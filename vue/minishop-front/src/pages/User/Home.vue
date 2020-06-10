<template>
<div>
    <page-loading :loading="loading"></page-loading>
    <div class="user-home-header">
        <div class="user-home-header-icon" @click="toLogout"><i class="iconfont icontuichu1"></i></div>
        <div>
            <shop-image :src="$store.getters.avatar" :width="80" type="fit" circled>
                <div slot="placeholder">
                    <i class="iconfont iconiconzhucetouxiang"></i>
                </div>
                <div slot="error">
                    <i class="iconfont iconiconzhucetouxiang"></i>
                </div>
            </shop-image>
        </div>
        <div>
            <div>{{ $store.getters.username ? $store.getters.username : '-' }}</div>
            <div class="user-home-level">
                <span><i class="iconfont iconhuiyuan"></i></span>
                {{ level.name }}
            </div>
        </div>
    </div>
    <div class="user-home-wallet">
        <div @click="toWallet"><i class="iconfont iconyinhangyouhui"></i>余额 | <span>¥ <strong>{{ $store.getters.balance ? $store.getters.balance : '0' }}</strong></span></div>
    </div>
    <div class="user-home-order">
        <div class="user-home-order-title">我的订单</div>
        <div class="user-home-order-item" @click="toOrder('')">
            <div><shop-icon name="dingdan" size="medium"></shop-icon></div>
            <div>全部</div>
        </div>
        <div class="user-home-order-item" @click="toOrder('pending')">
            <div><shop-icon name="daifukuan" size="medium"></shop-icon></div>
            <div>待付款</div>
        </div>
        <div class="user-home-order-item" @click="toOrder('processing')">
            <div><shop-icon name="daifahuo" size="medium"></shop-icon></div>
            <div>待发货</div>
        </div>
        <div class="user-home-order-item" @click="toOrder('sent')">
            <div><shop-icon name="daishouhuo" size="medium"></shop-icon></div>
            <div>待收货</div>
        </div>
        <div class="user-home-order-item" @click="toOrder('refunding')">
            <div><shop-icon name="shouhou" size="medium"></shop-icon></div>
            <div>退换货</div>
        </div>
    </div>
    <div class="user-home-other">
        <div @click="toAddress">
            <div><shop-icon name="ditu1" size="medium"></shop-icon></div>
            <div>地址簿</div>
        </div>
        <!-- <div>
            <div><shop-icon name="youhuiquan" size="medium"></shop-icon></div>
            <div>优惠券</div>
        </div> -->
    </div>
</div>
</template>

<script>
import { Toast } from 'mint-ui'
export default{
    data(){
        return{
            loading:false,
            level:{name:'普通会员',type:'normal'},
            levels:{
                normal:'',
                higher:''
            }
        }
    },
    created(){
        this.loading = true
        this.$store.dispatch('getUserInfo').then(()=>{
            this.$store.dispatch('getBalance').then(()=>{
                this.loading = false
            }).catch(e=>{
                if(e.response.status === 401){
                    Toast({
                        message:'登录超时',
                        duration:1000
                    })
                    this.$store.dispatch('logout')
                    setTimeout(()=>{
                        this.$router.push({name:'Login'})
                    },1000)
                }
            })
        }).catch(err=>{
            if(err.response.status === 401){
                Toast({
                    message:'登录超时',
                    duration:1000
                })
                this.$store.dispatch('logout')
                setTimeout(()=>{
                    this.$router.push({name:'Login'})
                },1000)
            }
        })
    },
    methods:{
        toLogout(){
            this.$store.dispatch('logout')
            Toast({
                message:'正在退出登录...',
                duration:1000
            })
            setTimeout(()=>{
                this.$router.push({name:'Home'})
            },1000)
        },
        toWallet(){
            this.$router.push({name:'UserWallet'})
        },
        toOrder(status){
            if(status) this.$router.push({name:'UserOrder',params:{status:status}})
                else this.$router.push({name:'UserOrder'})
        },
        toAddress(){
            this.$router.push({name:'UserAddress'})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.user-home-header{
    height:80px;
    padding:35px 20px 50px;
    @include block-background(1);
}
.user-home-header .shop-image i{
    font-size:45px;
    @include base-color(1);
}
.user-home-header>div{
    display:inline-block;
    vertical-align:middle;
    font-size:$normal-font-size;
    color:#fff;
}
.user-home-header>div:last-child{
    margin-left:10px;
    width:calc(100% - 90px);
}
.user-home-level{
    font-size:$middle-font-size;
    margin-top:5px;
}
.user-home-level span{
    background-color:#fff;
    text-align:center;
    border-radius:50%;
}
.user-home-level i{
    position:relative;
    left:1px;
    top:1px;
    margin-right:3px;
    font-size:18px;
    @include base-color(1);
    font-weight:500;
}
.user-home-wallet{
    position:absolute;
    left:50%;
    margin-left:-100px;
    margin-top:-28px;
    width:160px;
    padding:15px 20px;
    background-color:#fff;
    border-radius:25px;
    box-shadow:0 0 5px 3px rgba(0,0,0,0.1);
    text-align:center;
    font-size:$normal-font-size;
    color:$sub-font-color;
}
.user-home-wallet i{
    position:relative;
    top:-1px;
    font-size:20px;
    vertical-align:middle;
    margin-right:5px;
}
.user-home-wallet span{
    color:$main-font-color;
}

.user-home-order{
    padding:30px 0 15px;
    background-color:#fff;
}
.user-home-order .user-home-order-title{
    padding:10px 0;
    margin:0 10px 10px;
    border-bottom:1px solid $line-color;
    font-size:$middle-font-size;
    font-weight:500;
    color:$main-font-color;
}
.user-home-order .user-home-order-item{
    display:inline-block;
    vertical-align:middle;
    width:20%;
    text-align:center;
    font-size:$small-font-size;
    color:$sub-font-color;
}

.user-home-other{
    margin-top:10px;
    background-color:#fff;
}
.user-home-other>div{
    display:inline-block;
    padding:15px 0;
    width:25%;
    text-align:center;
    color:$sub-font-color;
    font-size:$small-font-size;
}

.user-home-header-icon{
    position:absolute;
    right:15px;
}
.user-home-header-icon i{
    font-size:22px;
}
</style>