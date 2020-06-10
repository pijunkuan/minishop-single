<template>
<div>
    <div class="product-page-title">
        <el-button size="small" @click="$router.push({name:'ProductList'})">返回商品列表</el-button>
        <el-button size="small" type="primary" style="float:right" @click="confirmChange">保存</el-button>
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
                <tinymce></tinymce>
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
</div>
</template>

<script>
import { create_product } from '@/api/product'
import { get_categories } from '@/api/category'
import { get_shipment } from '@/api/ship'
import ImageDialog from '@/components/ImageDialog'
import Tinymce from '@/components/Tinymce'
import Clickoutside from 'element-ui/src/utils/clickoutside'
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
                product:{
                    on_sale:0
                },
                images:[],
                variants:[
                    { variant_title:null, variant_code:null, quantity:null, weight:null, price:null, ori_price:null }
                ],
                categories:[]
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
            btnLoading:false
        }
    },
    created(){
        this.getShipments()
    },
    beforeRouteLeave(to,from,next){
        if(to.name === 'ProductList') to.meta.keepAlive = true
            else to.meta.keepAlive = false
        next()
    },
    methods:{
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
        getShipments(){
            get_shipment().then(r=>{
                this.shipments = r.data.body
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
            if(image.sort === 1) return
            image.sort -= 1
            this.item.images[index - 1].sort += 1
            this.item.images[index] = this.item.images.splice(index - 1, 1, this.item.images[index])[0]
        },
        imgBack(image,index){
            if(image.sort === this.item.images.length) return
            image.sort += 1
            this.item.images[index + 1].sort -= 1
            this.item.images[index] = this.item.images.splice(index + 1, 1, this.item.images[index])[0]
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
            if(!this.item.product.shipment_id){
                this.$message.warning('请选择物流模版')
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
                    image_id:v.id,
                    sort:v.sort
                })
            })
            this.item.images = images
            create_product(this.item).then(()=>{
                this.$message.success('商品创建成功')
                this.$router.push({name:'ProductList'})
                this.btnLoading = false
            }).catch(e=>{
                this.$message.warning(e.response.data.message)
                this.btnLoading = false
            })
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
</style>