<template>
<div id="container">
    <div class="type-search">
        <div class="search-bar" :class="{'is-searching':visible}">
            <form action="" v-on:submit.prevent="">
                <shop-input
                    ref="searchbar"
                    :placeholder="'大家都在搜 ' + placeholder"
                    type="search"
                    size="small" 
                    v-model="fetchQuery.name"
                    @focus="handleFocus"
                    @keyup.enter.native="getSearch">
                    <shop-icon slot="prepend" size="mini" name="sousuo"></shop-icon>
                </shop-input>
            </form>
        </div>
        <div v-if="visible" class="search-btn">
            <span @click="handelCancel">取消</span>
        </div>
    </div>
    <div class="type-menu">
        <div class="type-menu-bar">
            <div 
                class="type-menu-bar__item" 
                :class="{'is-active':activeIndex === 0}"
                @click="changeAll">
                <div style="width:67px;overflow:hidden">全部商品</div>
            </div>
            <div 
                class="type-menu-bar__item" 
                :class="{'is-active':activeIndex === ('m' + index)}"
                v-for="(menu,index) in menus" :key="index"
                @click="changeMenu(menu,index)">
                <div style="width:67px;overflow:hidden">{{ menu.category_title }}</div>
            </div>
        </div>
        <div class="type-content" id="content">
            <infinite-column
                :loading="loading"
                :nomore="noresult"
                :items="items"
                :width="width"
                :height="height"
                @load="getItems"
                @cart="addCart">
                <div slot="button">
                    <div class="add-cart-button"><i class="iconfont iconjiahao"></i></div>
                </div>
            </infinite-column>
        </div>
    </div>
    <shop-popup
        :show="popShow"
        size="large"
        @close="closePop">
        <div slot="content">
            <div style="margin-bottom:20px">
                <shop-image class="pop-item-image" :src="currentItem.images[0]" :width="100" rounded type="stretch">
                    <div class="product-image-error" slot="error"><i class="iconfont icontupian"></i></div>
                </shop-image>
                <div class="pop-item-title">
                    <div>{{ popLoading ? '-' : currentItem.product.product_title }}</div>
                    <div class="pop-item-title__price">¥ <strong>{{ popLoading ? '-' : selectVariant.price }}</strong></div>
                </div>
            </div>
            <div class="pop-item-content">
                <div class="pop-item-content__title">选择规格</div>
                <div class="pop-item-content__content" v-if="popLoading"></div>
                <div class="pop-item-content__content" v-else>
                    <div v-for="(variable,index) in currentItem.variants" :key="index">
                        <shop-radio 
                            type="button"
                            bordered rounded
                            size="medium"
                            v-model="selectId" 
                            :label="variable.id" 
                            @change="changeVariant(variable)">
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
                        :max="selectVariant.stock"
                        :min="1"
                        :disabled="!selectVariant.stock || popLoading"
                        @change="getNum">
                    </shop-input-num>
                </div>
            </div>
        </div>
        <div slot="button">
            <shop-button 
                :rounded="false" 
                :loading="btnLoading"
                :disabled="!selectVariant.stock || popLoading" 
                @click="confirmAdd">
                加入购物车
            </shop-button>
        </div>
    </shop-popup>
</div>
</template>

