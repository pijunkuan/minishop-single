<template>
<div>
    <div class="item-block-1__title">
        <div class="item-block-1__title-item title-item-left">{{ title }}</div>
        <div class="item-block-1__title-item title-item-middle">{{ subTitle }}</div>
        <div class="item-block-1__title-item title-item-right">
            <shop-button size="small" rounded style="width:50px">
                更多
            </shop-button>
        </div>
    </div>
    <div 
        class="item-block-1__placeholder" 
        :style="{height: itemHeight + 'px',lineHeight: itemHeight + 'px'}" 
        v-if="loading && items.length === 0">
        加载中...
    </div>
    <div 
        class="item-block-1__error" 
        :style="{height: itemHeight + 'px',lineHeight: itemHeight + 'px'}" 
        v-if="!loading && items.length === 0">
        暂无结果
    </div>
    <div class="item-block-1__content" v-if="itemAmount !== 0 && itemAmount <= 3" :style="{height: itemHeight + 'px'}">
        <div class="item-block-1-item" v-for="(item,index) in items" :key="index" :style="{width:itemWidth + 'px'}">
            <div>
                <shop-image :src="item.src" :width="itemWidth" type="fit"></shop-image>
            </div>
            <div class="item-block-1-item__title">{{ item.title }}</div>
            <div class="item-block-1-item__price">¥ <strong>{{ item.price }}</strong></div>
        </div>
    </div>
    <div class="item-block-1__content" v-if="itemAmount > 3" :style="{height: itemHeight * 2 + 'px'}">
        <div class="item-block-1-item" v-for="(item,index) in items" :key="index" :style="{width:itemWidth + 'px'}">
            <div>
                <shop-image :src="item.src" :width="itemWidth" type="fit"></shop-image>
            </div>
            <div class="item-block-1-item__title">{{ item.title }}</div>
            <div class="item-block-1-item__price">¥ <strong>{{ item.price }}</strong></div>
        </div>
    </div>
</div>
</template>

<script>
export default{
    props:{
        title:{
            type:String,
            default:'好物推荐'
        },
        subTitle:{
            type:String,
            default:'GOOD RECOMMENDATION'
        },
        items:Array,
        loading:Boolean
    },
    data(){
        return{
            itemWidth:0,
            itemHeight:0,
            itemAmount:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            let _width = window.innerWidth
            this.itemWidth = _width / 3 - 10
            this.itemHeight = this.itemWidth + 55
        })
        window.onresize = () => {
            let _width = window.innerWidth
            this.itemWidth = _width / 3 - 10
            this.itemHeight = this.itemWidth + 55
        }
    },
    watch:{
        items:{
            immediate:true,
            handler:function(){
                this.itemAmount = this.items.length
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.item-block-1__title{
    padding:10px;
    border-top-left-radius:12px;
    border-top-right-radius:12px;
    @include element-background(0.6);
}
.item-block-1__title-item{
    display:inline-block;
    vertical-align:middle;
    color:#fff;
    font-size:$normal-font-size;
}
.title-item-left{
    margin-right:5px;
}
.title-item-middle{
    font-size:$mini-font-size;
    @include element-color(3.7);
    width:160px;
    overflow:hidden;
}
.title-item-right{
    float:right;
}

.item-block-1__placeholder,.item-block-1__error{
    @include element-color(3);
    text-align:center;
    background-color:#fff;
    font-size:$normal-font-size;
}
.item-block-1__error{
    color:$disabled-color!important;
}

.item-block-1__content{
    padding:5px 0;
    background-color:#fff;
}
.item-block-1-item{
    display:inline-block;
    margin:0 5px;
    vertical-align:top;
}
.item-block-1-item:nth-child(4),.item-block-1-item:nth-child(5),.item-block-1-item:nth-child(6){
    margin-top:5px;
}
.item-block-1-item__title{
    font-size:$small-font-size;
    line-height:$small-font-height;
    max-height:$small-font-height * 2;
    overflow:hidden;
    color:$main-font-color;
}
.item-block-1-item__price{
    @include price-color(1);
    font-size:$small-font-size;
}
.item-block-1-item__price strong{
    font-size:$normal-font-size;
}
</style>