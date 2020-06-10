<template>
<div>
    <div v-if="loading" class="item-block-3__placeholder">加载中...</div>
    <div v-if="!loading && !item" class="item-block-3__error">暂无结果</div>
    <shop-image v-if="!loading && item" :src="item.img" :width="imageWidth" :height="imageHeight" type="cover" @click="toPage"></shop-image>
    <div v-if="item" class="item-block-3__content" @click="toPage">
        <div class="item-block-3__content-title">
            <div>{{ item.title }}</div>
            <div>{{ item.sub_title }}</div>
        </div>
        <div class="item-block-3__content-price">
            <div>¥ <strong>{{ item.price }}</strong></div>
        </div>
    </div>
</div>
</template>

<script>
export default{
    props:{
        item:[Object,null],
        loading:Boolean
    },
    data(){
        return{
            imageWidth:0,
            imageHeight:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            let _width = window.innerWidth
            this.imageWidth = _width
            this.imageHeight = _width * 600 / 750
        })
        window.onresize = () => {
            let _width = window.innerWidth
            this.imageWidth = _width
            this.imageHeight = _width * 600 / 750
        }
    },
    methods:{
        toPage(){
            if(this.item.link === undefined || !this.item.link.link) return
            if(this.item.link.link === 'Product'){
                this.$router.push({name:this.item.link.link, params:{id:this.item.link.query}})
            }else if(this.item.link.link === 'Types'){
                this.$router.push({name:this.item.link.link, params:{id:this.item.link.query}})
            }else{
                this.$router.push({name:this.item.link.link})
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.item-block-3__placeholder,.item-block-3__error{
    height:260px;
    line-height:260px;
    background-color:#fff;
    font-size:$normal-font-size;
    text-align:center;
    @include element-color(3);
}
.item-block-3__error{
    color:$disabled-color!important;
}
.item-block-3__content{
    padding:10px;
    background-color:#fff;
}
.item-block-3__content>div{
    display:inline-block;
    vertical-align:bottom;
}
.item-block-3__content-title{
    width:calc(100% - 100px);
}
.item-block-3__content-title>div:first-child{
    font-size:$normal-font-size;
    line-height:$normal-font-height;
    height:$normal-font-height;
    overflow:hidden;
    font-weight:bolder;
    color:$main-font-color;
}
.item-block-3__content-title>div:last-child{
    font-size:$middle-font-size;
    line-height:$middle-font-height;
    height:$middle-font-height;
    overflow:hidden;
    color:$sub-font-color;
}

.item-block-3__content-price{
    width:100px;
    text-align:right;
    font-size:$small-font-size;
    @include price-color(1);
}
.item-block-3__content-price strong{
    font-size:$large-font-size;
}
</style>