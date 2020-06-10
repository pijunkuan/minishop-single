<template>
<div>
    <page-loading :loading="loading"></page-loading>
    <div class="checksuc-header">
        订单提交成功
    </div>
    <div class="checksuc-amount">
        <div>应付金额</div>
        <div>¥ <strong>{{ order.total_amount ? order.total_amount : '-' }}</strong></div>
    </div>
    <div class="checksuc-payment">
        <div>支付方式</div>
        <div>
            <div @click="changePay">{{ payment.code === 'wallet' ? (payment.title + ' 余额：¥ ' + $store.getters.balance) : payment.title }}<span class="checksuc-payment-select">></span></div>
            <div class="checksuc-button">
                <div><shop-button size="small" type="border" @click="toOrders">稍后支付</shop-button></div>
                <div><shop-button size="small" @click="toPay" :disabled="btnLoading">立即支付</shop-button></div>
            </div>
        </div>
    </div>
    <div class="checksuc-content">
        <div class="checksuc-content-header">订单详情</div>
        <div class="checksuc-content-content">
            <div>订单号</div>
            <div>{{ order.order_no }}</div>
        </div>
        <div class="checksuc-content-content">
            <div>收件地址</div>
            <div>
                <div>{{ order.address.name }}</div>
                <div>{{ order.address.mobile }}</div>
                <div>{{ order.address.address }}</div>
            </div>
        </div>
        <div v-for="(item,index) in order.items" :key="index" class="checksuc-content-item">
            <div>
                <shop-image type="fit" :src="item.src" :width="60" rounded>
                    <div slot="error" class="checksuc-image-placeholder"></div>
                    <div slot="placeholder" class="checksuc-image-placeholder"></div>
                </shop-image>
            </div>
            <div>
                <div class="checksuc-content-item__title">{{ item.product_title }}</div>
                <div class="checksuc-content-item__subtitle">{{ item.variant_title }}</div>
                <div class="checksuc-content-item__price">¥ <strong>{{ item.price }}</strong></div>
                <div class="checksuc-content-item__amount">x {{ item.quantity }}</div>
            </div>
        </div>
    </div>
    <shop-popup size="medium" :show="payShow" @close="closePay">
        <div slot="title">选择支付方式</div>
        <div slot="content">
            <mt-picker
                ref="pays"
                :slots="slots">
            </mt-picker>
        </div>
        <div slot="button" class="pay-popup-button">
            <div><shop-button type="border" :rounded="false" @click="closePay">关闭</shop-button></div>
            <div><shop-button :rounded="false" @click="confirmChange">确定</shop-button></div>
        </div>
    </shop-popup>
</div>
</template>

