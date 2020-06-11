<template>
<div>
    <div class="product-page-title">
        <el-button size="small" @click="$router.go(-1)">返回</el-button>
        <span class="page-item-title">{{ item.product.product_title }}</span>
        <span style="float:right">
            <x-component is="sk-address-edit"></x-component>
            <el-button size="small" type="primary" @click="toPreview">手机预览</el-button>
            <el-button size="small" type="primary" @click="toShare">分享商品</el-button>
            <el-button size="small" type="primary" @click="confirmChange">保存</el-button>
        </span>
    </div>
    <div class="product-page-content">
        <div class="item-content">
            <div>商品名称</div>
            <el-input v-model="item.product.product_title"></el-input>
        </div>
        <div class="item-content">
            <div>商品简介</div>
            <el-input v-model="item.product.product_des"></el-input>
        </div>
        <div class="item-content" style="position:relative">
            <div>商品分类</div>
            <div>
                <el-button size="small" style="margin-right:30px" @click="toSelect">选择分类</el-button>
                <span v-for="(cate,index) in item.categories" :key="index" class="cate-item" @click="cateDelete(index)">
                    <span>{{ cate.category_title }}</span>
                    <i class="iconfont iconguanbi"></i>
                </span>
            </div>
            <transition name="type">
                <div class="item-type-select" v-show="typeShow" v-clickoutside="closeType">
                    <div class="item-type-select__header">分类列表</div>
                    <div v-if="t_loading" class="item-type-select__placeholder">加载中...</div>
                    <div v-if="!t_loading && types.length === 0" class="item-type-select__placeholder">暂无分类</div>
                    <ul v-if="!t_loading && types.length !== 0" class="item-type-select__content">
                        <li v-for="(type,index) in types" :key="index" :class="{'is-active':selectId.indexOf(type.id) !== -1,'is-disabled':currentType.indexOf(type.id) !== -1}">
                            <div @click="selectType(type)">{{ type.category_title }}</div>
                            <div><i v-if="selectId.indexOf(type.id) !== -1" class="iconfont iconguanbi" @click="deleteType(type)"></i></div>
                        </li>
                    </ul>
                    <div class="item-type-select__footer">
                        <el-button size="small" @click="closeType">取消</el-button>
                        <el-button size="small" type="primary" @click="addType">确定</el-button>
                    </div>
                </div>
            </transition>
        </div>
        <div class="item-content">
            <div>商品图片</div>
            <div class="item-content-image">
                <div v-for="(image,index) in item.images" :key="index" @mouseenter="activeIndex = index" @mouseleave="activeIndex = null" class="image-items">
                    <div class="image-edit-button" :class="{'is-active':activeIndex === index}">
                        <div style="width:calc(100% - 30px);padding-left:5px">
                            <span @click="imgFront(image,index)"><i class="iconfont iconshangyiye"></i></span>
                            <span @click="imgBack(image,index)"><i class="iconfont iconxiayiye"></i></span>
                        </div>
                        <div style="width:20px;padding-right:5px;text-align:right" @click="imgDelete(image)"><i class="iconfont iconshanchu1"></i></div>
                    </div>
                    <el-image style="width:150px;height:150px" :src="image.url"></el-image>
                </div>
                <div class="image-add" @click="imageShow = true"><i class="iconfont iconjiahao"></i></div>
            </div>
        </div>
        <div class="item-content">
            <div>商品规格</div>
            <div class="item-variant-column item-variant-column__header">
                <div style="width:calc(100% - 556px)">规格名称<span>(必填)</span></div>
                <div style="width:80px">编号</div>
                <div style="width:80px">库存<span>(必填)</span></div>
                <div style="width:80px">重量/kg</div>
                <div style="width:80px">售价<span>(必填)</span></div>
                <div style="width:80px">原价</div>
                <div style="width:80px">操作</div>
            </div>
            <div class="item-variant-column" v-for="(variable,index) in item.variants" :key="index">
                <div style="width:calc(100% - 558px)">
                    <el-input v-model="variable.variant_title"></el-input>
                </div>
                <div style="width:80px">
                    <el-input v-model="variable.variant_code"></el-input>
                </div>
                <div style="width:80px">
                    <el-input v-model="variable.quantity" type="number"></el-input>
                </div>
                <div style="width:80px">
                    <el-input v-model="variable.weight" type="number" step="0.01"></el-input>
                </div>
                <div style="width:80px">
                    <el-input v-model="variable.price" type="number" step="0.01"></el-input>
                </div>
                <div style="width:80px">
                    <el-input v-model="variable.ori_price" type="number" step="0.01"></el-input>
                </div>
                <div style="width:80px">
                    <el-button type="text" @click="varDelete(index)">删除</el-button>
                    <el-button type="text" @click="varCopy(variable)">复制</el-button>
                </div>
            </div>
            <div style="margin-top:10px;">
                <el-button size="small" type="primary" @click="addVariable">添加规格</el-button>
            </div>
        </div>
        <div class="item-content">
            <div>商品详情</div>
            <div>
                <tinymce v-model="item.content"></tinymce>
            </div>
        </div>
        <div class="item-content">
            <div>物流模版</div>
            <div>
                <el-select v-model="item.product.shipment_id">
                    <el-option v-for="(shipment,index) in shipments" :key="index" :label="shipment.shipment_name" :value="shipment.id"></el-option>
                </el-select>
            </div>
        </div>
        <div class="item-content">
            <div>上架情况</div>
            <div>
                <el-switch v-model="item.product.on_sale" active-text="上架" inactive-text="下架"></el-switch>
            </div>
        </div>
        <div style="margin:20px 0 40px">
            <el-button @click="confirmChange" type="primary" :loading="btnLoading">保存</el-button>
        </div>
    </div>
    <image-dialog :Visible="imageShow" @close="imageShow = false" @confirm="addImage"></image-dialog>
    <el-dialog :visible="previewShow" title="手机预览" center width="300px" @close="closePreview">
        <div class="preview-qrcode">
            <div id="qrcode"></div>
            <div>扫描上方二维码，手机预览页面效果</div>
        </div>
        <div slot="footer">
            <el-button type="primary" size="small" @click="closePreview">关闭</el-button>
        </div>
    </el-dialog>
    <el-dialog :visible="shareShow" title="分享商品" width="500px" @close="closeShare">
        <div class="share-image-loading">
            <i class="el-icon-loading"></i>正在生成分享图片
        </div>
        <div class="share-image-block" ref="shareimg">
            <div ref="shareImage" class="share-img"></div>
            <div>分享至微信群、QQ群、微博，帮助您快速触达客户</div>
        </div>
        <div class="share-image-btn" ref="sharebtn">
            <div id="shareLink"></div>
        </div>
    </el-dialog>
    <div v-if="shareShow">
        <div class="share-box">
            <div id="shareBox" style="width:750px">
                <div v-if="item.images.length !== 0" class="share-content-img"><img id="itemImage" :src="item.images[0].url" /></div>
                <div v-if="item.images.length === 0" class="share-content-blank">
                    <div><i class="iconfont icontupian1"></i></div>
                </div>
                <div class="share-content-bottom">
                    <div>
                        <div class="share-title">{{ item.product.product_title }}</div>
                        <div class="share-price">¥ {{ item.variants[0].price }}</div>
                        <div class="share-subtext">长按右侧二维码识别，查看详情</div>
                    </div>
                    <div>
                        <div id="shareCode"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { get_product, edit_product } from '@/api/product'
