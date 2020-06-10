<template>
<div>
    <div class="message-header">
        <div>
            <el-button type="primary" size="mini" @click="editSign">设置签名</el-button>
        </div>
        <div>
            <span>短信通知：{{ $store.getters.shop_sms }} 条可用</span>
            <el-button style="margin-left:10px" type="primary" size="mini" @click="smsHistory">短信使用历史</el-button>
        </div>
    </div>
    <div v-loading="loading" class="message-content">
        <div class="message-content-header">顾客通知</div>
        <div v-for="(type,index) in customer_templates" :key="index" style="margin-bottom:20px">
            <div class="message-content__title">{{ type[0].type }}</div>
            <div class="message-content__header">
                <div style="width:240px">模版名称</div>
                <div style="width:calc(100% - 360px)">通知模版</div>
                <div style="width:50px">状态</div>
                <div style="width:60px">操作</div>
            </div>
            <div v-for="(item,index) in type" :key="'i'+index" class="message-content__item">
                <div style="width:240px">{{ item.template_name }}</div>
                <div style="width:calc(100% - 360px)">{{ item.template_content }}</div>
                <div style="width:50px">{{ item.active ? '启用' : '停用' }}</div>
                <div style="width:60px">
                    <el-button :type="item.active ? 'danger' : 'primary'" size="mini" :disabled="type[0].type === '登录与注册'" :loading="item.isLoading" @click="changeStatus(item)">{{ item.active ? '停用' : '启用' }}</el-button>
                </div>
            </div>
        </div>
    </div>
    <div v-loading="loading" class="message-content">
        <div class="message-content-header">管理员通知</div>
        <div v-for="(type,index) in admin_templates" :key="index" style="margin-bottom:20px">
            <div class="message-content__title">{{ type[0].type }}</div>
            <div class="message-content__header">
                <div style="width:240px">模版名称</div>
                <div style="width:calc(100% - 360px)">通知模版</div>
                <div style="width:50px">状态</div>
                <div style="width:60px">操作</div>
            </div>
            <div v-for="(item,index) in type" :key="'i'+index" class="message-content__item">
                <div style="width:240px">{{ item.template_name }}</div>
                <div style="width:calc(100% - 360px)">{{ item.template_content }}</div>
                <div style="width:50px">{{ item.active ? '启用' : '停用' }}</div>
                <div style="width:60px">
                    <el-button :type="item.active ? 'danger' : 'primary'" :loading="item.isLoading" size="mini" @click="changeStatus(item)">{{ item.active ? '停用' : '启用' }}</el-button>
                </div>
            </div>
        </div>
    </div>
    <el-dialog :visible="signVisible" title="设置签名" width="500px" :before-close="closeSign">
        <div v-loading="signLoading" class="sign-page">
            <div>
                <div class="sign-list sign-header">
                    <div style="width:160px">签名</div>
                    <div style="width:100px">审核结果</div>
                    <div style="width:80px">状态</div>
                    <div style="width:100px;">操作</div>
                </div>
                <div v-for="(item,index) in signs" :key="index" class="sign-list">
                    <div style="width:160px">{{ item.sign_name }}</div>
                    <div style="width:100px">{{ item.status_value }}</div>
                    <div style="width:80px">{{ item.active ? '使用中' : '停用' }}</div>
                    <div style="width:100px;">
                        <el-button type="text" @click="toEdit(item)" v-if="item.status === 'success'">{{ item.active ? '停用' : '启用' }}</el-button>
                        <el-button type="text" @click="toDelete(item)">删除</el-button>
                    </div>
                </div>
            </div>
            <el-pagination
                style="text-align:right;"
                :current-page="signQuery.page"
                :page-size="signQuery.pageSize"
                :total="signTotal"
                layout="prev, next"
                @current-change="handleSignPage">
            </el-pagination>
            <div style="margin:20px 0"><el-button type="primary" size="mini" @click="isAdd = true" :disabled="signs.filter((val)=>{ return val.isEdit}).length !== 0">新增签名</el-button></div>
        </div>
        <div v-if="isAdd">
            <div style="margin-bottom:20px">短信签名需提交到短信运营商审核，预计1-2个工作日，签名只允许3-8个字符，且必须包含汉字，不允许特殊字符</div>
            <el-input class="el-form-item" :class="{'is-error':error}" v-model="sign" @blur="testSign(sign)" @focus="restartTest"></el-input>
            <div class="error-tip">{{ error }}</div>
        </div>
        <div slot="footer" v-if="isAdd">
            <el-button size="small" @click="closeSign">关闭</el-button>
            <el-button size="small" @click="cancelSign">取消</el-button>
            <el-button size="small" type="primary" @click="confirmSign" :loading="btnLoading">确定</el-button>
        </div>
    </el-dialog>
    <el-dialog :visible="historyVisible" title="短信使用历史" width="800px" :before-close="closeHistory">
        <div v-loading="hisLoading">
            <div class="sms-list sms-header">
                <div style="width:120px">接收手机</div>
                <div style="width:420px">发送内容</div>
                <div style="width:80px">消费数量</div>
                <div style="width:80px">剩余数量</div>
            </div>
            <div v-for="(item,index) in history" :key="index" class="sms-list">
                <div style="width:120px">{{ item.mobile }}</div>
                <div style="width:420px">{{ item.content }}</div>
                <div style="width:80px">{{ + item.amount }}</div>
                <div style="width:80px">{{ item.next_amount }}</div>
            </div>
            <el-pagination
                style="text-align:center;margin-top:20px"
                :current-page="hisQuery.page"
                :page-size="hisQuery.pageSize"
                :total="hisTotal"
                layout="total, prev, pager, next, jumper"
                @current-change="handleHisPage">
            </el-pagination>
        </div>
    </el-dialog>
    <el-dialog :visible="tipShow" title="提示" :show-close="false" :close-on-click-modal="false" :close-on-press-escape="false" center>
        <div class="dialog-tip">
            <div>付款过程中请勿关闭当前页面，付款成功后请点击“付款成功”按钮</div>
            <div>付款失败或取消付款，请点击“取消付款”按钮，关闭弹窗</div>
        </div>
        <div slot="footer">
            <el-button size="small" @click="tipShow = false">取消付款</el-button>
            <el-button size="small" type="primary" @click="validPay" :loading="validLoading">付款成功</el-button>
        </div>
    </el-dialog>
