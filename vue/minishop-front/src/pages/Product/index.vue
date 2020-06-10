<template>
<div>
    <div class="product-container">
        <page-loading :loading="loading"></page-loading>
        <div v-if="noStock" class="item-soldout" :style="{marginTop:(width - 200) / 2 + 'px'}"><div>已抢光</div></div>
        <img-carousel
            type="fit"
            :height="width"
            :indicator="false"
            :images="item.images">
            <div class="product-item-placeholder" :style="{height:width + 'px',lineHeight:width + 'px'}">
                <i class="iconfont icontupian"></i>
            </div>
        </img-carousel>
        <div class="product-detail">
            <div class="product-detail-title">{{ item.product.product_title }}</div>
            <div class="product-detail-desc" v-if="item.desc">{{ item.product.product_desc }}</div>
            <div>
                <div class="product-detail-price" v-if="loading">
                    <span class="product-detail-price__price">¥ <strong>-</strong></span>
                </div>
                <div class="product-detail-price" v-if="item.variants.length === 1">
                    <span class="product-detail-price__price">¥ <strong>{{ item.variants[0].price !== undefined ? item.variants[0].price : '-' }}</strong></span>
                    <span class="product-detail-price__oriprice" v-if="item.variants[0].ori_price">¥ {{ item.variants[0].ori_price }}</span>
                </div>
                <div class="product-detail-price" v-if="item.variants.length > 1">
                    <span class="product-detail-price__price">¥ <strong>{{ item.variants[0].price + '-' + item.variants[item.variants.length - 1].price }}</strong></span>
                </div>
            </div>
            <!-- <div class="product-detail-sale">{{ item.sale }}人已购买</div> -->
        </div>
        <div class="product-content" style="margin-bottom:0">
            <div class="product-content-title">商品详情</div>
            <div class="product-content-content">
                <div v-if="item.content">
                    <div v-html="item.content"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-footer">
        <div class="product-footer-icon" @click="$router.push({name:'Home'})">
            <div><shop-icon name="shouye" size="small" class="product-footer-icon__icon"></shop-icon></div>
            <div>首页</div>
        </div>
        <div class="product-footer-icon" @click="shareShow = true">
            <div><shop-icon name="fenxiang" size="small" class="product-footer-icon__icon"></shop-icon></div>
            <div>分享</div>
        </div>
        <div class="product-footer-button">
            <shop-button type="border" :rounded="false" @click="toAdd('direct')">立即购买</shop-button>
        </div>
        <div class="product-footer-button">
            <shop-button :rounded="false" @click="toAdd('cart')">加入购物车</shop-button>
        </div>
    </div>
    <shop-popup :show="shareShow"
        size="medium"
        @close="shareShow = false">
        <div slot="title">分享至</div>
        <div slot="content"></div>
        <div slot="button">
            <shop-button :rounded="false" @click="shareShow = false"><i class="iconfont iconguanbi" style="font-size:22px"></i></shop-button>
        </div>
    </shop-popup>
    <shop-popup :show="variantShow" @close="closeVariant">
        <div slot="content">
            <div style="margin-bottom:20px">
                <shop-image class="pop-item-image" :src="item.images.length !== 0 ? item.images[0].img : ''" :width="100" rounded type="fit">
                    <div class="product-image-error" slot="error"><i class="iconfont icontupian"></i></div>
                </shop-image>
                <div class="pop-item-title">
                    <div>{{ item.product.product_title }}</div>
                    <div class="pop-item-title__price">¥ <strong>{{ selectVariant.id === undefined ? '' : selectVariant.price }}</strong></div>
                </div>
            </div>
            <div class="pop-item-content">
                <div class="pop-item-content__title">选择规格</div>
                <div class="pop-item-content__content">
                    <div v-for="(variable,index) in item.variants" :key="index">
                        <shop-radio 
                            type="button"
                            bordered rounded
                            size="medium"
                            v-model="selectId" 
                            :label="variable.id" 
                            @change="changeVariant(index)">
                            {{ variable.variant_title }}
                        </shop-radio>
                    </div>
                </div>
            </div>
            <div class="pop-item-content pop-item-content-quantity">
                <div class="pop-item-content__title">选择数量</div>
                <div class="pop-item-content__content">
                    <shop-input-num
                        :value="selectNum"
                        :max="selectVariant.limit ? selectVariant.limit : selectVariant.stock"
                        :min="1"
                        :disabled="!selectVariant.stock"
                        @change="getNum">
                    </shop-input-num>
                </div>
            </div>
        </div>
        <div slot="button">
            <shop-button 
                :rounded="false" 
                :loading="btnLoading"
                :disabled="!selectVariant.stock" 
                @click="confirmAdd">
                确 定
            </shop-button>
        </div>
    </shop-popup>