import { get_categories } from '@/api/category'
import { get_shipment } from '@/api/ship'
import { to_base64 } from '@/api/tools'
import ImageDialog from '@/components/ImageDialog'
import Tinymce from '@/components/Tinymce'
import Clickoutside from 'element-ui/src/utils/clickoutside'
import { to_qrcode } from '@/vendor/qrcode'
import { to_canvas } from '@/vendor/html2canvas'
export default{
    components:{
        ImageDialog,
        Tinymce
    },
    directives:{
        Clickoutside
    },
    data(){
        return{
            item:{
                product:{},
                images:[],
                variants:[
                    { variant_title:null, variant_code:null, quantity:null, weight:null, price:null, ori_price:null }
                ],
                categories:[],
                content:''
            },
            activeIndex:null,
            imageShow:false,
            typeShow:false,
            t_loading:false,
            types:[],
            currentType:[],
            selectId:[],
            selectTypes:[],
            shipments:[],
            btnLoading:false,
            previewShow:false,
            shareShow:false,
            link:''
        }
    },
    created(){
        if(this.$route.query.id !== undefined) this.getItem()
        this.getShipments()
    },
    beforeRouteLeave(to,from,next){
        if(to.name === 'ProductList') to.meta.keepAlive = true
            else to.meta.keepAlive = false
        next()
    },
    methods:{
        getItem(){
            this.$loading()
            get_product(this.$route.query.id).then(r=>{
                this.item = r.data.body
                this.$loading().close()
            }).catch(()=>{
                this.$loading().close()
            })
        },
        getShipments(){
            get_shipment().then(r=>{
                this.shipments = r.data.body
            })
        },
        cateDelete(index){
            this.item.categories.splice(index,1)
        },
        toSelect(){
            this.item.categories.map(v=>{
                this.currentType.push(v.id)
            })
            this.selectTypes = []
            this.selectId = []
            this.typeShow = true
            this.getTypes()
        },
        getTypes(){
            this.t_loading = true
            get_categories().then(r=>{
                let _data = r.data.body
                this.types = _data
                this.t_loading = false
            }).catch(()=>{
                this.t_loading = false
            })
        },
        selectType(type){
            if(this.currentType.indexOf(type.id) !== -1) return
            const _index = this.selectId.indexOf(type.id)
            if(_index === -1){
                this.selectId.push(type.id)
                this.selectTypes.push(type)
            }
        },
        deleteType(type){
            const _index = this.selectId.indexOf(type.id)
            if(_index !== -1){
                this.selectId.splice(_index,1)
                this.selectTypes.splice(_index,1)
            }
        },
        closeType(){
            this.typeShow = false
            this.types = []
            this.currentType = []
        },
        addType(){
            if(this.selectId.length === 0){
                this.$message.warning('请选择分类')
                return
            }
            this.selectTypes.map(v=>{
                this.item.categories.push(v)
            })
            this.typeShow = false
            this.types = []
            this.currentType = []
        },
        imgFront(image,index){
            if(image.sort === 0) return
            const current_sort = image.sort
            const prev_sort = image.sort - 1
            image.sort = prev_sort
            this.item.images[index - 1].sort = current_sort
            this.item.images[index] = this.item.images[index - 1]
            this.item.images[index - 1] = image
        },
        imgBack(image,index){
            if(image.sort === (this.item.images.length - 1)) return
            const current_sort = image.sort
            const next_sort = image.sort + 1
            image.sort = next_sort
            this.item.images[index + 1].sort = current_sort
            this.item.images[index] = this.item.images[index + 1]
            this.item.images[index + 1] = image
        },
        imgDelete(index){
            this.item.images.splice(index,1)
        },
        addImage(val){
            this.imageShow = false
            const _last = this.item.images.length !== 0 ? this.item.images[this.item.images.length - 1].sort : 0
            val.map((v,n)=>{
                let temp = {
                    url:v.url,
                    id:v.id,
                    sort: _last + n + 1
                }
                this.item.images.push(temp)
            })
        },
        addVariable(){
            let item = {
                variant_title:null, 
                variant_code:null, 
                quantity:null, 
                weight:null, 
                price:null, 
                ori_price:null
            }
            this.item.variants.push(item)
        },
        varDelete(index){
            this.item.variants.splice(index,1)
        },
        varCopy(item){
            let _item = {
                variant_title:item.variant_title, 
                variant_code:item.variant_code, 
                quantity:item.quantity, 
                weight:item.weight, 
                price:item.price, 
                ori_price:item.ori_price
            }
            this.item.variants.push(_item)
        },
        confirmChange(){
            if(!this.item.product.product_title){
                this.$message.warning('商品名称为必填')
                return
            }
            if(this.item.variants.length === 0){
                this.$message.warning('商品至少应有一个规格')
                return
            }
            let _error = this.item.variants.filter((val)=>{ return val.variant_title === null || val.price === null || val.quantity === null })
            if(_error.length !== 0){
                this.$message.warning('商品规格填写不完整')
                return
            }
            this.btnLoading = true
            let cate = []
            this.item.categories.map(v=>{
                cate.push(v.id)
            })
            this.item.categories = cate
            let images = []
            this.item.images.map(v=>{
                images.push({
                    image_id:v.id !== undefined ? v.id : v.image_id,
                    sort:v.sort
                })
            })
            this.item.images = images
            edit_product(this.item,this.$route.query.id).then(()=>{
                this.$message.success('商品修改成功')
                this.btnLoading = false
                this.getItem()
            }).catch(e=>{
                this.$message.warning(e.response.data.message)
                this.btnLoading = false
            })
        },
        toPreview(){
            this.previewShow = true
            this.$nextTick(()=>{
                to_qrcode({
                    link:location.origin + '/product/' + this.$route.query.id,
                    height:200,
                    width:200,
                    id:'qrcode'
                })
            })
        },
        toShare(){
            this.shareShow = true
            this.$nextTick(()=>{
                let _image = document.getElementById('itemImage')
                if(_image){
                    const _type = _image.src.substr(_image.src.lastIndexOf('.') + 1)
                    to_base64({url:_image.src}).then(r=>{
                        _image.src = 'data:image/' + _type + ';base64,' + r.data
                        _image.setAttribute('crossOrigin','Anonymous')
                        to_qrcode({
                            link:location.origin + '/product/' + this.$route.query.id,
                            height:180,
                            width:180,
                            id:'shareCode'
                        })
                        to_canvas({
                            box:'shareBox',
                            image:'shareImage',
                            that:this,
                            link:'shareLink',
                            title:this.item.product.product_title,
                            show:['shareimg','sharebtn']
                        })
                    })
                }else{
                    to_qrcode({
                        link:location.origin + '/product/' + this.$route.query.id,
                        height:180,
                        width:180,
                        id:'shareCode'
                    })
                    to_canvas({
                        box:'shareBox',
                        image:'shareImage',
                        that:this,
                        link:'shareLink',
                        title:this.item.product.product_title,
                        show:['shareimg','sharebtn']
                    })
                }
            })  
        },
        closeShare(){
            let _image = this.$refs.shareImage
            while(_image.hasChildNodes()){
                _image.removeChild(_image.firstChild)
            }
            let _sharebtn = this.$refs.sharebtn
            let _shareimg = this.$refs.shareimg
            _shareimg.style.display = 'none'
            _sharebtn.style.display = 'none'

            let _link = document.getElementById('shareLink')
            while(_link.hasChildNodes()){
                _link.removeChild(_link.firstChild)
            }

            this.shareShow = false
        },
        closePreview(){
            let _qrcode = document.getElementById('qrcode')
            while(_qrcode.hasChildNodes()){
                _qrcode.removeChild(_qrcode.firstChild)
            }
            this.previewShow = false
        }
    }
}
</script>