</div>
</template>

<script>
import { get_sms_template, update_sms_template_status, get_sms_sign, delete_sms_sign, update_sms_sign, create_sms_sign, get_sms_history } from '@/api/sms'
export default{
    data(){
        return{
            loading:false,
            customer_templates:[],
            admin_templates:[],
            buyVisible:false,
            payLoading:false,
            message_type:{},
            message_types:[],
            buyLoading:false,
            payments:[],
            current_payment:'',
            signVisible:false,
            sign:'',
            error:'',
            signs:[],
            signQuery:{
                page:1,
                pageSize:10
            },
            signTotal:0,
            signLoading:false,
            isAdd:false,
            btnLoading:false,
            history:[],
            hisLoading:false,
            hisTotal:0,
            hisQuery:{
                page:1,
                pageSize:15
            },
            historyVisible:false,
            order_no:'',
            validLoading:false,
            tipShow:false
        }
    },
    created(){
        this.getTemplate()
    },
    methods:{
        getTemplate(){
            this.loading = true
            this.customer_templates = []
            this.admin_templates = []
            get_sms_template().then(r=>{
                let _admin_order = []
                r.data.body.admin_order.map(v=>{
                    v['isLoading'] = false
                    let _item = v
                    _item['type'] = '订单通知'
                    _admin_order.push(_item)
                })
                this.admin_templates.push(_admin_order)
                let _front_customer = []
                r.data.body.front_customer.map(v=>{
                    v['isLoading'] = false
                    let _item = v
                    _item['type'] = '登录与注册'
                    _front_customer.push(_item)
                })
                this.customer_templates.push(_front_customer)
                let _front_order = []
                r.data.body.front_order.map(v=>{
                    v['isLoading'] = false
                    let _item = v
                    _item['type'] = '订单通知'
                    _front_order.push(_item)
                })
                this.customer_templates.push(_front_order)
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        editSign(){
            this.signVisible = true
            this.getSigns()
            this.sign = this.message.sign
        },
        getSigns(){
            this.signLoading = true
            get_sms_sign(this.signQuery).then(r=>{
                this.signs = r.data.body.data
                this.signTotal = r.data.body.page.total
                this.signLoading = false
            }).catch(()=>{
                this.signLoading = false
            })
        },
        handleSignPage(pagination){
            this.signQuery.page = pagination
            this.getSigns()
        },
        testSign(val){
            const _length = /^.{3,8}$/
            const _character = /^[\u4E00-\u9FA5A-Za-z0-9]+$/
            if(!_length.test(val)){
                this.error = '签名必须在3-8个字符'
            }else if(!_character.test(val)){
                this.error = '签名只能包含汉字、数字和英文字符，不允许特殊字符'
            }
        },
        restartTest(){
            this.error = ''
        },
        closeSign(){
            this.error = ''
            this.sign = ''
            this.isAdd = false
            this.signVisible = false
        },
        cancelSign(){
            this.restartTest()
            this.isAdd = false
            this.sign = ''
        },
        toDelete(item){
            this.$confirm('是否确认删除？','提示',{
                confirmButtonText:'确定',
                cancelButtonText:'取消'
            }).then(()=>{
                delete_sms_sign(item.sign_id).then(()=>{
                    this.$message.success('删除成功')
                    this.getSigns()
                })
            }).catch(()=>{})
        },
        toEdit(item){
            update_sms_sign(item.sign_id,{active:!item.active}).then(()=>{
                this.$message.success('更新成功')
                this.getSigns()
            })
        },
        confirmSign(){
            if(!this.sign){
                this.$message.warning('请填写签名再提交')
                return
            }
            this.btnLoading = true
            create_sms_sign({sign_name:this.sign}).then(()=>{
                this.$message.success('提交成功，请等待审核')
                this.cancelSign()
                this.getSigns()
                this.btnLoading = false
            }).catch(()=>{
                this.btnLoading = false
            })
        },
        changeStatus(item){
            item.isLoading = true
            update_sms_template_status(item.sms_template_id).then(()=>{
                this.$message.success('更改成功')
                this.getTemplate()
                item.isLoading = false
            }).catch(()=>{
                item.isLoading = false
            })
        },
        smsHistory(){
            this.historyVisible = true
            this.getHistory()
        },
        getHistory(){
            this.hisLoading = true
            get_sms_history(this.hisQuery).then(r=>{
                this.history = r.data.body.data
                this.hisTotal = r.data.body.page.total
                this.hisLoading = false
            }).catch(()=>{
                this.hisLoading = false
            })
        },
        handleHisPage(pagination){
            this.hisQuery.page = pagination
            this.getHistory()
        },
        closeHistory(){
            this.historyVisible = false
            this.history = []
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.message-header,.message-content{
    padding:10px;
    margin-bottom:10px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:5px;
}
.message-header>div{
    display:inline-block;
    vertical-align:middle;
    width:50%;
}
.message-header>div:last-child{
    text-align:right;
}
.message-content{
    min-height:200px;
}
.message-content>div>div>div{
    display:inline-block;
    vertical-align:middle;
}
.message-content-header{
    padding-bottom:10px;
    border-bottom:1px solid $line-color * 0.98;
    font-weight:500;
    margin-bottom:15px;
    line-height:30px;
    font-size:$large-font-size;
}
.message-content__title{
    color:$other-font-color;
    margin-bottom:10px;
}
.message-content__header{
    padding:5px;
    background-color:$light-back-sub;
    border:1px solid $line-color * 0.98;
}
.message-content__item{
    padding:5px;
    border:1px solid $line-color * 0.98;
    border-top:none;
}
.buy-item{
    margin-bottom:10px;
}
.buy-item>div{
    display:inline-block;
    vertical-align:middle;
}
.buy-price{
    background-color:$light-back-sub;
    padding:10px;
    text-align:right;
    margin:20px 0;
}
.buy-price span{
    color:$main-color;
}
.buy-price span strong{
    font-size:$large-font-size;
}
.buy-footer{
    text-align:right;
}
.error-tip{
    color:$error-color;
    margin-top:-15px;
}

.sign-header{
    background-color:$light-back-sub * 0.98;
    border-radius:5px;
}
.sign-list{
    padding:5px 10px;
}
.sign-list>div{
    display:inline-block;
    vertical-align:middle;
}

.sms-list>div{
    display:inline-block;
    vertical-align:top;
    padding:5px;
}
.sms-list{
    margin:5px 0;
    background-color:$light-back-sub * 0.99;
    border-radius:5px;
}
.sms-list:hover{
    background-color:$light-back-sub * 0.95;
}
.sms-header,.sms-header:hover{
    font-weight:500;
    background:none;
}
</style>

<style lang="scss">
@import '@/assets/style/base.scss';
.sign-page .el-pagination button{
    border:1px solid $line-color;
    padding:0 6px;
    margin:0 2px;
    border-radius:5px;
}
</style>