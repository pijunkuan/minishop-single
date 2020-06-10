<template>
    <el-dialog title='发货' :visible="Visible" width="600px" :before-close="close">
        <div class="send-content">
            <div class="send-header">待发货商品</div>
            <div class="select-header">
                <div style="width:80px">图片</div>
                <div style="width:calc(100% - 230px);margin-right:20px">名称</div>
                <div style="width:130px">数量</div>
            </div>
            <div v-for="(item,index) in pendItems" :key="index">
                <el-checkbox v-model="selected_items" :label="item" class="select-item">
                    <div style="width:80px">
                        <el-image style="width:60px;height:60px" :src="item.thumbnail">
                            <div slot="placeholder" class="image-placeholder"><i class="iconfont icontupian1"></i></div>
                            <div slot="error" class="image-placeholder"><i class="iconfont icontupian1"></i></div>
                        </el-image>
                    </div>
                    <div style="width:calc(100% - 230px);margin-right:10px">
                        <div>{{ item.product_title }}</div>
                        <div>{{ item.variant_title }}</div>
                    </div>
                </el-checkbox>
                <div style="width:130px;display:inline-block"><el-input-number :min="1" :max="item.quantity * 1" :precision="0" size="mini" v-model="item.amount"></el-input-number></div>
            </div>
        </div>
        <el-form label-width="68px">
            <el-form-item label="运输公司">
                <el-input v-model="sendInfo.shipment_company"></el-input>
            </el-form-item>
            <el-form-item label="运单号">
                <el-input v-model="sendInfo.shipment_no"></el-input>
            </el-form-item>
            <div style="text-align: right;">
                <el-button @click="close">取消</el-button>
                <el-button @click="sendOrder" type="primary" :loading="btnLoading">确认发货</el-button>
            </div>
        </el-form>
    </el-dialog>
</template>

<script>
import { send_order } from '@/api/order'
export default{
    props:{
        Visible:Boolean,
        pendItems:Array
    },
    data(){
        return{
            selected_items:[],
            sendInfo:{
                shipment_no:'',
                shipment_company:''
            },
            btnLoading:false
        }
    },
    methods:{
        sendOrder(){
            if(this.selected_items.length === 0){
                this.$message.warning('请选择发货商品')
                return
            }
            if(!this.sendInfo.shipment_company){
                this.$message.warning('请填写运输公司')
                return
            }
            if(!this.sendInfo.shipment_no){
                this.$message.warning('请填写运单号')
                return
            }
            this.btnLoading = true
            let items = []
            this.selected_items.map(v=>{
                items.push({
                    variant_id:v.variant_id,
                    quantity:v.amount
                })
            })
            this.sendInfo['items'] = items
            send_order(this.$route.query.id,this.sendInfo).then(()=>{
                this.$message.success('发货成功')
                this.$emit('confirm')
                this.btnLoading = false
            }).catch(()=>{
                this.btnLoading = false
            })
        },
        close(){
            this.$emit('close')
        }
    }
}
</script>

<style lang="scss" scoped>
@import "@/assets/style/base.scss";
.send-content{
    margin-bottom:20px;
}
.send-header{
    color:$sub-font-color;
    font-weight:500;
    margin-bottom:20px;
}
.select-header{
    color:$sub-font-color;
    margin-left:24px;
    font-weight:500;
    padding-left:5px;
    color:$other-font-color;
}
.select-item .el-checkbox__label>div,.select-header>div{
    display:inline-block;
    vertical-align:middle;
}
.select-item{
    margin:5px 0;
    padding:5px;
    display:inline-block;
    width:calc(100% - 140px);
}
.select-item .el-image{
    border:1px solid $line-color * 0.95;
    border-radius:5px;
}

.shipment-item{
    margin:0;
    margin-top:10px;
    padding:0;
    list-style:none;
}
.shipment-item li{
    background-color:$background-color;
    padding:5px;
    margin-bottom:10px;
}
.image-placeholder{
    line-height:60px;
    text-align:center;
    background-color:#fff;
}
</style>
<style>
.select-item .el-checkbox__label{
    width:535px;
}
</style>