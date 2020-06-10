<template>
<div>
    <div class="user-wallet-header">
        <div>我的余额</div>
        <div>¥ <strong>{{ $store.getters.balance }}</strong></div>
    </div>
    <div class="user-wallet-button">
        <shop-button>我要充值</shop-button>
    </div>
</div>
</template>

<script>
import { Toast } from 'mint-ui'
export default{
    created(){
        this.getBalance()
    },
    methods:{
        getBalance(){
            this.$store.dispatch('getBalance').then(()=>{}).catch(e=>{
                if(e.response.status === 401){
                    this.$store.dispatch('logout')
                    Toast({message:'登录超时',duration:1000})
                    setTimeout(()=>{
                        this.$router.push({name:'Login',query:{from:this.$route.name}})
                    },1000)
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.user-wallet-header{
    padding:20px;
    background-color:#fff;
    font-size:$normal-font-size;
    color:$sub-font-color;
}
.user-wallet-header>div{
    display:inline-block;
    width:calc(50% - 10px);
    padding:0 5px;
}
.user-wallet-header>div:first-child{
    text-align:right;
}
.user-wallet-header>div:last-child{
    @include sale-color(1);
}

.user-wallet-button{
    margin:20px 20px 0;
}
</style>