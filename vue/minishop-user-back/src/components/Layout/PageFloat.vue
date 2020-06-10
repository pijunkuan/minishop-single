<template>
<div>
    <transition name="float">
        <div v-show="isShow" class="page-float-content">
            <div class="content-header">
                <span></span>
                <span @click="isShow = false"><i class="iconfont iconguanbi"></i></span>
            </div>
        </div>
    </transition>
    <div class="page-float-button" @click="isShow = true">
        <i class="iconfont iconbangzhu"></i>
        <div>使用帮助</div>
    </div>
</div>
</template>

<script>
export default{
    data(){
        return{
            isShow:false
        }
    }
}
</script>

<style lang="scss" scoped>
.float-enter,.float-leave-to{
    opacity:0;
}
.float-enter-active,.float-leave-active{
    transition:all .2s ease-in;
}
.page-float-content{
    position:absolute;
    background-color:rgba(0,0,0,0.7);
    top:0;
    right:0;
    left:0;
    bottom:0;
    z-index:5;
}
.content-header{
    position:absolute;
    top:20px;
    right:10px;
    line-height:40px;
    text-align:right;
    padding:5px 20px;
}
.content-header i{
    font-size:28px;
    color:#fff;
    padding:5px;
    background-color:rgba(0,0,0,0.7);
    border-radius:50%;
    cursor:pointer;
}
.page-float-button{
    position:absolute;
    width:50px;
    height:50px;
    right:0;
    bottom:50px;
    background-color:rgba(0,0,0,0.3);
    cursor:pointer;
}
.page-float-button:hover{
    background-color:rgba(0,0,0,0.6);
    width:110px;
    transition:width .2s ease-in;
}
.page-float-button i{
    color:#fff;
    font-size:34px;
    position:relative;
    top:6px;
    margin-left:8px;
}
.page-float-button div{
    position:absolute;
    right:5px;
    width:60px;
    color:#fff;
    display:inline-block;
    opacity:0;
    bottom:15px;
}
.page-float-button:hover div{
    opacity:1;
}
</style>