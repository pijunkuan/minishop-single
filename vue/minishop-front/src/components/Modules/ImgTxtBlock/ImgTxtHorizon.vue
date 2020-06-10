<template>
<div>
    <div class="text-block-1__title" v-if="title">{{ title }}</div>
    <div v-if="type === 'img-text' && loading" class="text-block-1__placeholder">加载中...</div>
    <div v-else-if="type === 'img-text' && !loading && items.length === 0" class="text-block-1__placeholder">暂无结果</div>
    <ul class="text-block-1__content" :style="{height:(type === 'totaltext' ? 20 : 80) + 'px'}" v-else>
        <li v-for="(item,index) in items" :key="index" :style="{marginLeft:itemMargin + 'px', marginRight:itemMargin + 'px'}">
            <shop-image v-if="item.src" rounded :src="item.src" :width="50" style="margin-bottom:10px"></shop-image>
            <div class="text-block-1__content-title">{{ item.title }}</div>
        </li>
    </ul>
</div>
</template>

<script>
export default{
    props:{
        title:String,
        items:Array,
        type:{
            type:String,
            default:'totaltext'
        },
        loading:Boolean
    },
    data(){
        return{
            itemMargin:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            let _width = window.innerWidth
            this.itemMargin = ((_width + 35) / 5 - 50) / 2
        })
        window.onresize = () => {
            let _width = window.innerWidth
            this.itemMargin = ((_width + 35) / 5 - 50) / 2
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.text-block-1__title{
    padding:10px;
    font-size:$normal-font-size;
    color:$main-font-color;
    background-color:#fff;
    border-bottom:1px solid $line-color;
}
.text-block-1__placeholder{
    height:110px;
    width:100%;
    line-height:110px;
    text-align:center;
    @include base-color(3);
    background-color:#fff;
}
.text-block-1__content{
    padding:15px 0;
    margin:0;
    white-space:nowrap;
    width:100%;
    padding-left:0;
    background-color:#fff;
    overflow-x:scroll;
    overflow-y:hidden;
}
.text-block-1__content::-webkit-scrollbar{
    display:none;
}
.text-block-1__content li{
    display:inline-table;
    width:50px;
    text-align:center;
    vertical-align:middle;
}
.text-block-1__content-title{
    font-size:$middle-font-size;
    color:$sub-font-color;
}
</style>