<template>
<div class="web-header header-container">
    <div class="header-item header-item-side">
        <i style="margin-left:20px" class="iconfont iconleimupinleifenleileibie2" v-if="menuShow" @click="toggleMenu(false)"></i>
        <i style="margin-left:20px" class="iconfont iconleimupinleifenleileibie" v-else @click="toggleMenu(true)"></i>
        <div class="header-logo">
            <img v-if="logo && logo !== 'null'" :src="logo" />
            <span v-else>商城后台</span>
        </div>
    </div>
    <div class="header-item header-item-main">
        <div style="width:71px"><span class="header-item-main__level">{{ $store.getters.shop_level }}</span></div>
        <div>
            <div class="shop-enter-name" @click="toShop">{{ $store.getters.shop_name }}</div>
            <div class="shop-enter-btn" @click="toShop">进入商城</div>
        </div>
    </div>
    <div class="header-item header-item-side">
        <span @click="toCenter" style="cursor:pointer;position:relative;top:-1px;margin-right:20px">后台中心</span>
        <span @click="toLogout" style="cursor:pointer">
            <span style="position:relative;top:-1px;margin-right:10px">退出</span>
            <i class="iconfont icontuichu1"></i>
        </span>
    </div>
</div>
</template>

<script>
import { getLogo } from '@/utils/auth'
export default{
    data(){
        return{
            menuShow:false,
            logo:getLogo()
        }
    },
    methods:{
        toggleMenu(boolean){
            this.menuShow = !this.menuShow
            this.$store.dispatch('toggleSide',boolean)
            this.$emit('click')
        },
        toLogout(){
            this.$store.dispatch('logout').then(()=>{
                this.$message.success({
                    message:'已安全登出',
                    duration:1000
                })
                location.href = '//account.' + this.$store.getters.shop_host + '/login'
            })
        },
        toEdit(){
            if(this.$route.name !== 'UserEdit') this.$router.push({name:'UserEdit'})
        },
        toCenter(){
            location.href = '//account.' + this.$store.getters.shop_host
        },
        toShop(){
            window.open('//' + this.$store.getters.shop_url + '.' + this.$store.getters.shop_host, '_blank')
        }
    }
}
</script>

<style scoped lang="scss">
@import '@/assets/style/base.scss';
.header-logo{
    width:98px;
    font-size:20px;
}
.header-logo img{
    width:100%;
    height:100%;
    object-fit:scale-down;
    vertical-align:middle;
}
.header-container{
    line-height:50px;
    height:50px;
    margin:0;
}
.header-item{
    display:inline-block;
    vertical-align:middle;
    margin-top:-5px;
}
.header-item-side{
    width:100px;
    font-size:14px;
}
.header-item-side:first-child{
    width:160px;
    text-align:left;
}
.header-item-side i{
    font-size:24px;
    cursor:pointer;
    position:relative;
    top:3px;
    margin-right:10px;
}
.header-item-side:first-child:after{
    position:absolute;
    content:'';
    height:20px;
    left:165px;
    top:15px;
    border-right:1px solid $light-back-main;
}
.header-item-side:first-child>div{
    display:inline-block;
}
.header-item-side:last-child{
    width:160px;
    text-align:right;
}
.header-item-main{
    width:calc(100% - 345px);
    font-size:16px;
    text-align:left;
    padding-left:20px;
}
.header-item-main>div,.header-item-main>div>div{
    display:inline-block;
    vertical-align:middle;
}
.header-item-main__level{
    font-size:13px;
    background-color:$dark-back-main;
    color:$light-back-main * 0.9;
    padding:2px 8px;
    border-radius:3px;
}
.header-item-main>div:last-child{
    width:calc(100% - 91px);
    overflow:hidden;
}
.shop-enter-name{
    cursor:pointer;
    max-width:calc(100% - 90px);
}
.shop-enter-name:hover{
    color:#eee;
}
.shop-enter-btn{
    font-size:13px;
    margin-left:10px;
    color:$success-color * 1.1;
    width:80px;
    font-weight:500;
}
.shop-enter-btn:hover{
    color:$success-color * 1.2;
    cursor:pointer;
}
</style>
<style>
.el-submenu .el-menu-item{
    min-width:165px!important;
}
.is-collapse .el-submenu__title span,.is-collapse .el-menu-item span{
    display:none;
}
.el-submenu__title .iconfont,.el-menu-item .iconfont{
    font-size:20px;
    margin-right:6px;
}
.el-submenu__title .el-submenu__icon-arrow{
    right:5px;
    margin-top:-3px;
}
</style>