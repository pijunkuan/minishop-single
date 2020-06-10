<template>
<div>
    <div class="item-block-2__title">{{ title }}</div>
    <div class="item-block-2__placeholder" v-if="loading && items.length === 0">加载中...</div>
    <div class="item-block-2__error" v-if="!loading && items.length === 0">暂无结果</div>
    <div class="item-block-2__content" v-for="(item,index) in items" :key="index" @click="toProduct(item)">
        <div>
            <shop-image :src="item.img" :width="100" type="fit" circled>
                <div slot="error" class="item-block-2-image__error"></div>
            </shop-image>
        </div>
        <div>
            <div class="item-block-2__content-title">{{ item.product_title }}</div>
            <div v-if="item.sub_title"><span class="item-block-2__content-subtitle">{{ item.sub_title }}</span></div>
            <!-- <div class="item-block-2__content-hot">{{ item.sale }}人已购买</div> -->
            <div class="item-block-2__content-price">
                <span class="current-price">¥ <strong>{{ item.price }}</strong></span>
                <span v-if="item.ori_price" class="ori-price">¥ <strong>{{ item.ori_price }}</strong></span>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default{
    props:{
        title:{
            type:String,
            default:'精选商品'
        },
        subTitle:{
            type:String,
            default:'selected'
        },
        items:Array,
        loading:Boolean
    },
    methods:{
        toProduct(item){
            this.$router.push({name:'Product',params:{id:item.id}})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.item-block-2__title{
    padding:10px;
    border-top-left-radius:5px;
    border-top-right-radius:5px;
    border-bottom:1px solid $line-color;
    background-color:#fff;
    font-size:$normal-font-size;
    color:$main-font-color;
}
.item-block-2__content .shop-image{
    border:1px solid $line-color * 1.05;
    background-color:$line-color * 1.05;
    border-radius:50%;
}
.item-block-2-image__error{
    height:100px;
    width:100px;
    line-height:100px;
    border-radius:50%;
    border:1px solid $line-color * 1.05;
    background-color:$line-color * 1.05;
}
.item-block-2__placeholder,.item-block-2__error{
    background-color:#fff;
    height:390px;
    line-height:390px;
    text-align:center;
    font-size:$normal-font-size;
    @include element-color(3);
}
.item-block-2__error{
    color:$disabled-color!important;
}
.item-block-2__content{
    padding:10px;
    background-color:#fff;
    vertical-align:middle;
}
.item-block-2__content:after{
    position:absolute;
    content:'';
    left:0;
    width:calc(100% - 20px);
    margin:112px 10px 0;
    border-bottom:1px solid $line-color;
}
.item-block-2__content>div{
    display:inline-block;
    vertical-align:middle;
}
.item-block-2__content>div:last-child{
    width:calc(100% - 135px);
    margin-left:30px;
    padding:5px 0;
}
.item-block-2__content-title{
    font-size:$middle-font-size;
    line-height:$middle-font-height;
    max-height:$middle-font-height;
    overflow:hidden;
    word-break:break-all;
    color:$main-font-color;
    margin-bottom:5px;
}
.item-block-2__content-subtitle{
    padding:3px 5px;
    border-radius:25px;
    font-size:$mini-font-size;
    line-height:$mini-font-height;
    height:$mini-font-height;
    overflow:hidden;
    color:#fff;
    @include sale-background(1);
    opacity:0.8;
}
.item-block-2__content-hot{
    font-size:$mini-font-size;
    margin-top:10px;
    line-height:$mini-font-height;
    color:$other-font-color;
}
.item-block-2__content-price{
    margin-top:10px;
    font-size:$middle-font-size;
}
.item-block-2__content-price .current-price{
    @include price-color(1);
}
.item-block-2__content-price .current-price strong{
    font-size:$large-font-size;
}
.item-block-2__content-price .ori-price{
    color:$other-font-color;
    text-decoration:line-through;
    margin-left:10px;
}
</style>