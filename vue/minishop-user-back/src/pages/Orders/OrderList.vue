<template>
<div>
	<div class="table-header">
		<div class="table-header-item">
			<div>订单状态</div>
			<el-radio-group v-model="query.status" size="small">
				<el-radio-button :label="''">全部</el-radio-button>
				<el-radio-button v-for="(item,index) in status" :key="index" :label="item.type">{{ item.value }}</el-radio-button>
			</el-radio-group>
		</div>
		<div class="table-header-item">
			<div>搜索</div>
			<el-input v-model="search" class="input-with-select" size="medium">
				<el-select v-model="searchType" slot="prepend" placeholder="请选择">
					<el-option label="收件人" value="name"></el-option>
					<el-option label="订单号" value="no"></el-option>
					<el-option label="电话" value="mobile"></el-option>
				</el-select>
			</el-input>
		</div>
		<div class="table-header-item" style="text-align:center">
			<el-button @click='reset' size="small">重置</el-button>
			<el-button type="primary" @click='toSearch' size="small">搜索</el-button>
		</div>
	</div>
	<div v-loading="loading" style="background-color:#fff;border-radius:5px">
		<div v-if="!loading && orderData.length === 0" class="loading-placeholder" style="min-height:350px">暂无订单</div>
		<div v-for="(order,index) in orderData" :key="index" class="order-item">
			<div class="order-header">
				<i class="order-icon iconfont icondingdan"></i>
				<strong style="margin-right:10px;" @click="toDetail(order)">{{ order.no }}</strong>
				<el-tag v-if="order.status === 'pending'" type="warning" effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'processing'" effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'sent'" type="primary" effect="plain">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'partial'" type="primary" effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'refunding'" type="danger" effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'refunded'" type="info" effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'cancel' " type='info' effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'closed' " type='info' effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else-if="order.status === 'success' " type='success' effect="dark">{{order.status_value}}</el-tag>
				<el-tag v-else type="info" effect="dark">{{order.status_value}}</el-tag>
				<span style="margin-left:10px">{{ order.created_at }}</span>
			</div>
			<div class="order-content-main">
				<div class="order-content-main-item" style="width:calc(100% - 202px)">
					<div class="order-content-main-item__address">
						<div class="order-content-main-item__title" style="width:70px">收件信息</div>
						<div style="width:calc(100% - 70px)">
							<div>{{ order.name }} | {{ order.mobile }}</div>
							<div>{{ order.address }}</div>
						</div>
					</div>
					<div class="order-content-main-item__address">
						<div class="order-content-main-item__title" style="width:70px">买家留言</div>
						<div style="width:calc(100% - 70px)">{{ order.remark }}</div>
					</div>
				</div>
				<!-- <div class="order-content-main-item" style="width:100px">
					<div class="order-content-main-item__title">支付方式</div>
					<div>{{ order.payment }}</div>
				</div> -->
				<div class="order-content-main-item" style="width:200px">
					<div class="order-content-main-item__title">订单金额</div>
					<div><strong>¥ {{ order.amount }}</strong></div>
				</div>
			</div>
			<div v-for="(item,Index) in order.items" :key="Index" class="order-content-items">
				<el-image style="width:80px;height:80px" :src="item.img_url" fit="scale-down"></el-image>
				<div style="width:calc(100% - 310px);margin:0 10px">
					<div>{{ item.product_title }}</div>
					<div>{{ item.variant_title }}</div>
				</div>
				<div style="width:100px;margin-right:10px"><span>¥ {{ item.price }}</span></div>
				<div style="width:100px">x {{ item.quantity }}</div>
			</div>
		</div>
		<el-pagination
			style="text-align:center;padding:40px 0"
			@size-change="handleSizeChange"
			@current-change="handleCurrentChange"
			:current-page="query.page"
			:page-sizes="[15, 30, 50, 100]"
			:page-size="query.pageSize"
			layout="total, sizes, prev, pager, next, jumper"
			:total="total">
		</el-pagination>
	</div>
</div>
</template>

<script>
	import {get_orders} from "@/api/order"
	export default {
		data(){
			return {
				orderData:[],
				loading:false,
				query:{
					status:'',
					no:'',
					name:'',
					mobile:'',
					page:1,
					pageSize:15
				},
				total:0,
				status:[
					{ type:'pending', value:'待支付'},
					{ type:'processing', value:'待发货'},
					{ type:'sent', value:'已发货'},
					{ type:'refunding', value:'退款中'}
				],
				searchType:'name',
				search:''
			}
		},
		beforeRouteLeave(to,from,next){
			if(to.name === 'OrderDetail') from.meta.keepAlive = true
				else from.meta.keepAlive = false
			next()
		},
		methods:{
			getData(){
				this.loading = true
				get_orders(this.query).then(r=>{
					this.orderData = r.data.body.data
					this.total = r.data.body.page.total
					this.loading=false
				}).catch(()=>{
					this.loading = false
				})
			},
			handleSizeChange(val){
				this.query.pageSize = val
				this.query.page = 1
				this.getData()
			},
			handleCurrentChange(val){
				this.query.page = val
				this.getData()
			},
			toSearch(){
				this.query.page = 1
				this.query[this.searchType] = this.search
				console.log(this.query)
				this.getData()
			},
			reset(){
				this.query.status = ''
				this.query.page = 1
				this.query.value = ''
				this.query.type = ''
				this.getData()
			},
			toDetail(order){
				this.$router.push({name:'OrderDetail',query:{id:order.id}})
			}
		},
		created(){
			if(this.$route.query.status){
				this.query.status  = this.$route.query.status
			}
			this.getData()
		}
	}
</script>

<style lang="scss">
@import '@/assets/style/base.scss';
.table-header .el-input--suffix input{
	width:100px;
}
.table-header .el-radio-group .el-radio-button{
	margin-right:10px;
	margin-bottom:10px;
}
.table-header .el-radio-group .el-radio-button__inner{
	border-left:1px solid #DCDFE6;
	border-radius:4px;
}
.table-header .el-radio-button__orig-radio:checked+.el-radio-button__inner{
	border-left:none;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.table-header{
	margin-bottom:15px;
	background-color:#fff;
	border:1px solid $line-color;
	border-radius:5px;
}
.table-header-item{
	margin:10px;
}
.table-header-item>div:first-child{
	color:$sub-font-color;
	margin-bottom:10px;
}

.order-item{
	border:1px solid $line-color;
	margin:10px 0 15px;
	padding-bottom:10px;
}
.order-header{
	background-color:$background-color * 0.98;
	line-height:50px;
	padding:0 10px;
	border-radius:5px 5px 0 0;
}
.order-header strong{
	text-decoration:underline;
	color:$main-color;
	cursor:pointer;
}
.order-header strong:hover{
	color:$main-color * 1.2;
}

.order-content-main{
	margin:10px;
	padding-bottom:10px;
	border-bottom:1px dotted $line-color;
}
.order-content-main-item{
	display:inline-block;
	vertical-align:top;
}
.order-content-main-item:last-child{
	text-align:right;
}
.order-content-main-item__address>div{
	display:inline-block;
	vertical-align:top;
}
.order-content-main-item__title{
	color:$other-font-color;
}
.order-content-items{
	margin:10px;
}
.order-content-items>div{
	display:inline-block;
	vertical-align:middle;
}
.order-content-items>div span{
	color:$error-color;
}

.table-header-item .el-button{
    width:100px;
    margin-left: 10px;
}

.order-icon{
	color:$main-color;
	font-size:20px;
	position:relative;
	top:3px;
	margin-right:6px;
}
</style>