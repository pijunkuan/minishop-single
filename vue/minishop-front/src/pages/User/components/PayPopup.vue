<template>
    <shop-popup size="medium" :show="payShow" @close="closePay">
        <div slot="title">选择支付方式</div>
        <div slot="content">
            <mt-picker
                ref="pays"
                :slots="slots">
            </mt-picker>
        </div>
        <div slot="button" class="pay-popup-button">
            <div><shop-button type="border" :rounded="false" @click="closePay">取消支付</shop-button></div>
            <div><shop-button :rounded="false" :disabled="btnLoading" @click="confirmPay">确认支付</shop-button></div>
        </div>
    </shop-popup>
</template>

<script>
import { get_payments } from '@/api/wallet'
import { create_pay } from '@/api/order'
export default{
    props:{
        payShow:Boolean,
        orderNo:String
    },
    data(){
        return{
            payments:[],
            slots:[{
                flex:1,
                values:[],
                textAlign:'center'
            }],
            btnLoading:false
        }
    },
    created(){
        this.getPayments()
    },
    methods:{
        getPayments(){
            get_payments().then(r=>{
                this.payments = r.data.body
                this.payments.map(v=>{
                    this.slots[0].values.push(v.title)
                })
            })
        },
        closePay(){
            this.$emit('close')
        },
        confirmPay(){
            if(this.btnLoading) return
            this.btnLoading = true
            let _method = ''
            this.payments.map(v=>{
                if(v.title === this.$refs.pays.values[0]) _method = v.code
            })
            create_pay(this.orderNo,_method).then(r=>{
                if(r.data.body.status === 'success'){
                    this.$router.push({name:'Checksuc', query:{no:this.orderNo}})
                }else{
                    location.href = r.data.body.pay_url
                }
            }).catch(()=>{
                this.btnLoading = false
            })
        }
    }
}
</script>

<style scoped>
.pay-popup-button>div{
    display:inline-block;
    width:50%;
}
</style>