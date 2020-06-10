<template>
<div>
    <div class="set-module" style="padding-bottom:10px">
        <el-button type="primary" size="small" @click="$router.push({name:'ThemeList'})">返回</el-button>
        <span style="margin-left:10px">当前主题：<strong>{{ $route.query.name }}</strong></span>
    </div>
    <div class="set-module" v-loading="loading">
        <div class="set-module-title">
            <span style="margin-right:20px">主题颜色选择</span>
            <el-button type="primary" size="mini" @click="changeTheme" :loading="colorLoad">保存</el-button>
        </div>
        <div>
            <div v-if="!selectTheme" style="margin:10px 10px 20px"><strong>提示：暂未选择主题颜色</strong></div>
            <el-radio v-for="(color,index) in colors" :key="index" :label="color.value" v-model="selectTheme">
                <div class="theme-template">
                    <el-image fit="scale-down" :src="color.img" style="width:160px;height:284px;"></el-image>
                </div>
                <div style="text-align:center;margin:10px 0 20px">{{ color.name }}</div>
            </el-radio>
        </div>
    </div>
    <div class="set-module" v-loading="loading">
        <div class="set-module-title">
            <span style="margin-right:20px">广告图设置</span>
            <el-button type="primary" size="mini" @click="changeTemplate('banners')" :loading="loadGroup.banners">保存</el-button>
        </div>
        <div>
            <div class="module-template">
                <div class="module-template-title">模块设置示例</div>
                <el-image src="//asset.ibanquan.com/image/5eb5d62936a6ce0021f438e4/s.jpeg?v=1588975146" style="width:240px;height:183.5px" fit="scale-down"></el-image>
                <div class="module-template-tip">
                    此模块置于主页顶部，搜索框之下，请上传<strong>尺寸 750 * 500 像素</strong>的图片
                </div>
            </div>
            <div class="module-content">
                <div class="module-content-title">
                    <div style="width:150px">图片</div>
                    <div style="width:calc(100% - 274px);margin:0 5px;">链接</div>
                    <div style="width:80px">操作</div>
                </div>
                <div v-for="(banner,index) in banners" :key="index" class="module-content-content">
                    <div class="module-content-image" style="position:relative">
                        <div class="image-cover-button" style="line-height:100px;height:100px;width:150px">
                            <el-button type="text" size="small" @click="changeImage('banner',index)">选择</el-button>
                        </div>
                        <el-image :src="banner.img" fit="scale-down" style="width:150px;height:100px"></el-image>
                    </div>
                    <div class="link-input" style="width:calc(100% - 274px);margin:0 5px;" @click="getLink('banner',index)">
                        <div :class="{'is-error':bannerDisabled.indexOf(banner.link.query) !== -1}">
                            <span v-if="banner.link.name">{{ banner.link.name }}</span>
                            <span v-else class="link-input-placeholder">选填，广告图跳转链接</span>
                        </div>
                        <div class="error-tip" v-if="bannerDisabled.indexOf(banner.link.query) !== -1">商品已删除，为不影响购买体验，请及时更换链接，并保存模块</div>
                    </div>
                    <div class="module-content-button" style="width:80px">
                        <span @click="toFront('banner',index)"><i class="iconfont iconxiangshang"></i></span>
                        <span @click="toBack('banner',index)"><i class="iconfont iconxiangxia"></i></span>
                        <span @click="toDelete(index)"><i class="iconfont iconshanchu1"></i></span>
                    </div>
                </div>
                <div style="margin-top:20px">
                    <div><el-button size="small" type="primary" @click="addBanner">添加广告图</el-button></div>
                    <div class="module-tip">提示：最多可添加6张广告图</div>
                </div>
            </div>
        </div>
    </div>
    <div class="set-module">
        <div class="set-module-title">
            <span style="margin-right:20px">分类标签设置</span>
            <el-button type="primary" size="mini" @click="changeTemplate('types')" :loading="loadGroup.types">保存</el-button>
        </div>
        <div>
            <div class="module-template">
                <div class="module-template-title">模块设置示例</div>
                <el-image src="//asset.ibanquan.com/image/5eb5d88336a6ce0016f43d03/s.jpeg?v=1588975747" fit="scale-down"></el-image>
                <div class="module-template-tip">
                    此模块置于主页广告图下方，请上传<strong>尺寸 50 * 50 像素</strong>的图片
                </div>
            </div>
            <div class="module-content">
                <div class="module-content-title"><strong>是否显示</strong></div>
                <div style="margin-bottom:30px;">
                    <el-switch v-model="types.visible" active-text="显示" inactive-text="不显示"></el-switch>
                </div>
                <div class="module-content-title">
                    <div style="width:50px">图片</div>
                    <div style="width:100px;margin:0 5px;">名称</div>
                    <div style="width:calc(100% - 230px);margin:0 5px;">链接</div>
                    <div style="width:60px">操作</div>
                </div>
                <div v-for="(type,index) in types.items" :key="index" class="module-content-content">
                    <div class="module-content-image" style="width:50px;position:relative">
                        <div class="image-cover-button" style="line-height:50px;height:50px;width:50px">
                            <el-button type="text" size="small" @click="changeImage('type',index)">选择</el-button>
                        </div>
                        <el-image :src="type.img" fit="scale-down" style="width:50px;height:50px"></el-image>
                    </div>
                    <el-input maxlength="4" v-model="type.title" style="width:100px;margin:0 5px;" placeholder="4个字以内"></el-input>
                    <div class="link-input" style="width:calc(100% - 262px);margin:0 5px;" @click="getLink('type',index)">
                        <div :class="{'is-error':typeDisabled.indexOf(type.link.query) !== -1}">
                            <span v-if="type.link.name">{{ type.link.name }}</span>
                            <span v-else class="link-input-placeholder">选填，广告图跳转链接</span>
                        </div>
                        <div class="error-tip" v-if="typeDisabled.indexOf(type.link.query) !== -1">商品已删除，为不影响购买体验，请及时更换链接，并保存模块</div>
                    </div>
                    <div class="module-content-button" style="width:60px">
                        <span @click="toFront('type',index)"><i class="iconfont iconxiangshang"></i></span>
                        <span @click="toBack('type',index)"><i class="iconfont iconxiangxia"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="set-module">
        <div class="set-module-title">
            <span style="margin-right:20px">消息通知</span>
            <el-button type="primary" size="mini" @click="changeTemplate('message')" :loading="loadGroup.message">保存</el-button>
        </div>
        <div>
            <div class="module-template">
                <div class="module-template-title">模块设置示例</div>
                <el-image src="//asset.ibanquan.com/image/5eb5d95f36a6ce000bf43dab/s.jpeg?v=1588975968" fit="scale-down"></el-image>
                <div class="module-template-tip">
                    此模块置于主页分类标签下方，如果文字内容超过手机屏幕宽度，将<strong>自动滚动播放</strong>
                </div>
            </div>
            <div class="module-content">
                <div class="module-tip" style="margin-bottom:10px">提示：如果设置消息将自动显示，如果不设置将隐藏此模块</div>
                <el-input v-model="message" placeholder="请输入主页通知的消息"></el-input>
            </div>
        </div>
    </div>
    <div class="set-module">
        <div class="set-module-title">
            <span style="margin-right:20px">限时打折</span>
            <el-button type="primary" size="mini" @click="changeTemplate('limit')" :loading="loadGroup.limit">保存</el-button>
        </div>
        <div>
            <div class="module-template">
                <div class="module-template-title">模块设置示例</div>
                <el-image src="//asset.ibanquan.com/image/5eb5da4636a6ce000bf43dae/s.jpeg?v=1588976198" fit="scale-down"></el-image>
                <div class="module-template-tip">
                    <div>此模块置于主页消息通知下方</div>
                    <div>可设置优惠结束时间，如果结束时间距离客户浏览时间为0秒时，会<strong>自动提示活动已结束</strong></div>
                    <div>最多可添加<strong>10个</strong>商品，<strong>向右滑动</strong>即可浏览商品</div>
                </div>
            </div>
            <div class="module-content">
                <div class="module-content-title"><strong>是否显示</strong></div>
                <div style="margin-bottom:30px;">
                    <el-switch v-model="limit.visible" active-text="显示" inactive-text="不显示"></el-switch>
                </div>
                <div style="margin-bottom:10px"><strong>活动结束时间</strong></div>
                <el-date-picker v-model="limit.endTime" type="datetime"></el-date-picker>
                <div style="margin:30px 0 10px"><strong>活动商品</strong></div>
                <div>
                    <div v-if="limit.items.leng" style="margin-bottom:10px" class="module-tip">暂无商品</div>
                    <div v-for="(item,index) in limit.items" :key="index" class="module-content-items" style="position:relative">
                        <div class="module-content-items-disabled" v-if="limitDisabled.indexOf(item.id) !== -1">
                            <div>此商品已被删除，为不影响购买体验，请及时删除该商品，并保存模块</div>
                            <div style="text-align:center"><el-button type="text" @click="deleteItem('limit',index)">删除</el-button></div>
                        </div>
                        <div class="module-content-items-button">
                            <span @click="deleteItem('limit',index)"><i class="iconfont iconshanchu1"></i></span>
                        </div>
                        <el-image :src="item.img" fit="scale-down" style="width:180px;height:180px;"></el-image>
                        <div class="input-group">
                            <el-input v-model="item.product_title" maxlength="10" placeholder="商品名称，10个字以内"></el-input>
                            <el-input v-model="item.sub_title" placeholder="宣传语，8个字以内" maxlength="8"></el-input>
                            <el-input type="number" step="0.01" v-model="item.price" placeholder="商品售价"></el-input>
                            <el-input type="number" step="0.01" v-model="item.ori_price" placeholder="商品原价"></el-input>
                        </div>
                    </div>
                    <div>
                        <el-button type="primary" size="mini" @click="addItem('limit')">新增商品</el-button>
                        <div class="module-tip">提示：最多可添加10个商品</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="set-module">
        <div class="set-module-title">
            <span style="margin-right:20px">今日上新</span>
            <el-button type="primary" size="mini" @click="changeTemplate('newitem')" :loading="loadGroup.newitem">保存</el-button>
        </div>
        <div>
            <div class="module-template">
                <div class="module-template-title">模块设置示例</div>
                <el-image src="//asset.ibanquan.com/image/5eb5db536da86b0013760803/s.jpeg?v=1588976468" fit="scale-down"></el-image>
                <div class="module-template-tip">
                    <div>此模块置于主页限时折扣下方</div>
                    <div>最多可添加<strong>10个</strong>商品</div>
                </div>
            </div>
            <div class="module-content">
                <div class="module-content-title"><strong>是否显示</strong></div>
                <div style="margin-bottom:30px;">
                    <el-switch v-model="newitem.visible" active-text="显示" inactive-text="不显示"></el-switch>
                </div>
                <div><strong>活动商品</strong></div>
                <div>
                    <div v-if="newitem.items.leng" style="margin-bottom:10px" class="module-tip">暂无商品</div>
                    <div v-for="(item,index) in newitem.items" :key="index" class="module-content-items" style="position:relative">
                        <div class="module-content-items-disabled" v-if="limitDisabled.indexOf(item.id) !== -1">
                            <div>此商品已被删除，为不影响购买体验，请及时删除该商品，并保存模块</div>
                            <div style="text-align:center"><el-button type="text" @click="deleteItem('newitem',index)">删除</el-button></div>
                        </div>
                        <div class="module-content-items-button">
                            <span @click="deleteItem('newitem',index)"><i class="iconfont iconshanchu1"></i></span>
                        </div>
                        <el-image :src="item.img" fit="scale-down" style="width:180px;height:180px"></el-image>
                        <div class="input-group">
                            <el-input v-model="item.product_title" placeholder="商品名称，20个字以内" maxlength="20"></el-input>
                            <el-input v-model="item.sub_title" placeholder="宣传语，8个字以内" maxlength="8"></el-input>
                            <el-input type="number" step="0.01" v-model="item.price" placeholder="商品售价"></el-input>
                            <el-input type="number" step="0.01" v-model="item.ori_price" placeholder="商品原价"></el-input>
                        </div>
                    </div>
                    <div>
                        <el-button type="primary" size="mini" @click="addItem('newitem')">新增商品</el-button>
                        <div class="module-tip">提示：最多可添加10个商品</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="set-module" style="margin-bottom:60px">
        <div class="set-module-title">
            <span style="margin-right:20px">单个商品推荐</span>
            <el-button type="primary" size="mini" @click="changeTemplate('ad')" :loading="loadGroup.ad">保存</el-button>
        </div>
        <div>
            <div class="module-template">
                <div class="module-template-title">模块设置示例</div>
                <el-image src="//asset.ibanquan.com/image/5eb5dbf836a6ce0016f43d06/s.jpeg?v=1588976632" fit="scale-down"></el-image>
                <div class="module-template-tip">
                    此模块置于主页今日上新下方，请上传<strong>尺寸 750 * 600 像素</strong>的图片
                </div>
            </div>
            <div class="module-content">
                <div class="module-content-title"><strong>是否显示</strong></div>
                <div style="margin-bottom:30px;">
                    <el-switch v-model="ad.visible" active-text="显示" inactive-text="不显示"></el-switch>
                </div>
                <div class="module-ad-content">
                    <div>
                        <el-image :src="ad.img" fit="scale-down" style="width:150px;height:120px"></el-image>
                        <div>
                            <el-button type="text" size="small" @click="changeImage('ad',null)">更换图片</el-button>
                        </div>
                    </div>
                    <div class="input-group">
                        <el-input v-model="ad.title" maxlength="15" placeholder="商品名称，15个字以内"></el-input>
                        <el-input v-model="ad.sub_title" maxlength="10" placeholder="宣传语，10个字以内"></el-input>
                        <el-input type="number" step="0.01" v-model="ad.price" placeholder="商品售价"></el-input>
                        <div class="link-input" @click="getLink('ad',null)">
                            <div :class="{'is-error':adDisabled.indexOf(ad.link.query) !== -1}" style="border:none">
                                <span v-if="ad.link.name">{{ ad.link.name }}</span>
                                <span v-else class="link-input-placeholder">选填，广告图跳转链接</span>
                            </div>
                            <div class="error-tip" v-if="adDisabled.indexOf(ad.link.query) !== -1" style="padding-bottom:10px">商品已删除，为不影响购买体验，请及时更换链接，并保存模块</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <image-dialog :Visible="imageShow" Type="mono" @close="imageShow = false" @confirm="confirmImage"></image-dialog>
    <item-dialog :Visible="addShow" :Current="current_items" @close="addShow = false" @confirm="confirmItem"></item-dialog>
    <link-dialog :Visible="linkShow" @close="linkShow = false" @confirm="confirmLink"></link-dialog>
