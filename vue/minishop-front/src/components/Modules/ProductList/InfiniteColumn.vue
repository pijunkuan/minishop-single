<template>
<div>
    <ul
        class="infinite-shop-list"
        :style="{height:height - 1 + 'px'}"
        v-infinite-scroll="loadMore"
        infinite-scroll-disabled="loading"
        infinite-scroll-distance="10"
        infinite-scroll-immediate-check>
        <!-- 兼容ios13 -->
        <div style="min-height:calc(100% + 1px)">
        <!-- end -->
            <li v-for="(item,index) in items" :key="index" :style="{ width:width - 10 + 'px'}">
                <div @click="toItem(item)">
                    <div v-if="item.saletag" class="infinite-shop-list__saletag"><div>{{ item.saletag }}</div></div>
                    <div v-if="item.stock * 1 === 0" class="infinite-shop-list__soldout"><div>已抢光</div></div>
                    <shop-image :src="item.img" rounded type="fit" :width="90">
                        <div class="product-image-error" slot="error"><i class="iconfont icontupian"></i></div>
                    </shop-image>
                </div>
                <div :style="{width:width - 120 + 'px'}">
                    <div class="infinite-shop-content__title" @click="toItem(item)">{{ item.product_title }}</div>
                    <div v-if="item.subTitle" @click="toItem(item)"><span class="infinite-shop-content__subtitle">{{ item.subTitle }}</span></div>
                    <div @click="toItem(item)">
                        <span class="infinite-shop-content__price">¥ <strong>{{ item.price ? item.price : '-' }}</strong></span>
                        <span v-if="item.oriPrice" class="infinite-shop-content__oriprice">¥ {{ item.oriPrice }}</span>
                    </div>
                    <!-- <div class="infinite-shop-content__sale" @click="toItem(item)">{{ item.sale ? item.sale : 0 }}人已买</div> -->
                    <div class="infinite-shop-content__button" @click="addCart(item)"><slot name="button"></slot></div>
                </div>
            </li>
            <div
                v-if="!nomore"
                class="infinite-shop-list__placeholder">
                加载中...
            </div>
            <div 
                v-if="nomore"
                class="infinite-shop-list__error">
                没有更多了
            </div>
        </div>
    </ul>
</div>
</template>

<script>
export default{
    props:{
        items:Array,
        height:{
            type:Number,
            default:500
        },
        loading:Boolean,
        nomore:Boolean,
        width:Number
    },
    methods:{
        loadMore(){
            this.$emit('load')
        },
        addCart(item){
            this.$emit('cart',item)
        },
        toItem(item){
            if(item.id === undefined) return
                else this.$router.push({name:'Product',params:{id:item.id}})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.infinite-shop-list__placeholder,.infinite-shop-list__error{
    height:50px;
    line-height:50px;
    font-size:$small-font-size;
    text-align:center;
    color:$other-font-color;
}
.infinite-shop-list__error{
    text-align:center;
    color:$disabled-color;
}
.infinite-shop-list__error-icon{
    display:block!important;
    color:$disabled-color!important;
    position:absolute;
    left:50%;
    margin-left:-20px;
}
.infinite-shop-list{
    margin:0;
    padding:0;
    list-style:none;
    overflow:scroll;
    //兼容ios13
    -webkit-overflow-scrolling:touch;
}
.infinite-shop-list li{
    display:inline-table;
    vertical-align:middle;
    margin:0 5px;
    padding:5px 0;
    height:120px;
    overflow:hidden;
    background-color:#fff;
    border-bottom:1px solid $line-color;
}
.infinite-shop-list li>div{
    position:relative;
    display:inline-block;
    vertical-align:middle;
    margin:0 5px;
}
.infinite-shop-list li>div:first-child{
    padding:15px 0;
}

.infinite-shop-list__saletag{
    position:absolute;
    padding:2px 6px;
    margin-top:5px;
    margin-left:-3px;
    border-radius:3px;
    font-size:$mini-font-size;
    color:#fff;
    @include sale-background(1);
}
.infinite-shop-content__button{
    position:absolute;
    right:0;
    margin-top:-10px;
}
.infinite-shop-content__title{
    font-size:$middle-font-size;
    line-height:$middle-font-height;
    max-height:$middle-font-height * 2;
    overflow:hidden;
    word-break:break-all;
    color:$main-font-color;
}
.infinite-shop-content__subtitle{
    font-size:$small-font-size;
    color:$other-font-color;
}
.infinite-shop-content__price{
    font-size:$small-font-size;
    @include price-color(1);
}
.infinite-shop-content__price strong{
    font-size:$large-font-size;
}
.infinite-shop-content__oriprice{
    margin-left:5px;
    font-size:$small-font-size;
    color:$disabled-color;
    text-decoration:line-through;
}
.infinite-shop-content__sale{
    font-size:$small-font-size;
    color:$other-font-color;
}

.product-image-error{
    height:88px;
    border:1px solid $line-color;
    border-radius:5px;
    background-color:$background-color;
}
.product-image-error i{
    font-size:30px;
    color:$line-color;
}

.infinite-shop-list__soldout{
    position:absolute;
    width:70px;
    height:70px;
    margin-top:10px;
    margin-left:10px;
    line-height:70px;
    text-align:center;
    border-radius:50%;
    font-size:$small-font-size;
    color:#fff;
    background-color:rgba(0,0,0,0.4);
}
.infinite-shop-list__soldout>div{
    width:60px;
    height:60px;
    line-height:60px;
    margin-top:5px;
    margin-left:5px;
    border-radius:50%;
    background-color:rgba(0,0,0,0.6);
}
</style>