<template>
<div>
    <div class="payment-set-block" style="width:calc(100% - 214px);margin-right:10px">
        <div class="payment-block-title">我的收款方式</div>
        <div v-loading="loading" style="min-height:360px">
            <div v-for="(pay,index) in user_payments" :key="index" class="payment-items my-payment-items">
                <div>
                    <div style="width:40px"><i class="iconfont iconyinhangyouhui"></i></div>
                    <div style="width:calc(100% - 90px)">
                        {{ pay.title }}
                        <span class="payment-status" :class="{'is-active':pay.status !== '使用中'}">{{ pay.status }}</span>
                    </div>
                    <div style="width:50px"><el-button size="mini" type="primary" plain @click="endPay(pay)">停用</el-button></div>
                </div>
                <transition name="setup">
                    <div v-show="pay.isSet"></div>
                </transition>
            </div>
        </div>
    </div>
    <div class="payment-set-block" style="width:200px">
        <div class="payment-block-title">全部收款方式</div>
        <div v-loading="sideLoading" style="min-height:360px">
            <div v-for="(pay,index) in payments" :key="index" class="payment-items">
                <div class="payment-title">{{ pay.title }}</div>
                <div class="payment-desc">{{ pay.des }}</div>
                <div class="payment-desc">{{ pay.need_setting ? '需配置' : '无需配置' }}</div>
                <div class="payment-button"><el-button type="success" size="mini" @click="startPay(pay)" :disabled="pay.hasUsed">启用</el-button></div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { get_shop_payments } from '@/api/shop'
import { get_payments, start_payment, stop_payment } from '@/api/payment'
export default{
    data(){
        return{
            loading:false,
            sideLoading:false,
            user_payments:[],
            payments:[]
        }
    },
    created(){
        this.getPayments()
    },
    methods:{
        getPayments(){
            this.loading = true
            this.sideLoading = true
            get_payments().then(r=>{
                this.user_payments = r.data.body
                this.user_payments.map(v=>{
                    v['isSet'] = false
                })
                get_shop_payments().then(r=>{
                    this.payments = r.data.body
                    this.payments.map(v=>{
                        if(JSON.stringify(this.user_payments).indexOf(v.code) !== -1) v['hasUsed'] = true
                            else v['hasUsed'] = false
                    })
                    this.sideLoading = false
                    this.loading = false
                }).catch(()=>{
                    this.sideLoading = false
                    this.loading = false
                })
            }).catch(()=>{
                this.loading = false
                this.sideLoading = false
            })
        },
        endPay(pay){
            stop_payment(pay.code).then(()=>{
                this.getPayments()
            }).catch(e=>{
                this.$message.error(e.response.data.message)
            })
        },
        startPay(pay){
            start_payment(pay.code).then(()=>{
                this.getPayments()
            }).catch(e=>{
                this.$message.error(e.response.data.message)
            })
        }
    }
}
</script>

<style scoped lang="scss">
@import '@/assets/style/base.scss';
.payment-set-block{
    display:inline-block;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:5px;
    vertical-align:top;
}
.payment-block-title{
    padding:10px;
    border-bottom:1px solid $line-color * 0.95;
    font-weight:500;
}
.payment-items{
    padding:10px;
    margin:0 10px;
    border-bottom:1px solid $line-color;
}
.payment-items i{
    color:$main-color;
    font-size:20px;
}
.my-payment-items>div:first-child>div{
    display:inline-block;
    vertical-align:middle;
}
.payment-status{
    font-size:12px;
}
.payment-status{
    background-color:$dark-back-sub;
    padding:2px 10px;
    border-radius:3px;
    color:#fff;
    margin-left:15px;
}
.payment-status.is-active{
    background-color:$error-color;
}

.payment-title{
    margin:10px 0 5px;
}
.payment-desc{
    color:$other-font-color;
    font-size:$small-font-size;
}
.payment-button{
    margin:10px 0;
    text-align:right;
}
</style>