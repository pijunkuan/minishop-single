<template>
<div>
    <ul class="user-order-menubar">
        <li :class="{'is-active':fetchQuery.status === ''}" @click="changeStatus('')"><span>全部</span></li>
        <li :class="{'is-active':fetchQuery.status === 'pending'}" @click="changeStatus('pending')"><span>待付款</span></li>
        <li :class="{'is-active':fetchQuery.status === 'processing'}" @click="changeStatus('processing')"><span>待发货</span></li>
        <li :class="{'is-active':fetchQuery.status === 'sent'}" @click="changeStatus('sent')"><span>待收货</span></li>
        <li :class="{'is-active':fetchQuery.status === 'refunding'}" @click="changeStatus('refunding')"><span>退换货</span></li>
    </ul>
    <ul
        class="order-list"
        :style="{height:height - 1 + 'px'}"
        v-infinite-scroll="getOrders"
        infinite-scroll-disabled="loading"
        infinite-scroll-distance="10"
        infinite-scroll-immediate-check>
        <li v-for="(order,index) in orders" :key="index" class="order-list-item">
            <div class="order-list-item__title" @click="toCheck(order)">
                <div>{{ order.created_at }}</div>
                <div :class="{'is-disabled':disabledStatus.indexOf(order.status) !== -1}">{{ order.refund_status ? order.refund_status_value : order.status_value }}</div>
            </div>
            <div v-for="(item,index) in order.items" :key="'i' + index" class="order-list-item__item">
                <div>
                    <shop-image class="order-list-item__item-image" :src="item.img_url" type="fit" :width="60" rounded>
                        <div slot="error" class="order-item-placeholder"></div>
                        <div slot="placeholder" class="order-item-placeholder"></div>
                    </shop-image>
                </div>
                <div class="order-list-item__item-title">
                    <div>{{ item.product_title }}</div>
                    <div>{{ item.variant_title }}</div>
                </div>
                <div class="order-list-item__item-price">
                    <div>¥ <strong>{{ item.price }}</strong></div>
                    <div>x {{ item.quantity }}</div>
                </div>
            </div>
            <div class="order-list-item__bottom">
                <div>订单总额 ¥ <strong>{{ order.amount ? order.amount : '-' }}</strong></div>
                <div>
                    <shop-button size="small" type="border" v-if="canClose.indexOf(order.status) !== -1" @click="toPay(order)">支付订单</shop-button>
                    <shop-button size="small" type="border" v-if="canClose.indexOf(order.status) !== -1" @click="toClose(order)">关闭订单</shop-button>
                    <shop-button size="small" type="border" v-if="canShip.indexOf(order.status) !== -1">查看物流</shop-button>
                    <shop-button size="small" type="border" v-if="canRefund.indexOf(order.status) !== -1" @click="toRefund(order)">退换货</shop-button>
                    <shop-button size="small" v-if="canReceive.indexOf(order.status) !== -1" @click="toReceive(order)">确认收货</shop-button>
                </div>
            </div>
        </li>
        <div v-if="!nomore" class="order-list-placeholder">加载中...</div>
        <div v-if="nomore" class="order-list-placeholder">没有更多了</div>
    </ul>
    <pay-popup
        :payShow="payShow"
        :orderNo="currentNo"
        @close="payShow = false"
        @confirm="confirmPay">
    </pay-popup>
    <refund-popup
        :refundShow="refundShow"
        :order="currentOrder"
        @close="refundShow = false"
        @confirm="confirmRefund">
    </refund-popup>
</div>
</template>