</div>
</template>

<script>
import { get_product } from '@/api/products'
import { add_cart, create_cart_cache } from '@/api/cart'
import { Toast, MessageBox } from 'mint-ui'
import ImgCarousel from '@/components/Modules/ImgTxtBlock/ImgCarousel'
export default{
    components:{
        ImgCarousel
    },
    data(){
        return{
            loading:false,
            width:0,
            item:{
                images:[],
                product:{},
                variants:[],
                content:null
            },
            noStock:false,
            shareShow:false,
            addType:'',
            variantShow:false,
            selectVariant:{},
            selectId:null,
            selectNum:1,
            btnLoading:false
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.width = window.innerWidth
        })
        window.onresize = () => {
            this.width = window.innerWidth
        }
    },
    created(){
        this.getDetail()
    },
    beforeRouteLeave(to,from,next){
        if(to.name === 'Types' || to.name === 'Search') to.meta.keepAlive = true
            else to.meta.keepAlive = false
        next()
    },
    methods:{
        getDetail(){
            this.loading = true
            get_product(this.$route.params.id).then(r=>{
                let _data = r.data.body
                _data.variants.sort((a,b)=>{
                    b.price * 1 - a.price * 1
                })
                let _stock = 0
                _data.variants.map(v=>{
                    _stock += v.stock
                })
                if(_stock) this.noStock = false
                    else this.noStock = true
                if(_data.images.length !== 0){
                    _data.images.map(v=>{
                        this.item.images.push({
                            img:v.url
                        })
                    })
                }
                this.item = _data
                this.loading = false
            }).catch(e=>{
                if(e.response.status === 404){
                    this.loading = false
                    Toast({
                        message:'商品已删除',
                        duration:1000
                    })
                    setTimeout(()=>{
                        this.$router.go(-1)
                    },1000)
                }else if(e.response.status === 422){
                    this.loading = false
                    Toast({
                        message:e.response.data.message,
                        duration:1000
                    })
                    setTimeout(()=>{
                        this.$router.go(-1)
                    },1000)
                }
            })
        },
        toAdd(type){
            if(this.item.variants[0].stock < 1){
                Toast('库存不足，逛逛别的吧')
                return
            }
            this.addType = type
            this.variantShow = true
            this.selectVariant = this.item.variants[0]
            this.selectId = this.selectVariant.id
        },
        changeVariant(index){
            this.selectVariant = this.item.variants[index]
            this.selectId = this.selectVariant.id
            this.selectNum = 1
        },
        getNum(val){
            this.selectNum = val
        },
        closeVariant(){
            this.variantShow = false
            this.selectNum = 0
            this.selectVariant = {}
            this.selectId = null
        },
        confirmAdd(){
            let _data = {
                variant_id:this.selectVariant.id,
                quantity:this.selectNum
            }
            if(this.addType === 'direct'){
                let _order = {
                    address:{},
                    items:[]
                }
                _order.items.push(_data)
                create_cart_cache(_order).then(r=>{
                    this.$router.push({name:'Checkout',query:{key:r.data.body.key}})
                }).catch(e=>{
                    if(e.response.status === 401) this.loginMsg()
                        else Toast(e.response.data.message)
                })
            }else{
                add_cart(_data).then(()=>{
                    Toast({
                        message:'添加成功',
                        duration:500
                    })
                    this.closeVariant()
                }).catch(e=>{
                    if(e.response.status === 401) this.loginMsg()
                        else Toast(e.response.data.message)
                })
            }
        },
        loginMsg(){
            MessageBox({
                title:'提示',
                message:'请登录后加入购物车',
                confirmButtonText:'现在登录',
                showCancelButton:true,
                cancelButtonText:'再逛逛'
            }).then(action=>{
                if(action === 'cancel'){
                    this.closeVariant()
                }else{
                    this.$router.push({name:'Login',query:{from:this.$route.name,type:'id',query:this.$route.query.id}})
                }
            })
        }
    }
}
</script>