<style>
.item-variant-column .el-input-number{
    width:80px;
}
.item-variant-column .el-input-number .el-input__inner{
    padding:0 15px;
    text-align:left;
}
.item-variant-column .el-input-number__decrease,.item-variant-column .el-input-number__increase{
    display:none;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.product-page-title{
    border-bottom:1px solid $line-color;
    background-color:#fff;
    padding:10px;
    border-radius:5px 5px 0 0;
}
.page-item-title{
    font-size:14px;
    font-weight:500;
    margin-left:10px;
}
.product-page-content{
    padding:10px;
    border-radius:0 0 5px 5px;
    background-color:#fff;
}
.item-content{
    margin:10px 0;
    padding-bottom:20px;
    border-bottom:1px dotted $line-color;
}
.item-content>div:first-child{
    line-height:40px;
}
.item-variant-column>div{
    display:inline-block;
    font-size:$small-font-size;
    padding:5px;
    border-right:1px solid $line-color * 1.01;
}
.item-variant-column>div:first-child{
    border-left:1px solid $line-color * 1.01;
}
.item-variant-column>div:last-child{
    margin-right:0;
}
.item-variant-column{
    border-bottom:1px solid $line-color;
}
.item-variant-column__header{
    line-height:30px;
    background-color:$background-color;
    border:1px solid $line-color;
}
.item-variant-column__header>div{
    padding:0 5px;
}
.item-variant-column__header>div span{
    color:$error-color;
}
.item-variant-column__header>div:first-child{
    border-left:none;
}
.item-variant-column__header>div:last-child{
    border-right:none;
}

.item-content-image>div{
    display:inline-block;
    vertical-align:top;
    margin-right:20px;
}
.item-content-image .image-add{
    width:150px;
    height:150px;
    line-height:150px;
    text-align:center;
    border:1px dotted $other-font-color;
}
.item-content-image .image-add i{
    color:$line-color;
    font-size:50px;
}
.item-content-image .image-add:hover{
    border-color:$main-color;
    cursor:pointer;
}
.item-content-image .image-add:hover i{
    color:$main-color;
}
.image-items{
    position:relative;
    width:150px;
    height:150px;
    border:1px solid $line-color;
    margin-bottom:10px;
}
.image-edit-button{
    position:absolute;
    margin-top:130px;
    height:30px;
    width:150px;
    line-height:30px;
    z-index:2;
    background-color:rgba(0,0,0,0.4);
    opacity:0;
    color:#fff;
}
.image-edit-button.is-active{
    transition:all .2s ease-in;
    transform:translateY(-10px);
    opacity:1;
}
.image-edit-button>div{
    display:inline-block;
    cursor:pointer;
}

.cate-item{
    position:relative;
    top:1px;
    padding:5px;
    margin-right:10px;
    border:1px solid $line-color;
    border-radius:3px;
    color:$sub-font-color;
    cursor:pointer;
}
.cate-item:hover{
    color:$main-color;
    border-color:$main-color;
}
.item-type-select{
    position:absolute;
    margin-top:10px;
    max-width:600px;
    height:320px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:5px;
    box-shadow:0 0 5px 3px rgba(0,0,0,0.1);
    z-index:4;
}
.item-type-select__header{
    line-height:30px;
    margin:0 10px 10px;
    border-bottom:1px dotted $line-color;
}
.item-type-select__content{
    margin:0 10px;
    padding:0;
    list-style:none;
    height:210px;
}
.item-type-select__content li{
    line-height:30px;
    width:180px;
    margin-right:10px;
    display:inline-block;
    color:$sub-font-color;
}
.item-type-select__content li>div{
    display:inline-block;
    vertical-align:middle;
}
.item-type-select__content li>div:first-child{
    width:calc(100% - 25px);
    margin-right:5px;
    height:30px;
    overflow:hidden;
}
.item-type-select__content li>div:last-child{
    width:20px;
}
.item-type-select__content li.is-active{
    color:$main-color;
}
.item-type-select__content li.is-disabled{
    color:$disabled-color;
}
.item-type-select__content li:hover{
    cursor:pointer;
    color:$main-color * 1.2;
}
.item-type-select__content li.is-disabled:hover{
    color:$disabled-color;
}
.item-type-select__footer{
    margin:15px 10px 0;
    padding-top:10px;
    border-top:1px dotted $line-color;
    text-align:right;
}
.item-type-select__placeholder{
    text-align:center;
    line-height:210px;
    color:$other-font-color;
}

.preview-qrcode{
    text-align:center;
}
.preview-qrcode #qrcode{
    border:1px solid $line-color * 0.95;
    border-radius:5px;
    padding:5px;
    width:200px;
    margin:0 auto 10px;
}
.preview-qrcode>div:last-child{
    font-weight:500;
}


