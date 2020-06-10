<template>
<transition name="loading">
    <div v-show="loading">
        <div class="loading-bar">
            <div class="loading-move-block">
                <i class="iconfont iconjiazai loading-move-block__light"></i>
            </div>
            <div class="loading-bar-text">
                <slot v-if="$slots.default"></slot>
                <span v-else>加载中</span>
            </div>
        </div>
        <div class="loading-cover"></div>
    </div>
</transition>
</template>

<script>
export default{
    props:{
        loading:Boolean
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.loading-enter-active,.loading-leave-active{
    transition:opacity .2s ease-in-out;
}
.loading-enter,.loading-leave-to{
    opacity:.2;
}

.loading-bar{
    z-index:3001;
    position:fixed;
    top:50%;
    left:50%;
    margin-top:-50px;
    margin-left:-50px;
    height:100px;
    width:100px;
    background-color:rgba(0,0,0,.65);
    border-radius:10px;
}
.loading-bar-text{
    font-size:$middle-font-size;
    text-align:center;
    color:$disabled-color;
}
.loading-move-block{
    width:40px;
    height:40px;
    margin-left:25px;
    margin-top:10px;
    padding:5px;
    text-align:center;
    -webkit-animation: rotating 3s linear infinite;
    -moz-animation: rotating 3s linear infinite;
    -ms-animation: rotating 3s linear infinite;
    -o-animation: rotating 3s linear infinite;
    animation: rotating 3s linear infinite;
}
.loading-move-block__light{
    font-size:40px;
    line-height:40px;
    border:none;
    color:$disabled-color;
}
@keyframes rotating
    {
    from
        {
        transform: rotate(0deg);
        -o-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        }
    to
        {
        transform: rotate(360deg);
        -o-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -webkit-transform: rotate(360deg);
        }
    }
@-webkit-keyframes rotating
    {
    from
        {
        transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        }
    to
        {
        transform: rotate(360deg);
        -webkit-transform: rotate(360deg);
        }
    }
.loading-cover{
    position:fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background-color:rgba(255,255,255,.5);
    z-index:3000;
}
</style>