<style>
.product-footer-icon__icon.small-icon{
    width:24px;
    height:24px;
    line-height:24px;
}
.product-footer-icon__icon.small-icon i{
    font-size:18px;
}
.product-content-content img{
    width:100%;
    heihgt:100%;
    object-fit:scale-down;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.product-container{
    position:absolute;
    top:0;
    right:0;
    left:0;
    bottom:44px;
    overflow-y:scroll;
}

.product-item-placeholder{
    text-align:center;
    background-color:$line-color;
}
.product-item-placeholder i{
    font-size:200px;
    color:$background-color;
}

.product-detail,.product-content{
    margin-bottom:10px;
    padding:10px;
    background-color:#fff;
}
.product-detail-title{
    font-size:$normal-font-size;
    font-weight:500;
    color:$main-font-color;
    line-height:$normal-font-height;
    max-height:$normal-font-height * 2;
    overflow:hidden;
}
.product-detail-desc{
    font-size:$small-font-size;
    color:$other-font-color;
    line-height:$small-font-height;
    max-height:$small-font-height * 2;
    overflow:hidden;
}

.product-detail-price__price{
    font-size:$small-font-size;
    @include price-color(1);
}
.product-detail-price__price strong{
    font-size:$large-font-size;
}
.product-detail-price__oriprice{
    margin-left:10px;
    font-size:$small-font-size;
    color:$disabled-color;
    text-decoration:line-through;
}

.product-detail-sale{
    margin:5px 0;
    font-size:$small-font-size;
    color:$other-font-color;
}

.product-footer{
    position:fixed;
    bottom:0;
    left:0;
    right:0;
    display:flex;
    height:44px;
    background-color:#fff;
}
.product-footer>div{
    vertical-align:middle;
}
.product-footer-icon{
    flex:1;
    text-align:center;
    border-top:1px solid $line-color;
    font-size:$small-font-size;
    color:$sub-font-color;
}
.product-footer-button{
    flex:2;
}

.product-content-title{
    font-size:$middle-font-size;
    line-height:30px;
    border-bottom:1px solid $line-color;
}

.product-image-error{
    height:98px;
    border-radius:5px;
    background-color:$background-color;
}
.product-image-error i{
    font-size:30px;
    color:$line-color;
}

.pop-item-image{
    display:inline-block;
    vertical-align:middle;
}
.pop-item-image{
    border:1px solid $line-color;
    border-radius:5px;
}
.pop-item-title{
    display:inline-block;
    vertical-align:middle;
    font-size:$normal-font-size;
    color:$main-font-color;
    margin-left:10px;
}

.pop-item-content{
    margin:10px 0;
    padding:20px 0 10px;
    border-top:1px solid $line-color;
}
.pop-item-content__title{
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.pop-item-content__content{
    margin:10px 0;
}
.pop-item-content__content>div{
    display:inline-block;
    margin-bottom:5px;
}
.pop-item-content-quantity>div{
    display:inline-block;
    vertical-align:middle;
}
.pop-item-content-quantity>div:first-child{
    width:80px;
}
.pop-item-content-quantity>div:last-child{
    width:calc(100% - 80px);
    text-align:right;
}

.pop-item-title__price{
    margin-top:5px;
    font-size:$small-font-size;
    @include price-color(1);
}
.pop-item-title__price strong{
    font-size:$normal-font-size;
}

.product-content-content{
    min-height:20px;
}

.item-soldout{
    position:absolute;
    width:200px;
    height:200px;
    margin-left:-100px;
    left:50%;
    line-height:200px;
    text-align:center;
    background-color:rgba(0,0,0,0.4);
    color:#fff;
    font-size:$normal-font-size;
    z-index:2;
    border-radius:50%;
}
.item-soldout>div{
    width:170px;
    height:170px;
    margin-left:15px;
    margin-top:15px;
    line-height:170px;
    background-color:rgba(0,0,0,0.6);
    border-radius:50%;
}
</style>