<script>
import { get_orders, change_order_status } from '@/api/order'
import { Toast, MessageBox } from 'mint-ui'
import RefundPopup from './components/RefundPopup'
import PayPopup from './components/PayPopup'
export default{
    components:{
        RefundPopup,
        PayPopup
    },
    data(){
        return{
            fetchQuery:{
                status:this.$route.params.status !== undefined ? this.$route.params.status : '',
                page:0,
                pageSize:15
            },
            loading:false,
            nomore:false,
            height:0,
            orders:[],
            canClose:['pending'],
            canShip:['sent','partial','success'],
            canRefund:['processing','sent','partial','success'],
            canReceive:['sent','partial'],
            disabledStatus:['cancel','closed','refunded'],
            payShow:false,
            currentNo:'',
            currentOrder:{},
            refundShow:false
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 170
        })
        window.onresize = () => {
            this.height = window.innerHeight - 170
        }
    },
    beforeRouteLeave(to,from,next){
        if(to.name === 'UserOrderDetail') from.meta.keepAlive = true
            else from.meta.keepAlive = false
        next()
    },
    methods:{
        getOrders(){
            this.loading = true
            this.nomore = false
            this.fetchQuery.page += 1
            get_orders(this.fetchQuery).then(r=>{
                let _data = r.data.body.data
                if(_data.length === 0){
                    this.loading = true
                    this.nomore = true
                }else{
                    this.loading = false
                    this.nomore = false
                    this.orders = this.orders.concat(_data)
                }
            }).catch(e=>{
                this.loading = true
                this.nomore = true
                if(e.response.status === 401){
                    Toast('登录超时')
                    this.$router.push({name:'Login',query:{from:this.$route.name}})
                }
            })
        },
        changeStatus(status){
            this.fetchQuery.status = status
            this.fetchQuery.page = 0
            this.orders = []
            this.loading = false
            this.nomore = false
            this.getOrders()
        },
        toCheck(order){
            this.$router.push({name:'UserOrderDetail', query:{no:order.no}})
        },
        toClose(order){
            MessageBox.confirm('是否确认关闭?').then(()=>{
                change_order_status(order.no,{status:'cancel'}).then(()=>{
                    Toast('订单已关闭')
                    this.fetchQuery.page = 0
                    this.orders = []
                    this.loading = false
                    this.nomore = false
                    this.getOrders()
                })
            }).catch(()=>{})
        },
        toPay(order){
            this.currentNo = order.no
            this.payShow = true
        },
        confirmPay(){
            this.$router.go(0)
        },
        toRefund(order){
            this.currentOrder = {
                no:order.no,
                status_value:order.status_value,
                amount:order.amount
            }
            this.refundShow = true
        },
        confirmRefund(){
            this.$router.go(0)
        },
        toReceive(order){
            MessageBox.confirm('是否确认收货?').then(()=>{
                change_order_status(order.no,{status:'success'}).then(()=>{
                    Toast('已确认收货')
                    this.fetchQuery.page = 0
                    this.orders = []
                    this.loading = false
                    this.nomore = false
                    this.getOrders()
                })
            }).catch(()=>{})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.user-order-menubar{
    margin:0;
    padding:10px 0 15px;
    height:20px;
    background-color:#fff;
}
.user-order-menubar li{
    display:inline-block;
    width:calc(20% - 10px);
    margin:0 5px;
    text-align:center;
    font-size:$small-font-size;
}
.user-order-menubar li.is-active{
    @include element-color(1);
}
.user-order-menubar li.is-active:after{
    position:relative;
    display:block;
    content:'';
    top:8px;
    height:10px;
    border-top-width:2px;
    border-top-style:solid;
    @include element-border-color(1);
}

.order-list{
    margin:0;
    margin-top:10px;
    padding:0;
    overflow-y:scroll;
}
.order-list-placeholder{
    line-height:30px;
    text-align:center;
    font-size:$small-font-size;
    color:$other-font-color;
}

.order-list-item{
    margin:0 5px 10px;
    padding:10px;
    border:1px solid $line-color;
    border-radius:5px;
    background-color:#fff;
    box-shadow:0 0 3px 2px rgba(0,0,0,0.05);
}
.order-list-item__title{
    padding-bottom:10px;
    margin-bottom:10px;
    border-bottom:1px solid $line-color;
    font-size:$middle-font-size;
}
.order-list-item__title>div{
    display:inline-block;
    vertical-align:middle;
}
.order-list-item__title>div:first-child{
    width:calc(100% - 80px);
}
.order-list-item__title>div:last-child{
    text-align:right;
    @include base-color(1);
    font-weight:500;
    width:80px;
}
.order-list-item__title>div:last-child.is-disabled{
    color:$other-font-color;
}

.order-list-item__item{
    margin:5px 0;
}
.order-list-item__item>div{
    display:inline-block;
    vertical-align:middle;
    font-size:$small-font-size;
    line-height:$small-font-height;
}
.order-list-item__item-title{
    width:120px;
    padding:10px;
}
.order-list-item__item-title>div:first-child{
    max-height:$middle-font-height * 3;
    overflow:hidden;
    color:$main-font-color;
}
.order-list-item__item-title>div:last-child{
    height:$middle-font-height;
    overflow:hidden;
    color:$sub-font-color;
}
.order-list-item__item-price{
    width:calc(100% - 202px);
    text-align:right;
}
.order-list-item__item-price>div:first-child{
    @include price-color(1);
}
.order-list-item__item-price>div:first-child strong{
    font-size:$normal-font-size;
}
.order-list-item__item-price>div:last-child{
    color:$sub-font-color;
}
.order-list-item__item-image{
    border:1px solid $line-color;
    border-radius:5px;
}
.order-item-placeholder{
    background-color:$line-color;
    width:60px;
    height:60px;
    border-radius:5px;
}

.order-list-item__bottom{
    margin-top:10px;
    padding:10px 0 5px;
    border-top:1px solid $line-color;
    font-size:$small-font-size;
    text-align:right;
    color:$main-font-color;
}
.order-list-item__bottom>div:first-child{
    margin-bottom:10px;
}
.order-list-item__bottom>div:last-child button{
    margin-left:5px;
}

</style> 