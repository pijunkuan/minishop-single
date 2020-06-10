<template>
    <el-dialog :visible="Visible" title="退款" width="650px" :before-close="close">
        <div v-loading="loading">
            <el-form label-width="100px">
                <el-form-item label="订单金额">
                    <div>¥ <strong>{{ refund_info.order_amount }}</strong></div>
                </el-form-item>
                <el-form-item label="已退款金额" v-if="refund_info.order_amount * 1 > (refund_info.refund_amount_rest + refund_info.refund_amount_success)">
                    <div>
                        <span class="sub-title">正在退款 </span>
                        <span>¥ <strong>{{ refund_info.refund_amount_pending }}</strong></span>
                    </div>
                    <div>
                        <span class="sub-title">成功退款 </span>
                        <span>¥ <strong>{{ refund_info.refund_amount_success }}</strong></span>
                    </div>
                    <div><el-button type="text" @click="seeDetail = !seeDetail">{{ seeDetail ? '收起' : '查看' }}退款记录</el-button></div>
                    <div v-if="seeDetail" class="detail-items">
                        <div class="detail-list detail-header">
                            <div style="width:100px">金额</div>
                            <div style="width:150px">时间</div>
                            <div style="width:calc(100% - 335px)">备注</div>
                            <div style="width:60px">状态</div>
                        </div>
                        <div v-for="(item,index) in refund_info.refund_record_list" :key="index" class="detail-list">
                            <div style="width:100px">¥ {{ item.amount }}</div>
                            <div style="width:150px">{{ item.created_at }}</div>
                            <div style="width:calc(100% - 335px)">{{ item.content }}</div>
                            <div style="width:60px">{{ item.status_value }}</div>
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="剩余退款金额" v-if="refund_info.order_amount * 1 > (refund_info.refund_amount_rest + refund_info.refund_amount_success)">
                    <div>¥ <strong>{{ refund_info.refund_amount_rest }}</strong></div>
                </el-form-item>
                <el-form-item label="填写退款金额" v-if="refund_info.refund_amount_rest !== 0">
                    <el-input-number v-model="refund.amount" :min="0.01" :max="refund_info.refund_amount_rest" :precision="2">
                    </el-input-number>
                    <div style="cursor:pointer;display:inline-block;margin-left:10px" @click="refund.amount = refund_info.refund_amount_rest">全额退款</div>
                </el-form-item>
                <el-form-item label="退款备注">
                    <el-input v-model="refund.content"></el-input>
                </el-form-item>
            </el-form>
        </div>
        <div slot="footer">
            <el-button @click="close">取消</el-button>
            <el-button @click="confirm" type="primary" :loading="btnLoading">确定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import { get_refund_record, create_refund } from '@/api/order'
export default{
    props:{
        Visible:Boolean
    },
    data(){
        return{
            refund_info:{
                order_amount:null,
                refund_amount_pending:null,
                refund_amount_rest:null,
                refund_amount_success:null,
                refund_record_list:[]
            },
            loading:false,
            refund:{
                amount:0.01,
                content:''
            },
            btnLoading:false,
            seeDetail:false
        }
    },
    methods:{
        getRefund(){
            this.loading = true
            get_refund_record(this.$route.query.id).then(r=>{
                this.refund_info = r.data.body
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        reset(){
            this.refund_info = {
                order_amount:null,
                refund_amount_pending:null,
                refund_amount_rest:null,
                refund_amount_success:null,
                refund_record_list:[]
            }
            this.refund = {
                amount:0.01,
                content:''
            }
            this.seeDetail = false
        },
        close(){
            this.reset()
            this.$emit('close')
        },
        confirm(){
            this.btnLoading = true
            create_refund(this.$route.query.id,this.refund).then(()=>{
                this.$message.success('退款成功')
                this.btnLoading = false
                this.reset()
                this.$emit('confirm')
            }).catch(e=>{
                this.$message.warning({
                    message:e.response.data.message,
                    duration:4000
                })
                this.btnLoading = false
            })
        }
    },
    watch:{
        Visible:{
            deep:true,
            handler:function(){
                if(this.Visible) this.getRefund()
            }
        }
    }
}
</script>

<style scoped lang="scss">
@import '@/assets/style/base.scss';
.item-list{
    margin:10px 0;
    padding:0 10px;
    width:calc(100% - 20px);
    background-color:$light-back-sub;
    border-radius:5px;
}
.item-list .el-checkbox__label>div{
    display:inline-block;
    vertical-align:middle;
}
.refund-title{
    font-weight:500;
}
.sub-title{
    color:$other-font-color;
    margin-right:10px;
}
.detail-items{
    border:1px solid $line-color;
    border-radius:5px;
}
.detail-list{
    line-height:20px;
    padding:10px 5px;
    border-top:1px solid $line-color;
}
.detail-list:nth-child(even){
    background-color:$light-back-sub;
}
.detail-header{
    background:none;
    color:$other-font-color;
    font-weight:500;
    border-top:none;
}
.detail-list>div{
    display:inline-block;
    vertical-align:middle;
    margin-right:5px;
}
.detail-list:last-child{
    border-radius:0 0 5px 5px;
}
</style>
<style>
.item-list .el-checkbox__label{
    width:100%;
}
</style>