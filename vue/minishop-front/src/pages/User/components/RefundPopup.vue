<template>
    <shop-popup size="medium" full :show="refundShow" @close="closeRefund">
        <div slot="title">退换货</div>
        <div slot="content">
            <div class="refund-info">
                <div>订单号：{{ order.no }}</div>
                <div>当前状态：{{ order.status_value }}</div>
                <div>金额：¥ {{ order.amount }}</div>
            </div>
            <div class="refund-reason">
                <div style="margin-bottom:10px">选择退单原因</div>
                <div v-for="(item,index) in reasons" :key="index">
                    <shop-radio v-model="refundReason" :label="item"></shop-radio>
                </div>
                <div v-if="refundReason === '其他原因'">
                    <div style="margin:30px 0 10px">请填写具体原因</div>
                    <shop-input v-model="reasonDetail" :rounded="false"></shop-input>
                </div>
            </div>
        </div>
        <div slot="button" class="pay-popup-button">
            <div><shop-button type="border" :rounded="false" @click="closeRefund">关闭</shop-button></div>
            <div><shop-button :rounded="false" :loading="btnLoading" :disabled="btnLoading" @click="confirmRefund">确定</shop-button></div>
        </div>
    </shop-popup>
</template>

<script>
import { Toast } from 'mint-ui'
import { change_order_status } from '@/api/order'
export default{
    props:{
        refundShow:Boolean,
        order:Object
    },
    data(){
        return{
            reasons:['订单信息有误','物流太慢','不想要了','其他原因'],
            refundReason:'订单信息有误',
            reasonDetail:'',
            btnLoading:false
        }
    },
    methods:{
        closeRefund(){
            this.refundReason = '订单信息有误'
            this.reasonDetail = ''
            this.$emit('close')
        },
        confirmRefund(){
            if(this.btnLoading) return
            this.btnLoading = true
            let _reason = ''
            if(this.refundReason === '其他原因') _reason = this.refundReason + ' ' + this.reasonDetail
                else _reason = this.refundReason
            change_order_status(this.order.no,{status:'refunding',reason:_reason}).then(()=>{
                Toast('退款申请已发送，请等待商家处理')
                this.closeRefund()
                this.$emit('confirm')
            }).catch(()=>{
                this.btnLoading = false
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.refund-info{
    padding:10px;
    background-color:$background-color;
    font-size:$small-font-size;
    color:$sub-font-color;
}
.refund-info>div{
    margin:5px 0;
}
.refund-reason{
    margin:20px 10px 10px;
}
.pay-popup-button>div{
    display:inline-block;
    width:50%;
}
</style>