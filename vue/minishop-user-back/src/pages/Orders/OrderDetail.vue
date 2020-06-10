<template>
<div>
	<div class="order-top-button">
		<div style="width:80px">
			<el-button size="small" @click='$router.go(-1)'>返回</el-button>
		</div>
		<div style="width:calc(100% - 280px);font-weight:500" :class="{'is-refund':refund_reason}">
			<span>{{ status !== 'closed' ? status_value : (refund_status_value ? refund_status_value : status_value) }}</span>
			<span v-if="refund_reason" style="margin-left:15px">{{ refund_reason }}</span>
		</div>
		<div style="width:200px">
			<el-button size="small" v-if="status === 'pending'" type="info" @click='closedOrder'>关闭订单</el-button>
			<el-button v-if="(status === 'partial' || status === 'processing') && !refund_status" type='primary' size="small" @click="toSend">发货</el-button>
			<el-button size="small" v-if="['processing','sent','partial','success'].indexOf(status) !== -1 && !refund_status" type='primary' @click="refundOrder('refunded')">退单</el-button>
			<el-button size="small" v-if="refund_status ==='refunding'" type="primary" @click="refundOrder('refunded')">同意退单</el-button>
			<el-button size="small" v-if="refund_status ==='refunding'" type="danger" @click="refundOrder('refund_refuse')">拒绝退单</el-button>
			<el-button size="small" v-if="status !== 'pending' && refund_status !== 'refunding'" :disabled="!(payment && payment.payment_method !== 'cash' && payment.pay_at)" type='primary' @click="toReturnMoney">退款</el-button>
		</div>
	</div>
	<div class="order-top" style="width:calc(100% - 2px)">
		<el-steps :active="status_active" :finish-status="status === 'cancel' || status === 'closed' || status === 'refunded' ? 'wait' : 'success'" simple>
			<el-step v-for = "(status_process,index) in status_processes" :key="index" :title="status_process.value"></el-step>
		</el-steps>
	</div>
	<div class="order-contain">
		<div class="order-main">
			<div v-if="suborders.length === 1">
				<div class="order-block-title">
					<strong>商品详情</strong>
				</div>
				<div class="order_items" v-for="(item , index) in items" :key='index'>
					<div class="order_items_img">
						<el-image :src="item.thumbnail" style="width: 60px;height: 60px" fit="contain"></el-image>
					</div>
					<div class="order_items_info" style="width:calc(100% - 200px)">
						<div>{{item.product_title}}</div>
						<div class="sub-title">规格：{{item.variant_title}}</div>
					</div>
					<div class="order_items_quantity" style="width:120px">
						<div>¥ <strong>{{ item.price }}</strong> x <strong>{{item.quantity}}</strong></div>
						<div>共 ¥ {{item.total}}</div>
					</div>
				</div>
				<div class="order-price-list">
					<div>
						<div>商品总计：</div>
						<div>¥ {{items_amount}}</div>
					</div>
					<div>
						<div>运费总计：</div>
						<div>¥ {{shipments_amount}}</div>
					</div>
					<div>
						<div>优惠总计：</div>
						<div>-¥ {{discounts_amount}}</div>
					</div>
					<div>
						<div>订单总计：</div>
						<div v-if="ori_amount == amount" class="order-price">¥ <strong style="font-size:16px">{{amount}}</strong></div>
						<div v-else>
							<div style="text-decoration: line-through;">¥ {{ori_amount}}</div>  
							<div class="order-price">¥ <strong style="font-size:16px">{{amount}}</strong></div>
						</div>
					</div>
					<div v-if="status == 'pending'">
						<el-button style="text-align:right;padding:5px 10px;" type ='text' @click="updatePriceOrder">修改价格</el-button>
					</div>
				</div>
			</div>
			<div v-if="suborders.length !== 1">
				<div class="order-block-title">
					<strong>商品详情</strong>
				</div>
				<div v-for="(sub,index) in suborders" :key="index" style="padding:5px;background-color:#fff;">
					<div class="order_title">
						<span>物流 {{ index + 1 }}</span>
						<span style="margin-left:15px">{{ sub.shipment_name }}</span>
						<span style="margin-left:15px">¥ {{ sub.shipments_amount }}</span>
					</div>
					<div class="order_items" v-for="(item,Index) in sub.items" :key="'s'+Index">
						<div class="order_items_img"><el-image :src="item.thumbnail" style="height:60px;width:60px"></el-image></div>
						<div class="order_items_info" style="width:calc(100% - 200px)">
							<div>{{item.product_title}}</div>
							<div class="sub-title">规格：{{item.variant_title}}</div>
						</div>
						<div class="order_items_quantity" style="width:120px">
							<div>¥ <strong>{{ item.price }}</strong> x <strong>{{item.quantity}}</strong></div>
							<div>共 ¥ {{item.total}}</div>
						</div>
					</div>
				</div>
				<div class="order-price-list">
					<div>
						<div>商品总计：</div>
						<div>¥ {{items_amount}}</div>
					</div>
					<div>
						<div>运费总计：</div>
						<div>¥ {{shipments_amount}}</div>
					</div>
					<div>
						<div>优惠总计：</div>
						<div>-¥ {{discounts_amount}}</div>
					</div>
					<div>
						<div>订单总计：</div>
						<div v-if="ori_amount == amount" class="order-price">¥ <strong style="font-size:16px">{{amount}}</strong></div>
						<div v-else>
							<div style="text-decoration: line-through;">¥ {{ori_amount}}</div>  
							<div class="order-price">¥ <strong style="font-size:16px">{{amount}}</strong></div>
						</div>
					</div>
					<div v-if="status == 'pending'">
						<el-button style="text-align:right;padding:5px 10px;" type ='text' @click="updatePriceOrder">修改价格</el-button>
					</div>
				</div>
			</div>
			<div class="order-remark">
				<div class="order-block-title"><strong>客户备注</strong></div>
				<div style="padding:10px;background-color:#fff;min-height:50px;border-radius:0 0 5px 5px;">{{ remark ? remark : '暂无' }}</div>
			</div>
			<div>
				<div class="order-block-title">
					<strong>员工备注</strong>
					<span class="order-block-button" @click="addTips">添加备注</span>
				</div>
				<div v-if="tips.length === 0" style="padding:10px;background-color:#fff;min-height:50px;border-radius:0 0 5px 5px;">暂无员工备注</div>
				<el-steps direction="vertical" v-if="tips.length !== 0" style="border-radius:0 0 5px 5px;background-color:#fff;padding:10px" :space="80">
					<el-step v-for="(item,index) in tips" :key="index" status="finish">
						<div slot="description">{{ item.created_at }}</div>
						<div slot="title">{{ item.tip }}</div>
					</el-step>
				</el-steps>
			</div>
		</div>
		<div class="order-subside">
			<div class='order-subside-card'>
				<div class="order-block-title"><strong>订单信息</strong></div>
				<div class="order-block-content">
					<div>
						<div>订单编号：</div>
						<div class="user-name">{{no}}</div>
					</div>
					<div>
						<div>支付方式：</div>
						<div>{{ payment ? payment.payment_method_value : '-' }}</div>
					</div>
					<div>
						<div>支付时间：</div>
						<div>{{ payment ? payment.pay_at : '-' }}</div>
					</div>
				</div>
			</div>
			<div class='order-subside-card'>
				<div class="order-block-title"><strong>客户信息</strong></div>
				<div class="order-block-content">
					<div>
						<div>用户名：</div>
						<div class="user-name">{{customer.username}}</div>
					</div>
					<div>
						<div>手机号：</div>
						<div>{{customer.mobile}}</div>
					</div>
				</div>
			</div>
			<div class='order-subside-card'>
				<div class="order-block-title"><strong>配送信息</strong></div>
				<div class="order-block-content">
					<div>
						<div>姓名：</div>
						<div class="user-name">{{address.name}}</div>
					</div>
					<div>
						<div>地址：</div>
						<div>{{address.address}}</div>
					</div>
					<div>
						<div>电话：</div>
						<div>{{address.mobile}}</div>
					</div>
					<div>
						<div>邮编：</div>
						<div>{{address.zip}}</div>
					</div>
				</div>
			</div>
			<div class='order-subside-card'>
				<div class="order-block-title"><strong>配送列表</strong></div>
				<div v-if='shipments.length !== 0' style="padding:10px;background-color:#fff">
					<el-collapse>
						<el-collapse-item v-for="(shipment,index) in shipments" :key="index" :title="'运单 '+ (index + 1) + ' : ' + shipment.shipment_no" :name="index">
							<div>
								<div style="display:inline-block;width:70px">运输方式：</div>
								<div style="display:inline-block">{{shipment.shipment_company}}</div>
							</div>
							<div>
								<div style="display:inline-block;width:70px">运单号：</div>
								<div style="display:inline-block">{{shipment.shipment_no}}</div>
							</div>
							<div>
								<ul class="shipment-item">
									<li v-for="(item , index) in shipment.items" :key="index">
										<span><strong>{{item.product_title}}</strong></span>
										<span style="margin-left:10px">规格：{{item.variant_title}}</span>
										<span style="margin-left:20px"> x {{item.quantity}}</span>
									</li>
								</ul>
							</div>
						</el-collapse-item>
					</el-collapse>
				</div>
				<div v-else style="padding:10px;background-color:#fff;min-height:50px">暂无配送信息</div>
			</div>
		</div>
	</div>
	<order-send
		:Visible="sendDialogShow"
		:pendItems="pend_items"
		@close="sendDialogShow = false"
		@confirm="sendConfirm">
	</order-send>
	<order-refund
		:Visible="refundVisible"
		@close="refundVisible = false"
		@confirm="refundConfirm">
	</order-refund>
