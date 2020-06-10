<template>
<div class="shop-bottom">
    <div class="shop-bottom-item" @click="changeMenu(1)">
        <shop-icon 
            size="small"
            :name="defaultIndex === 1 ? 'shouye1' : 'shouye'">
        </shop-icon>
        <div class="shop-bottom-item__text">首页</div>
    </div>
    <div class="shop-bottom-item" @click="changeMenu(2)">
        <shop-icon 
            size="small"
            :name="defaultIndex === 2 ? 'leimupinleifenleileibie2' : 'leimupinleifenleileibie'">
        </shop-icon>
        <div class="shop-bottom-item__text">分类</div>
    </div>
    <div class="shop-bottom-item" @click="changeMenu(3)">
        <div class="shop-bottom-item-badge" v-if="$store.getters.cart">{{ $store.getters.cart >= 10 ? '9+' : $store.getters.cart }}</div>
        <shop-icon
            size="small"
            :name="defaultIndex === 3 ? '20gouwuchefill' : 'shiwu-gouwuche'">
        </shop-icon>
        <div class="shop-bottom-item__text">购物车</div>
    </div>
    <div class="shop-bottom-item" @click="changeMenu(4)">
        <shop-icon
            size="small"
            :name="defaultIndex === 4 ? 'shequ1' : 'shequ'">
        </shop-icon>
        <div class="shop-bottom-item__text">我的</div>
    </div>
</div>
</template>

<script>
import { getToken } from '@/utils/auth'
import { Toast } from 'mint-ui'
export default{
    props:{
        defaultIndex:Number
    },
    created(){
        if(this.$store.getters.token) this.$store.dispatch('getCart')
    },
    methods:{
        changeMenu(index){
            if(index === 1 && this.$route.name !== 'Home') this.$router.push({name:'Home'})
            if(index === 2 && this.$route.name !== 'Types') this.$router.push({name:'Types'})
            if(index === 3 && this.$route.name !== 'Cart') {
                if(getToken()) this.$router.push({name:'Cart'})
                    else Toast('请先登录')
            }
            if(index === 4){
                if(this.$store.getters.token && this.$route.name !== 'UserHome') this.$router.push({name:'UserHome'})
                    else if(!this.$store.getters.token && this.$route.name !== 'Login') this.$router.push({name:'Login',query:{from:this.$route.name}})
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-bottom{
    height:65px;
    border-top:1px solid $line-color;
    background-color:#fff;
}
.shop-bottom-item{
    display:inline-block;
    width:42px;
    height:59px;
    margin:0 calc((25% - 42px) / 2);
    padding-top:3px;
    vertical-align:middle;
    text-align:center;
}
.shop-bottom-item .shop-icon{
    margin-top:5px;
}
.shop-bottom-item__text{
    font-size:$middle-font-size;
    color:$main-font-color;
}
.shop-bottom-item-badge{
    position:absolute;
    margin-left:28px;
    width:24px;
    height:18px;
    line-height:18px;
    font-size:$small-font-size;
    color:#fff;
    border-radius:25px;
    @include sale-background(1);
}
</style>