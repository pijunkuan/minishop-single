<template>
<div :style="{height:height + 'px'}" class="infinite-list">
    <ul
        class="infinite-shop-list"
        :style="{height:height + 'px'}"
        style="min-height:calc(100% + 1px)"
        v-infinite-scroll="loadMore"
        infinite-scroll-disabled="loading"
        infinite-scroll-distance="10"
        infinite-scroll-immediate-check>
        <!-- 兼容ios13 -->
        <div style="min-height:calc(100% + 1px)">
        <!-- end -->
            <li v-for="(item,index) in items" :key="index" :style="{width:width + 'px', height:width + 70 + 'px'}" @click="toProduct(item)">
                <div>
                    <div 
                        v-if="item.stock * 1 === 0" 
                        class="infinite-shop-list__soldout"
                        :style="{height:width - 50 + 'px', width:width - 50 + 'px', lineHeight:width - 50 + 'px'}">
                        <div :style="{height:width - 70 + 'px', width:width - 70 + 'px', lineHeight:width - 70 + 'px'}">已抢光</div>
                    </div>
                    <shop-image :src="item.img" rounded type="fit" :width="width - 20">
                        <div class="product-image-error" :style="{height:width - 20 + 'px'}" slot="error"><i class="iconfont icontupian"></i></div>
                    </shop-image>
                </div>
                <div>
                    <div class="infinite-shop-content__title">{{ item.product_title }}</div>
                    <div>
                        <span class="infinite-shop-content__price">¥ <strong>{{ item.price ? item.price : '-' }}</strong></span>
                        <span v-if="item.oriPrice" class="infinite-shop-content__oriprice">¥ {{ item.oriPrice }}</span>
                    </div>
                    <!-- <div class="infinite-shop-content__sale">{{ item.sale ? item.sale : 0 }}人已购买</div> -->
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
            default:544
        },
        loading:Boolean,
        nomore:Boolean
    },
    data(){
        return{
            width:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.width = window.innerWidth / 2 - 10
        })
        window.onresize = () => {
            this.width = window.innerWidth / 2 - 10
        }
    },
    methods:{
        loadMore(){
            this.$emit('load')
        },
        toProduct(item){
            if(item.id === undefined) return
                else this.$router.push({name:'Product',params:{id:item.id}})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.infinite-shop-list__placeholder,.infinite-shop-list__error{
    height:30px;
    line-height:30px;
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
    display:inline-block;
    vertical-align:middle;
    margin:5px;
    overflow:hidden;
    background-color:#fff;
    border-radius:5px;
}
.infinite-shop-list li>div:first-child{
    padding:10px;
}
.infinite-shop-list li>div:last-child{
    padding:0 6px;
}
.infinite-shop-list li>div{
    position:relative;
}
.infinite-shop-content__title{
    font-size:$middle-font-size;
    line-height:$middle-font-height;
    max-height:$middle-font-height * 2;
    overflow:hidden;
    word-break:break-all;
    color:$main-font-color;
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
    text-align:right;
}

.product-image-error{
    border-radius:5px;
    background-color:$background-color;
}
.product-image-error i{
    font-size:30px;
    color:$line-color;
}

.infinite-shop-list__soldout{
    position:absolute;
    margin-top:15px;
    margin-left:15px;
    text-align:center;
    border-radius:50%;
    font-size:$small-font-size;
    color:#fff;
    background-color:rgba(0,0,0,0.4);
}
.infinite-shop-list__soldout>div{
    margin-top:10px;
    margin-left:10px;
    border-radius:50%;
    background-color:rgba(0,0,0,0.6);
}

.infinite-list{
    overflow-y:scroll;
    overflow-x:hidden;
    -webkit-overflow-scrolling: touch;
}
</style>