.share-box{
    opacity:0;
}
.share-content-img,.share-content-blank{
    width:550px;
    height:550px;
    padding:100px;
    text-align:center;
    background-color:#fff;
}
.share-content-img img{
    width:100%;
    height:100%;
    object-fit:scale-down;
}
.share-content-blank>div{
    width:550px;
    height:550px;
    line-height:550px;
    margin:auto;
}
.share-content-blank>div{
    background-color:$light-back-sub;
}
.share-content-blank>div i{
    font-size:300px;
    color:$light-back-main;
}
.share-content-bottom{
    padding:20px;
    background-color:#EBEEF5;
    width:710px;
}
.share-content-bottom>div{
    display:inline-block;
    vertical-align:middle;
}
.share-content-bottom>div:first-child{
    width:calc(100% - 190px);
    margin-right:10px;
}
.share-content-bottom>div:last-child{
    width:180px;
}
.share-title{
    font-size:30px;
    font-weight:500;
    line-height:40px;
    height:80px;
    overflow:hidden;
}
.share-price{
    margin-top:10px;
    font-size:30px;
    font-weight:500;
    color:#F56C6C;
    height:40px;
    line-height:40px;
    overflow:hidden;
}
.share-subtext{
    font-size:20px;
    color:#888;
    margin:20px 0;
    height:30px;
    line-height:30px;
    overflow:hidden;
}

.share-img{
    width:350px;
    height:455px;
    border:1px solid $line-color * 0.95;
    box-shadow:0 0 10px 8px rgba(0,0,0,0.1);
    border-radius:10px;
}

.share-image-block{
    position:absolute;
    background-color:$light-back-main;
    top:60px;
    width:500px;
    height:540px;
    left:0;
    text-align:center;
}
.share-image-block div{
    color:$other-font-color;
    margin:0 auto;
}
.share-image-block div:first-child{
    margin-top:20px;
    margin-bottom:20px;
}

.share-image-btn{
    margin-top:340px;
    text-align:center;
}
.share-image-btn a{
    color:$main-color;
    font-weight:500;
    text-decoration:none;
}

.share-image-block,.share-image-btn{
    display:none;
}

.share-image-loading{
    height:200px;
    line-height:200px;
    text-align:center;
    color:$other-font-color;
    font-size:20px;
}
.share-image-loading i{
    font-size:30px;
    margin-right:10px;
}
</style>