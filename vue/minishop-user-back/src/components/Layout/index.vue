<template>
<div>
    <page-header></page-header>
    <div class="main-container" :style="{height:height + 'px'}">
        <page-side :isCollapse="$store.getters.sidebar" :height="height"></page-side>
        <div class="main-content" :style="{height:height + 'px'}" :class="{'is-collapse':$store.getters.sidebar}">
            <div class="main-menu-title">
                <span class="main-menu-title__icon"><i class="iconfont" :class="$route.meta.icon"></i></span>
                <span style="font-size:16px">{{ $route.meta.title }}</span>
            </div>
            <router-view />
        </div>
    </div>
    <!-- <page-float></page-float> -->
</div>
</template>

<script>
import PageHeader from './PageHeader'
import PageSide from './PageSide'
// import PageFloat from './PageFloat'
export default{
    components:{
        PageHeader,
        PageSide,
        // PageFloat
    },
    data(){
        return{
            height:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 51
        })
        window.onresize = ()=>{
            this.height = window.innerHeight - 51
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.main-container{
    width:100%;
}

.main-content{
    display:inline-block;
    vertical-align:top;
    width:calc(100% - 166px);
    min-width:794px;
    overflow:hidden;
    background-color:$light-back-main;
}
.main-content>div{
    margin:10px;
}
.main-content>div:last-child{
    overflow:auto;
    height:calc(100% - 61px);
}
.main-content>div:last-child::-webkit-scrollbar{
    display:none;
}
.main-menu-title{
    height:40px;
    margin:0!important;
    padding:0 10px;
    line-height:40px;
    border-bottom:1px solid $line-color * 0.9;
    background-color:#fff;
}
.main-content.is-collapse{
    width:calc(100% - 66px);
}

.main-content::-webkit-scrollbar{
    display:none;
}

@media (max-width:960px){
    .main-container{
        width:960px;
    }
}

.main-menu-title__icon{
    margin-right:10px;
}
.main-menu-title__icon i{
    padding:5px;
    color:#fff;
    background-color:$dark-back-sub;
    border-radius:50%;
}
</style>