</div>
</template>

<script>
import { get_theme, change_theme } from '@/api/theme'
import { get_product } from '@/api/product'
import ImageDialog from '@/components/ImageDialog'
import ItemDialog from '@/components/ItemDialog'
import LinkDialog from '@/components/LinkDialog'
export default{
    components:{
        ImageDialog,
        ItemDialog,
        LinkDialog
    },
    data(){
        return{
            loading:false,
            colors:[
                { value:'summer', name:'夏日艳阳', img:'//asset.ibanquan.com/image/5eb5d27c6da86b000f7608b1/s.jpeg?v=1588974204' },
                { value:'water', name:'清爽蓝', img:'//asset.ibanquan.com/image/5eb5d2de36a6ce0021f438e1/s.jpeg?v=1588974302' },
                { value:'spring', name:'春日绿', img:'//asset.ibanquan.com/image/5eb5d35f6da86b000b7603e2/s.jpeg?v=1588974431' },
                { value:'forest', name:'森林绿', img:'//asset.ibanquan.com/image/5eb5d3b736a6ce002cf43ca1/s.jpeg?v=1588974519' },
                { value:'purple', name:'静谧紫', img:'//asset.ibanquan.com/image/5eb5d1f036a6ce0016f43d00/s.jpeg?v=1588974065' },
            ],
            selectTheme:'',
            template:{},
            banners:[
                { img:'', link:{ link:null, name:null, query:null }, sort:0 }
            ],
            types:{
                visible:true,
                items:[
                    { img:'', title:'全部商品', link:{ link:null, name:null, query:null }, sort:0 },
                    { img:'', title:'热卖商品', link:{ link:null, name:null, query:null }, sort:1 },
                    { img:'', title:'最新上架', link:{ link:null, name:null, query:null }, sort:2 },
                    { img:'', title:'优惠特卖', link:{ link:null, name:null, query:null }, sort:3 }
                ]
            },
            message:'',
            limit:{
                visible:true,
                endTime:'',
                items:[]
            },
            newitem:{
                visible:true,
                items:[]
            },
            ad:{
                visible:true,
                img:'',
                title:'',
                sub_itle:'',
                price:'',
                ori_price:'',
                link:{ link:null, name:null, query:null }
            },
            imageShow:false,
            current:{
                type:null,
                index:null
            },
            current_items:[],
            addShow:false,
            linkShow:false,
            limitDisabled:[],
            newDisabled:[],
            bannerDisabled:[],
            typeDisabled:[],
            adDisabled:[],
            colorLoad:false,
            loadGroup:{
                banners:false,
                types:false,
                message:false,
                limit:false,
                newitem:false,
                ad:false
            }
        }
    },
    created(){
        this.getTheme()
    },
    methods:{
        getTheme(){
            this.loading = true
            get_theme(this.$route.query.id).then(r=>{
                let _data = r.data.body
                this.selectTheme = _data.theme
                this.banners = _data.banners
                this.limit = _data.limit
                this.message = _data.message
                this.newitem = _data.newitem
                this.types = _data.types
                this.ad = _data.ad
                this.template = _data
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        changeTheme(){
            this.template.theme = this.selectTheme
            this.colorLoad = true
            change_theme({setting:this.template},this.$route.query.id).then(()=>{
                this.getTheme()
                this.$message.success('修改成功')
                this.colorLoad = false
            }).catch(()=>{
                this.colorLoad = false
            })
        },
        testItems(type,data){
            let _data = type === 'limit' ? this.limitDisabled : this.newDisabled
            data.map(v=>{
                get_product(v.id).then(()=>{}).catch(e=>{
                    if(e.response.status === 404) _data.push(v.id)
                })
            })
        },
        testLink(type,data){
            let _data = type === 'banner' ? this.bannerDisabled : (type === 'type' ? this.typeDisabled : this.adDisabled)
            data.map(v=>{
                get_product(v.query).then(()=>{}).catch(e=>{
                    if(e.response.status === 404) _data.push(v.query)
                })
            })
        },
        changeTemplate(type){
            let _content = type === 'banners' ? this.banners : (type === 'types' ? this.types : (type === 'message' ? this.message : (type === 'newitem' ? this.newitem : (type === 'limit' ? this.limit : this.ad))))
            this.template[type] = _content
            this.loadGroup[type] = true
            change_theme({setting:this.template},this.$route.query.id).then(()=>{
                this.$message.success('保存成功')
                this.getTheme()
                this.loadGroup[type] = false
            }).catch(e=>{
                if(e.response.status !== 401) this.$message.warning(e.response.data.message)
                this.loadGroup[type] = false
            })
        },
        changeImage(type,index){
            this.imageShow = true
            this.current.type = type
            this.current.index = index
        },
        confirmImage(val){
            let _data = this.current.type === 'banner' ? this.banners : (this.current.type === 'type' ? this.types : this.ad)
            if(this.current.index !== null && this.current.type === 'banner'){
                _data[this.current.index].img = val[0].url
            }else if(this.current.index !== null && this.current.type === 'type'){
                _data.items[this.current.index].img = val[0].url
            }else{
                _data.img = val[0].url
            }
            this.imageShow = false
        },
        addBanner(){
            if(this.banners.length === 6){
                this.$message.warning('已有6张广告图')
                return
            }
            this.banners.push({
                img:'',
                link:{ link:null, name:null, query:null },
                sort:this.banners.length
            })
        },
        toFront(type,index){
            let _data = type === 'banner' ? this.banners : this.types
            if(_data[index].sort === 0) return
            _data[index - 1].sort += 1
            _data[index].sort -= 1
            _data[index] = _data.splice(index - 1, 1, _data[index])[0]
        },
        toBack(type,index){
            let _data = type === 'banner' ? this.banners : this.types
            if(_data[index].sort === _data.length - 1) return
            _data[index + 1].sort -= 1
            _data[index].sort += 1
            _data[index] = _data.splice(index + 1, 1, _data[index])[0]
        },
        toDelete(index){
            if(this.banners.length <= 1){
                this.$message.warning('至少添加一个广告图')
                return
            }
            this.$confirm('是否确认删除？','提示',{
                confirmButtonText:'确定',
                cancelButtonText:'取消'
            }).then(()=>{
                this.banners.splice(index,1)
                this.banners.map((v,n)=>{
                    v.sort = n
                })
            }).catch(()=>{})
        },
        addItem(type){
            let _data = type === 'limit' ? this.limit : this.newitem
            if(_data.items.length >= 10){
                this.$message.warning('最多可选择10个商品')
                return
            }
            this.addShow = true
            this.current.type = type
            this.current_items = []
            _data.items.map(v=>{
                this.current_items.push(v.id)
            })
        },
        confirmItem(val){
            let _data = this.current.type === 'limit' ? this.limit : this.newitem
            let _current = []
            _data.items.map(v=>{
                _current.push(v.id)
            })
            val.map(v=>{
                if(_current.indexOf(v.id) === -1){
                    _data.items.push({
                        id:v.id,
                        img:v.img,
                        product_title:v.product_title,
                        sub_title:'',
                        price:v.price,
                        ori_price:''
                    })
                }
            })
            this.addShow = false
        },
        deleteItem(type,index){
            let _data = type === 'limit' ? this.limit : this.newitem
            this.$confirm('是否确认删除？', '提示',{
                confirmButtonText:'确定',
                cancelButtonText:'取消'
            }).then(()=>{
                _data.items.splice(index,1)
            }).catch(()=>{})
        },
        getLink(type,index){
            this.current.type = type
            this.current.index = index
            this.linkShow = true
        },
        confirmLink(val){
            let _data = this.current.type === 'banner' ? this.banners : (this.current.type === 'type' ? this.types : this.ad)
            if(this.current.index !== null && this.current.type === 'banner'){
                _data[this.current.index].link = val
            }else if(this.current.index !== null && this.current.type === 'type'){
                _data.items[this.current.index].link = val
            }else{
                _data.link = val
            }
            this.linkShow = false
        }
    }
}
</script>

<style lang="scss">
@import '@/assets/style/base.scss';
.set-module .el-radio__inner{
    position: absolute;
    margin-top: -275px;
    margin-left: 17px;
    z-index: 2;
}
.module-content-items .el-input__inner,.module-ad-content .el-input__inner{
    border:none;
    border-bottom:1px solid $line-color;
    border-radius:0;
}
.module-content-items .input-group>div:first-child .el-input__inner,.module-ad-content .input-group>div:first-child .el-input__inner{
    border-radius:5px 5px 0 0;
}
.module-content-items .input-group>div:last-child .el-input__inner,.module-ad-content .input-group>div:last-child .el-input__inner{
    border-bottom:none;
    border-radius:0 0 5px 5px;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.set-module{
    margin-bottom:10px;
    background-color:#fff;
    padding:10px 10px 25px;
    border-radius:5px;
}
.set-module-title{
    font-size:$large-font-size;
    line-height:40px;
    margin-bottom:10px;
    padding-bottom:10px;
    border-bottom:1px solid $line-color * 0.98;
}
.module-template{
    display:inline-block;
    width:240px;
    padding:20px 10px;
    background-color:$line-color * 1.02;
    vertical-align:top;
}
.module-template-title{
    margin-bottom:15px;
    color:$sub-font-color;
}
.module-template-tip{
    margin-top:15px;
}
.module-content{
    display:inline-block;
    width:calc(100% - 280px);
    margin-left:20px;
    vertical-align:top;
}
.module-content-title>div,.module-content-content>div{
    display:inline-block;
    vertical-align:middle;
}
.module-content-title{
    padding:10px 0;
    border-bottom:1px dotted $line-color;
    margin-bottom:10px;
}
.module-content-content{
    margin-bottom:10px;
}
.module-content-image .el-image,.module-content-items .el-image{
    border:1px solid $line-color;
}

.image-cover-button{
    position:absolute;
    z-index:2;
    text-align:center;
    background-color:rgba(0,0,0,0.5);
    display:none;
}
.module-content-image:hover .image-cover-button{
    display:block;
}
.module-tip{
    color:$other-font-color;
    margin-top:10px;
}
.module-content-button span{
    color:$sub-font-color;
    cursor:pointer;
}
.module-content-button span:hover{
    color:$main-color;
}

.module-content-items{
    width:180px;
    margin:10px 10px 10px 0;
    display:inline-block;
    vertical-align:top;
}
.module-content-items .input-group,.module-ad-content .input-group{
    border:1px solid $line-color;
    border-radius:5px;
}

.module-content-items-button{
    position:absolute;
    height:50px;
    width:182px;
    margin-top:-10px;
    line-height:50px;
    text-align:center;
    color:#fff;
    background-color:rgba(0,0,0,0.6);
    opacity:0;
    z-index:2;
}
.module-content-items-button i{
    font-size:30px;
}
.module-content-items:hover .module-content-items-button{
    transition:all .2s ease-in;
    transform:translateY(10px);
    opacity:1;
}
.module-content-items-disabled{
    position:absolute;
    height:100px;
    width:161px;
    padding:125px 10px;
    background-color:rgba(0,0,0,0.8);
    color:#fff;
    border-radius:5px;
    z-index:3;
}

.module-ad-content>div{
    display:inline-block;
    vertical-align:top;
}
.module-ad-content>div:last-child{
    margin-left:10px;
    width:calc(100% - 162px);
}
.link-input>div:first-child{
    padding:10px 15px;
    border:1px solid $line-color;
    border-radius:5px;
    color:$sub-font-color;
    cursor:pointer;
}
.link-input:hover{
    color:$main-color * 1.2;
    border-color:$main-color * 1.2;
}
.link-input-placeholder{
    color:#cdcdcd;
}
.link-input .is-error{
    color:$error-color!important;
    border-color:$error-color;
}
.link-input .error-tip{
    font-size:$small-font-size;
    color:$error-color;
    padding-left:15px;
}

.theme-template{
    height:284px;
    padding:6px 6px 20px;
    border-radius:5px;
    background-color:$line-color;
    border:2px solid $line-color;
}
.is-checked .theme-template{
    border-color:$main-color;
}
</style>