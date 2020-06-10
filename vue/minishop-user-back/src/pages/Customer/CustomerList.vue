<template>
<div>
    <div class="customer-searchbar">
        <el-input v-model="searchValue">
            <el-select v-model="searchType" slot="prepend" placeholder="请选择">
                <el-option label="用户名" value="username"></el-option>
                <el-option label="手机号" value="mobile"></el-option>
            </el-select>
        </el-input>
        <div style="margin:15px 0 10px;text-align:center">
            <el-button size="small" @click="reset">重置</el-button>
            <el-button type="primary" size="small" @click="search">搜索</el-button>
        </div>
    </div>
    <div style="background-color:#fff;border-radius:5px;">
        <el-table :data="data" :loading="loading" border header-row-class-name="table-header-row" class="customer-table">
            <el-table-column label="昵称" prop="username"></el-table-column>
            <el-table-column label="手机号" prop="mobile"></el-table-column>
            <el-table-column label="会员等级" prop="mobile"></el-table-column>
            <el-table-column label="注册时间" prop="created_at"></el-table-column>
            <el-table-column label="上次登录" prop="created_at"></el-table-column>
            <el-table-column label="成交总单数" prop="balance"></el-table-column>
            <el-table-column label="消费总金额" prop="balance"></el-table-column>
            <el-table-column label="用户钱包余额" width="120px">
                <div slot-scope="scope">
                    <div>¥ {{ scope.row.balance }}</div>
                    <div><el-button type="text" size="small" @click="toWallet(scope.row,scope.row.id)">钱包记录</el-button></div>
                </div>
            </el-table-column>
            <el-table-column label="操作" width="200px">
                <div slot-scope="scope">
                    <el-button type="primary" size="small" @click="toPay(scope.row)">充值/扣款</el-button>
                    <el-button size="small" @click="editPass(scope.row)">修改密码</el-button>
                </div>
            </el-table-column>
        </el-table>
        <el-pagination
            style="padding:20px 0;text-align:center"
            :current-page="query.page"
            :page-size="query.pageSize"
            layout="total, prev, pager, next, jumper"
            :total="total"
            @current-change="handleCurrentChange">
        </el-pagination>
    </div>
    <el-dialog :title="'用户' + username + ' 钱包记录'" width='800px' :visible.sync="walletListShow">
        <div v-loading="walletLoading">
            <div class="wallet-title"><strong>钱包余额：<span>¥ {{ walletBalance }}</span></strong></div>
            <el-table :data="walletData" border>
                <el-table-column label="流水号" prop="no"></el-table-column>
                <el-table-column label="时间" prop="created_at"></el-table-column>
                <el-table-column label="类型" width="70px">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.type_code == 'in'" type="success">{{scope.row.type}}</el-tag>
                        <el-tag v-else type="danger">{{scope.row.type}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="金额" prop="amount" width="100px"></el-table-column>
                <el-table-column :show-overflow-tooltip="true" label="说明" prop="content"></el-table-column>
            </el-table>
            <el-pagination
                style="margin:20px 0;text-align:center"
                :current-page="walletQuery.page"
                :page-size="walletQuery.pageSize"
                layout="total, prev, pager, next, jumper"
                :total="walletTotal"
                @current-change="walletHandleCurrentChange">
            </el-pagination>
        </div>
    </el-dialog>

    <el-dialog :title="'用户 '+ username +' 充值/扣款'" :visible.sync="payWalletShow">
        <el-form label-width="80px">
            <el-form-item label="金额" required>
                <el-input-number v-model="payData.amount" required :min="1" :precision="2"></el-input-number>
            </el-form-item>
            <el-form-item label="业务类型" required>
                <el-select v-model="payData.type" required>
                    <el-option label="扣款" value="out"></el-option>
                    <el-option label="充值" value="in"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="备注">
                <el-input type="textarea" v-model="payData.content" :rows = "3"></el-input>
            </el-form-item>
        </el-form>
        <div style="text-align: right;">
            <el-button @click="payWalletShow = false">取消</el-button>
            <el-button type='primary' @click="payWallet" :loading="btnLoading">确认</el-button>
        </div>
    </el-dialog>
</div>
</template>

<script>
import { get_customer,update_customer } from '@/api/customer'
import { get_wallet_list,get_wallet_balance,pay_wallet } from '@/api/wallet'
export default{
    data(){
        return{
            loading:false,
            query:{
                page:1,
                pageSize:15,
                username:'',
                mobile:''
            },
            total:0,
            data:[],
            searchType:"username",
            searchValue:null,
            walletListShow:false,
            walletData:[],
            walletLoading:false,
            walletCustomerId:null,
            walletQuery:{
                page:1,
                pageSize:5
            },
            walletTotal:0,
            walletBalance:0,
            username:null,
            payWalletShow:false,
            payData:{
                amount:100,
                type:"in",
                content:null
            },
            btnLoading:false
        }
    },
    created(){
        this.getData()
    },
    methods:{
        getData(){
            this.loading = true
            get_customer(this.query).then(r=>{
                this.data = r.data.body.data
                this.total = r.data.body.page.total
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        getWalletData(){
            this.walletLoading = true
            get_wallet_list(this.walletCustomerId,this.walletQuery).then(r=>{
                let _wallet = r.data.body
                this.walletData = _wallet.data
                this.walletTotal = _wallet.page.total
                this.walletLoading = false
            })
            get_wallet_balance(this.walletCustomerId).then(r=>{
                this.walletBalance = r.data.body.balance
            })

        },
        handleCurrentChange(pagination){
            this.query.page = pagination
            this.getData()
        },
        editPass(row){
            this.$prompt('重置后顾客将使用新的密码进行登录，请谨慎操作，并且及时通知顾客密码已变更。如果确认重置，请在下方输入重置密码，并点击重置按钮。','提示',{
                confirmButtonText:'重置',
                cancelButtonText:'关闭'
            }).then(({value})=>{
                update_customer(row.id,{password:value}).then(()=>{
                    this.$message.success('操作成功')
                    this.getData()
                })
            }).catch(()=>{})
        },
        search(){
            this.query = {
                page:this.query.page,
                pageSize:this.query.pageSize
            }
            this.query[this.searchType] = this.searchValue
            this.getData()
        },
        reset(){
            this.query = {
                page:1,
                pageSize:15
            }
            this.getData()
        },
        toWallet(row,id){
            this.walletListShow = true
            this.walletCustomerId = id
            this.walletData = []
            this.walletBalance = 0
            this.username = row.username
            this.getWalletData()
        },
        walletHandleCurrentChange(val){
            this.walletQuery.page = val
            this.getWalletData()
        },
        toPay(row){
            this.username = row.username
            this.walletCustomerId = row.id
            this.payData={
                amount:100,
                type:"in",
                content:null
            }
            this.payWalletShow = true
        },
        payWallet(){
            this.btnLoading = true
            pay_wallet(this.walletCustomerId,this.payData).then(()=>{
                this.payWalletShow = false
                this.$message.success(this.payData.type === 'in' ? '充值' : '扣款' + '成功')
                this.getData()
                this.btnLoading = false
            }).catch(()=>{
                this.btnLoading = false
            })
        }

    }
}
</script>

<style lang="scss">
@import '@/assets/style/base.scss';
.el-table tr.table-header-row th{
    background-color:$background-color;
}
.customer-searchbar .el-input--suffix input{
    width: 100px;
}
</style>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.customer-searchbar{
    margin-bottom: 15px;
    background-color:#fff;
    padding:10px;
    border-radius:5px;
}
.customer-searchbar .el-button{
    width:100px;
    margin-left: 10px;
}
.customer-table{
    border-radius:5px 5px 0 0;
}
.wallet-title{
    margin-bottom:10px;
}
.wallet-title span{
    font-size:$large-font-size;
    color:$dark-back-sub;
}
</style>