<script>
import { get_types, get_products, get_product } from '@/api/products'
import { add_cart } from '@/api/cart'
import { Toast } from 'mint-ui'
import InfiniteColumn from '@/components/Modules/ProductList/InfiniteColumn'
export default{
    components:{
        InfiniteColumn
    },
    data(){
        return{
            fetchQuery:{
                name:'',
                page:0,
                pageSize:15,
                categories:[],
                Q4S:null
            },
            placeholder:'',
            visible:false,
            menus:[],
            items:[],
            activeIndex:0,
            width:0,
            height:0,
            loading:false,
            noresult:false,
            popShow:false,
            popLoading:false,
            currentItem:{
                product:{},
                images:[],
                variants:[]
            },
            selectVariant:{},
            selectId:null,
            selectNum:1,
            btnLoading:false
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.width = document.getElementById('content').offsetWidth
            this.height = document.getElementById('container').offsetHeight - 50
        })
        window.onresize = () => {
            this.width = document.getElementById('content').offsetWidth
            this.height = document.getElementById('container').offsetHeight - 50
        }
    },
    created(){
        this.getMenus()
    },
    beforeRouteLeave(to,from,next){
        if(to.name === 'Product') from.meta.keepAlive = true
            else from.meta.keepAlive = false
        next()
    },
    methods:{
        getMenus(){
            get_types().then(r=>{
                this.menus = r.data.body
                let _data = []
                this.menus.map(v=>{
                    _data.push(v.id)
                })
                if(this.$route.params.id !== undefined){
                    this.fetchQuery.categories = []
                    this.fetchQuery.categories.push(this.$route.params.id)
                    this.activeIndex = 'm' + _data.indexOf(this.$route.params.id)
                    this.items = []
                    this.noresult = false
                    this.loading = false
                    this.fetchQuery.page = 0
                    this.getItems()
                }
            })
        },
        changeAll(){
            this.activeIndex = 0
            this.items = []
            this.noresult = false
            this.loading = false
            this.fetchQuery.page = 0
            this.fetchQuery.categories = []
            this.getItems()
        },
        changeMenu(item,index){
            this.activeIndex = 'm' + index
            this.items = []
            this.noresult = false
            this.loading = false
            this.fetchQuery.page = 0
            this.fetchQuery.categories = []
            this.fetchQuery.categories.push(item.id)
            this.getItems()
        },
        getItems(){
            this.loading = true
            this.noresult = false
            let _data = []
            this.fetchQuery.page += 1
            get_products(this.fetchQuery).then(r=>{
                _data = r.data.body.data
                if(_data.length === 0){
                    this.loading = true
                    this.noresult = true
                }else{
                    this.items = this.items.concat(_data)
                    this.loading = false
                    this.noresult = false
                }
            }).catch(()=>{
                this.loading = true
                this.noresult = true
            })
        },
        addCart(val){
            this.popShow = true
            this.popLoading = true
            get_product(val.id).then(r=>{
                let _images = []
                if(r.data.body.images.length !== 0){
                    r.data.body.images.map(v=>{
                        _images.push(v.url)
                    })
                }
                this.currentItem = r.data.body
                this.currentItem.images = _images
                this.selectVariant = this.currentItem.variants[0]
                this.selectId = this.selectVariant.id
                this.popLoading = false
            }).catch(()=>{
                this.popLoading = false
            })
        },
        closePop(){
            this.popShow = false
            this.currentItem = {
                product:{},
                variants:[],
                images:[]
            }
            this.selectId = null
            this.selectVariant = {}
            this.selectNum = 1
        },
        confirmAdd(){
            this.btnLoading = true
            let _data = {
                variant_id:this.selectVariant.id,
                quantity:this.selectNum
            }
            add_cart(_data).then(()=>{
                Toast('成功加入购物车')
                this.btnLoading = false
                this.$store.dispatch('getCart')
                this.closePop()
            }).catch(e=>{
                if(e.response.status !== 401){
                    Toast(e.response.data.message)
                }else if(e.response.status === 401){
                    setTimeout(()=>{
                        this.$router.push({name:'Login',query:{from:this.$route.name}})
                    },1000)
                }
                this.btnLoading = false
                this.closePop()
            })
        },
        getSearch(){
            this.fetchQuery.page = 0
            this.fetchQuery.categories = []
            this.activeIndex = 0
            this.items = []
            this.getItems()
        },
        handleFocus(){
            this.visible = true
        },
        handelCancel(){
            this.$refs.searchbar.clear()
            this.search = ''
            this.visible = false
        },
        changeVariant(item){
            this.selectVariant = item
            this.selectId = item.id
            this.selectNum = 1
        },
        getNum(val){
            this.selectNum = val
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.type-search{
    padding:7px 6px;
    border-bottom:1px solid $line-color;
}
.search-bar{
    display:inline-block;
    width:100%;
}
.search-bar.is-searching{
    width:calc(100% - 40px);
}
.search-btn{
    display:inline-block;
    width:40px;
    font-size:14px;
    color:$other-font-color;
    text-align:center;
}

.type-menu{
    display:flex;
}
.type-menu-bar{
    width:70px;
    overflow-y:scroll;
}
.type-menu-bar__item{
    width:70px;
    height:40px;
    line-height:40px;
    border-bottom:1px solid $line-color;
}
.type-menu-bar__item div{
    font-size:$middle-font-size;
    height:40px;
    line-height:40px;
    overflow:hidden;
    text-align:center;
    flex:2;
}
.type-menu-bar__item.is-active{
    display:flex;
    background-color:#fff;
    @include base-color(1);
}
.type-menu-bar__item.is-active:before{
    content:'';
    position:relative;
    height:40px;
    width:3px;
    left:0;
    @include base-background(1);
}

.type-content{
    flex:2;
    background-color:#fff;
}

.add-cart-button{
    width:30px;
    height:30px;
    line-height:30px;
    text-align:center;
    border-radius:50%;
    color:#fff;
    box-shadow:0 0 5px 2px rgba(0,0,0,.1);
    @include element-background(1);
}
.add-cart-button i{
    font-size:20px;
}

.product-image-error{
    height:98px;
    border:1px solid $line-color;
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
    @include price-color(1);
    font-size:$small-font-size;
}

.pop-item-title__price strong{
    font-size:$normal-font-size;
}
</style>