</div>
</template>

<script>
	import {get_order, status_update_order, update_order, create_order_tip, get_order_tip } from "@/api/order"
	import OrderRefund from './components/OrderRefund'
	import OrderSend from './components/OrderSend'
	export default {
		components:{
			OrderRefund,
			OrderSend
		},
		data(){
			return {
				id:null,
				no:null,
				customer:{},
				suborders:[],
				items:[],
				payment:null,
				address:{},
				shipments:[],
				items_amount:null,
				discounts_amount:null,
				shipments_amount:null,
				amount:null,
				ori_amount:null,

				status:null,
				status_value:null,

				refund_at:null,
				refund_status:null,
				refund_status_value:null,
				refund_reason:null,
				
				remark:null,
				tips:[],
				send_at:null,
				pay_at:null,
				success_at:null,
				created_at:null,
				status_active:0,
				status_processes:[],

				sendDialogShow:false,
				pend_items:[],
				btnLoading:false,
				refundVisible:false
			}
		},
		beforeRouteLeave(to,from,next){
			if(to.name === 'OrderList') to.meta.keepAlive = true
				else to.meta.keepAlive = false
			next()
		},
		methods:{
			getData(){
				this.$loading()
				get_order(this.$route.query.id).then(r=>{
					this.$loading().close()
					let _data = r.data.body
					this.id = this.$route.query.id
					this.no = _data.no
					this.customer = _data.customer
					this.suborders = _data.suborders
					this.items = []
					this.suborders.map(v=>{
						v.items.map(t=>{
							this.items.push(t)
						})
					})
					this.payment = _data.payment
					this.address = _data.address
					this.shipments = _data.shipments
					this.items_amount = _data.items_amount
					this.discounts_amount = _data.discounts_amount
					this.shipments_amount = _data.shipments_amount
					this.amount = _data.amount
					this.ori_amount = _data.ori_amount
					this.status = _data.status
					this.status_value = _data.status_value
					this.refund_status = _data.refund_status
					this.refund_status_value = _data.refund_status_value
					this.refund_reason = _data.refund_reason
					this.refund_at = _data.refund_at
					this.remark = _data.remark
					this.tips = _data.tips
					this.send_at = _data.send_at
					this.pay_at = _data.pay_at
					this.success_at = _data.success_at
					this.created_at = _data.created_at
					if(_data.refund_status){
						switch(_data.refund_status){
							case 'refunding':
							this.status_active = 3
							this.status_processes = [
								{value:'订单创建'},
								{value:'订单支付'},
								{value:'退单申请'},
								{value:'订单关闭'},
							]
							break
							case 'refunded':
							this.status_active = 4
							this.status_processes = [
								{value:'订单创建'},
								{value:'订单支付'},
								{value:'退单申请'},
								{value:'订单关闭'},
							]
							break
						}
						return
					}
					switch(_data.status){
						case "pending":
						this.status_active = 1
						this.status_processes = [
							{value:'订单创建'},
							{value:'订单支付'},
							{value:'配送中'},
							{value:'已完成'},
						]
						break
						case "processing":
						this.status_active = 2
						this.status_processes = [
							{value:'订单创建'},
							{value:'订单支付'},
							{value:'配送中'},
							{value:'已完成'},
						]
						break
						case "partial":
						case "sent":
						this.status_active = 3
						this.status_processes = [
							{value:'订单创建'},
							{value:'订单支付'},
							{value:'配送中'},
							{value:'已完成'},
						]
						break
						case "success":
						this.status_active = 4
						this.status_processes = [
							{value:'订单创建'},
							{value:'订单支付'},
							{value:'配送中'},
							{value:'已完成'},
						]
						break
						case "cancel":
						this.status_active = 2
						this.status_processes = [
							{value:'订单创建'},
							{value:'已取消'},
						]
						break;
						case "closed":
						this.status_active = 2
						this.status_processes = [
							{value:'订单创建'},
							{value:'已关闭'},
						]
						break;
					}
				}).catch(()=>{
					this.$message.warning('参数错误')
					this.$router.push({
						name:'OrderList'
					})
					this.$loading().close()
				})
			},
			refundOrder(status){
				this.$prompt('','',{
					title:'提示',
					message:'请输入' + (status === 'refunded' ? '退单' : '拒绝退单') + '理由',
					confirmButtonText:"确定",
					cancelButtonText:"取消",
					type:"warning"
				}).then(({value})=>{
					status_update_order(this.id,{status:status,reason:value}).then(()=>{
						this.$message.success(status === 'refunded' ? '已成功退单' : '退单已驳回')
						this.getData()
					})
				}).catch(()=>{})
			},
			closedOrder(){
				this.$confirm('此操作将取消该订单, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					status_update_order(this.id,{status:"cancel"}).then(()=>{
						this.$message.success('关闭成功')
						this.getData()
					})
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消'
					})
				})
			},
			updatePriceOrder(){
				this.$prompt('请输入价格',"提示",{
					confirmButtonText:"确定",
					cancelButtonText:"取消",
					type:"warning"
				}).then(({value})=>{
					update_order(this.id,{amount:value}).then(()=>{
						this.$message.success('修改成功')
						this.getData()
					})
				}).catch(()=>{
					this.$message({
						type: 'info',
						message: '已取消'
					})
				})
			},
			toSend(){
				this.selected_items = []
				this.pend_items = []
				let _items = []
				this.shipments.map(v=>{
					v.items.map(t=>{
						_items.push(t)
					})
				})
				this.items.map(v=>{
					let repeat_item = _items.filter((val)=>{ return val.variant_id === v.variant_id })
					if(repeat_item.length === 0){
						let _item = {
							product_title:v.product_title,
							variant_title:v.variant_title,
							variant_id:v.variant_id,
							amount:v.quantity,
							quantity:v.quantity
						}
						this.pend_items.push(_item)
						this.selected_items.push(_item)
					}else if(repeat_item[0].quantity !== v.quantity){
						let _item = {
							product_title:v.product_title,
							variant_title:v.variant_title,
							variant_id:v.variant_id,
							amount:v.quantity - repeat_item[0].quantity,
							quantity:v.quantity - repeat_item[0].quantity
						}
						this.pend_items.push(_item)
						this.selected_items.push(_item)
					}
				})
				this.sendDialogShow = true
			},
			sendConfirm(){
				this.sendDialogShow = false
				this.getData()
			},
			toReturnMoney(){
				if(this.payment.payment_method === 'cash'){
					this.$message.warning('货到付款不支持线上退款')
					return
				}
				this.refundVisible = true
			},
			refundConfirm(){
				this.refundVisible = false
				this.getData()
			},
			addTips(){
				this.$prompt('填写订单备注','添加备注',{
					confirmButtonText:'提交',
					cancelButtonText:'取消'
				}).then(({value})=>{
					create_order_tip(this.$route.query.id,{tip:value}).then(()=>{
						this.$message.success('添加成功')
						setTimeout(()=>{
							this.$loading()
							get_order_tip(this.$route.query.id).then(r=>{
								this.tips = r.data.body
								this.$loading().close()
							}).catch(()=>{
								this.$loading().close()
							})
						},500)
					})
				})
			}
		},
		created(){
			if(this.$route.query.id){
				this.getData()
			}else{
				this.$message.warning('参数错误')
				this.$router.push({
					name:'OrderList'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
@import "@/assets/style/base.scss";
.order-top-button{
	margin-bottom: 5px;
	padding:10px;
	background-color:#fff;
	border-radius:5px;
	border:1px solid $line-color * 0.9;
}
.order-top-button>div{
	display:inline-block;
	vertical-align:middle;
}
.order-top-button>div.is-refund{
	color:$error-color;
}
.order-top-button>div:last-child{
	text-align:right;
}
.order-top {
	height: 47px;
	border: 1px solid $line-color * 0.9;
	border-radius: 3px;
	margin-bottom: 5px;
}
.order-main {
	display: inline-block;
	min-height:300px;
	width:calc(100% - 275px);
	margin-right:5px;
	vertical-align: top;
}
.order-main>div {
	border:1px solid $line-color * 0.9;
	border-radius:5px;
	margin-bottom:5px;
}
.order-subside {
	display: inline-block;
	width:270px;
	vertical-align: top;
}
.order-subside .order-subside-card{
	margin-bottom: 5px;
}

.order_title{
	background-color:$light-back-main * 1.03;
	padding:5px;
	color:$main-color;
	font-weight:500;
}
.order_items{
	padding:10px;
	background-color:#fff;
	border-bottom:1px dotted $line-color * 0.9;
}
.order_items .order_items_img {
	display: inline-block;
	text-align:center;
	width:65px;
	height:65px;
	vertical-align: middle;
}

.order_items .order_items_info {
	margin-left: 15px;
	display: inline-block;
	vertical-align: middle;
}
.order_items_info .sub-title{
	color:$sub-font-color * 1.1;
}
.order_items .order_items_quantity {
	display: inline-block;
	vertical-align: middle;
	text-align:right;
}
.order-price-list{
	padding:10px 0;
	background-color:#fff;
	border-radius:0 0 5px 5px;
}
.order-price-list>div{
	text-align: right;	
}
.order-price-list>div>div{
	width: 80px;
	display:inline-block;
	margin-right: 10px;
	vertical-align: top;
}
.order-price-list>div>div:first-child{
	color:$sub-font-color;
}

.order-subside-card{
	border:1px solid $line-color;
	border-radius:5px;
	background-color:#fff;
}
.order-block-title{
	line-height:40px;
	padding:0 10px;
	background-color:$background-color * 0.98;
	border-bottom:1px solid $line-color * 0.9;
	border-radius:5px 5px 0 0;
}
.order-block-content{
	padding:0 10px;
}
.order-block-content>div{
	padding:5px 0;
}
.order-block-content>div>div{
	display:inline-block;
	vertical-align:top;
}
.order-block-content>div>div:first-child{
	width:80px;
}
.order-block-content>div>div:last-child{
	width:calc(100% - 80px);
}

.user-name{
	color:$main-color;
	font-weight:500;
	cursor:pointer;
}
.order-price{
	color:$main-color;
}

.order-block-button{
	color:$main-color;
	float:right;
	cursor:pointer;
	font-weight:500;
}
.order-block-button:hover{
	color:$main-color * 1.3;
}

.shipment-item{
	margin:10px 0;
	padding:0;
	list-style:none;
}
</style>

<style lang="scss">
@import '@/assets/style/base.scss';
.order-subside-card .el-collapse-item__content{
	padding-bottom:10px;
}
.order-subside-card .el-collapse-item__header{
	color:$main-color;
}
</style>