<script>
import { get_order, create_pay } from '@/api/order'
import { get_payments } from '@/api/wallet'
import { Toast } from 'mint-ui'
export default{
    data(){
        return{
            loading:false,
            payments:[],
            payment:{},
            order:{
                order_no:'',
                address:{},
                items:[],
                total_amount:0
            },
            payShow:false,
            slots:[
                {
                    flex:1,
                    values:[],
                    textAlign:'center'
                }
            ],
            btnLoading:false
        }
    },
    created(){
        this.getOrder()
        this.$store.dispatch('getBalance')
    },
    methods:{
        getOrder(){
            this.loading = true
            this.order.order_no = this.$route.query.no
            get_order(this.$route.query.no).then(r=>{
                if(r.data.body.status !== 'pending'){
                    Toast({
                        message:'订单' + (r.data.body.status === 'processing' ? '已付款' : ((r.data.body.status === 'closed' || r.data.body.status === 'cancel') ? '已关闭' : '有误')) + '，将跳转至订单列表',
                        duration:2000
                    })
                    setTimeout(()=>{
                        this.loading = false
                        this.$router.push({name:'UserOrder'})
                    },2000)
                }else{
                    get_payments().then(res=>{
                        this.payments = res.data.body
                        this.payment = this.payments[0]
                        this.order.address = r.data.body.address
                        this.order.items = r.data.body.items
                        this.order.total_amount = r.data.body.amount
                        this.loading = false
                    }).catch(()=>{
                        this.loading = false
                    })
                }
            }).catch(()=>{
                this.loading = false
            })
        },
        changePay(){
            this.payShow = true
            this.payments.map(v=>{
                this.slots[0].values.push(v.title)
            })
        },
        closePay(){
            this.payShow = false
            this.slots[0].values = []
        },
        confirmChange(){
            let _payment = this.$refs.pays.values[0]
            this.payments.map(v=>{
                if(v.title === _payment) this.payment = v
            })
            this.payShow = false
            this.slots[0].values = []
        },
        toOrders(){
            this.$router.push({name:'UserOrder'})
        },
        toPay(){
            if(this.payment.code === 'wallet' && this.order.total_amount > this.$store.getters.balance){
                Toast('余额不足')
                return
            }
            this.btnLoading = true
            this.loading = true
            create_pay(this.$route.query.no,this.payment.code).then(r=>{
                if(r.data.body.status === 'success'){
                    this.$router.push({name:'Checksuc'})
                }else{
                    location.href = r.data.body.pay_url
                }
            }).catch(()=>{
                this.btnLoading = false
                this.loading = false
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.checksuc-header{
    padding:20px 0;
    text-align:center;
    @include base-background(1);
    color:#fff;
    font-size:$large-font-size;
}
.checksuc-amount{
    background-color:#fff;
    padding:10px;
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.checksuc-payment{
    margin-bottom:10px;
    padding:0 10px 15px;
    background-color:#fff;
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.checksuc-payment>div,.checksuc-amount>div{
    display:inline-block;
    vertical-align:top;
}
.checksuc-payment>div:first-child,.checksuc-amount>div:first-child{
    width:80px;
    line-height:30px;
}
.checksuc-payment>div:last-child,.checksuc-amount>div:last-child{
    width:calc(100% - 80px);
    color:$main-font-color;
    line-height:30px;
}
.checksuc-amount>div:last-child{
    @include price-color(1);
}
.checksuc-amount>div:last-child strong{
    font-size:$large-font-size;
}
.checksuc-payment .checksuc-payment-select{
    margin-left:20px;
    color:$other-font-color;
}

.checksuc-button{
    margin-top:10px;
}
.checksuc-button>div{
    display:inline-block;
    text-align:center;
    margin-right:10px;
}
.checksuc-button button{
    border-radius:25px!important;
}

.checksuc-content{
    padding:10px;
    background-color:#fff;
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.checksuc-content-header{
    font-weight:500;
    line-height:30px;
    border-bottom:1px solid $line-color;
    margin-bottom:5px;
}
.checksuc-content-content>div{
    display:inline-block;
    padding:5px 0;
    vertical-align:top;
}
.checksuc-content-content>div:first-child{
    width:75px;
}
.checksuc-content-content>div:last-child{
    width:calc(100% - 75px);
}

.checksuc-content-item{
    padding:10px 0;
    margin-top:10px;
    border-top:1px solid $line-color;
}
.checksuc-content-item>div{
    display:inline-block;
    vertical-align:middle;
}

.checksuc-content-item>div:first-child{
    width:60px;
    margin-right:10px;
}
.checksuc-content-item>div:last-child{
    width:calc(100% - 70px);
}
.checksuc-image-placeholder{
    height:60px;
    width:60px;
    border-radius:5px;
    background-color:$line-color;
}

.checksuc-content-item__title{
    color:$main-font-color;
    line-height:$middle-font-height;
    max-height:$middle-font-height * 2;
    overflow:hidden;
}
.checksuc-content-item__subtitle{
    line-height:$middle-font-height;
    height:$middle-font-height;
    overflow:hidden;
}
.checksuc-content-item__price{
    @include price-color(1);
}
.checksuc-content-item__price strong{
    font-size:$normal-font-size;
}
.checksuc-content-item__amount{
    text-align:right;
}

.pay-popup-button>div{
    display:inline-block;
    width:50%;
}
</style>