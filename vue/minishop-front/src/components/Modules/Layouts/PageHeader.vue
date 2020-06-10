<template>
<div class="shop-header">
    <div class="shop-header-left" @click="toReturn">
        <shop-icon name="shangyiye" size="small" type="square"></shop-icon>
    </div>
    <div class="shop-header-title">
        <slot name="title"></slot>
    </div>
    <div class="shop-header-right">
        <slot name="right"></slot>
    </div>
</div>
</template>

<script>
export default{
    methods:{
        toReturn(){
            if(this.$route.name === 'Cart' && this.$route.query.from !== undefined){
                this.$router.push({name:'Home'})
            }else if(this.$route.name === 'UserAddress' || this.$route.name === 'UserOrder' || this.$route.name === 'UserWallet'){
                this.$router.push({name:'UserHome'})
            }else if(this.$route.name === 'UserOrderDetail'){
                this.$router.push({name:'UserOrder'})
            }else{
                this.$router.go(-1)
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-header{
    height:49px;
    line-height:44px;
    @include base-background(1);
}
.shop-header-left,.shop-header-title,.shop-header-right{
    display:inline-block;
    vertical-align:middle;
}
.shop-header-right{
    width:30px;
}
.shop-header-title{
    width:calc(100% - 58px);
    text-align:center;
    color:#fff;
    font-size:$large-font-size;
    line-height:49px;
    overflow:hidden;
}
</style>