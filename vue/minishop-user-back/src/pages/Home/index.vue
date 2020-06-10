<template>
<div>
	<div class="dashboard-contain">
		<div class="dashboard-1">
			<div class="dashboard-title">
				<strong>经营数据</strong>
			</div>
			<div class="dashboard-1-data">
				<div>
					<div class="data-card-title-1"><strong style="color:#E6A23C">{{order.processing_count}}</strong></div>
					<div class="data-card-title-2">待配送订单</div>
					<div class="data-card-title-3" style="height:34px"><span style="color:#409EFF;cursor: pointer;" @click="toOrder('processing')">查看</span></div>
				</div>
				<div>
					<div class="data-card-title-1">¥ {{order.day_new_order_suc_amount}}</div>
					<div class="data-card-title-2">今日总金额</div>
					<div class="data-card-title-3">
						<div>昨日总金额: </div>
						<div><strong>{{order.yesterday_order_suc_amount}}</strong></div>
					</div>
				</div>
				<div>
					<div class="data-card-title-1">{{order.day_new_order_count}}</div>
					<div class="data-card-title-2">今日订单数</div>
					<div class="data-card-title-3">
						<div>昨日订单数: </div>
						<div><strong>{{order.yesterday_order_count}}</strong></div>
					</div>
				</div>
				<div>
					<div class="data-card-title-1">{{customer.today_customer_count}}</div>
					<div class="data-card-title-2">今日新顾客</div>
					<div class="data-card-title-3">
						<div>昨日新顾客: </div>
						<div><strong>{{customer.yesterday_customer_count}}</strong></div>
					</div>
				</div>
				<div>
					<div class="data-card-title-1">{{order.refunding_count}}</div>
					<div class="data-card-title-2">退款中</div>
					<div class="data-card-title-3" style="height:34px"><span style="color:#409EFF;cursor: pointer;" @click="toOrder('refunding')">查看</span></div>
				</div>
			</div>
		</div>
		<div class="dashboard-2">
			<div class="order-line">
				<div class="dashboard-title">
					<strong>实时概况</strong>
				</div>
				<div>
					<muti-line id="orderLineCircle" className="orderLineCircleClass" :Show="lineShow" :Height="300" :Values = "order_line"></muti-line>
				</div>
			</div>
			<div class="image-info">
				<div class="dashboard-title">
					<strong>图片空间</strong>
				</div>
				<div style="padding-left:20px">
					<div>
						<water-liquid id="ImageCircle" className="ImageCircleClass" :Show="waterShow" :Height="200" :Value="image.bytes_percent"></water-liquid>
					</div>
					<div style="padding:0 0 48px 10px">
						<div class="data-card-title-3">
							已使用：{{image.bytes_value}}
						</div>
						<div class="data-card-title-3">
							总大小：<span>{{ $store.getters.shop_img_limit }}</span> GB
						</div>
						<div class="data-card-title-3">
							图片数量：{{image.count}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ad-contain">
		<div class="web-title-container">
			<div class="web-title"><strong>{{ $store.getters.shop_name }}</strong></div>
			<div class="web-url">{{ $store.getters.shop_url + '.' + $store.getters.shop_host }}</div>
		</div>
		<div class="web-service">
			<div class="service-title">联系客服</div>
			<div class="service-info">
				<div>
					<img v-if="service.qrcode" :src="service.qrcode" />
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import { get_static_data } from "@/api/static"
import { get_config } from '@/api/sys'
import  WaterLiquid  from "@/components/Echarts/WaterLiquid"
import  MutiLine  from "@/components/Echarts/MutiLine"
export default {
	components:{
		WaterLiquid,
		MutiLine
	},
	data(){
		return {
			order:{
				processing_count:0,
				refunding_count:0,
				day_new_order_count:0,
				day_new_order_suc_amount:0,
				yesterday_order_count:0,
				yesterday_order_amount:0,
				yesterday_order_suc_amount:0,
				yesterday_order_rate:0,
			},
			customer:{
				today_customer_count:0,
				yesterday_customer_count:0,
				week_customer_count:0
			},
			image:{
				bytes:0,
				bytes_value:"",
				count:0,
				bytes_percent:0
			},
			order_line:{
				today:[],
				yesterday:[]
			},
			waterShow:false,
			lineShow:false,
			certify_type:'-',
			service:{
				qrcode:'',
			},
			upVisible:false,
			upType:'',
			upTitle:{
				upgrade:'升级',
				long:'续费'
			},
			upLoading:false,
			shop_levels:[],
			variants:[],
			payments:[],
			shop_order:{
				level:'',
				payment:'',
				variant:''
			},
			toHighest:false,
			btnLoading:false,
			tipShow:false,
			order_no:'',
			validLoading:false
		}
	},
	methods:{
		toOrder(status){
			this.$router.push({name:"OrderList",query:{status:status}})
		},
		getOrder(){
			get_static_data("order").then(r=>{
				let _data = r.data.body
				this.order = {
					processing_count:_data.processing_count * 1.00,
					refunding_count:_data.refunding_count * 1.00,
					day_new_order_count:_data.day_new_order_count * 1.00,
					day_new_order_suc_amount:_data.day_new_order_suc_amount * 1.00,
					yesterday_order_count:_data.yesterday_order_count * 1.00,
					yesterday_order_amount:_data.yesterday_order_amount * 1.00,
					yesterday_order_suc_amount:_data.yesterday_order_suc_amount * 1.00,
					yesterday_order_rate:_data.yesterday_order_rate * 1.00,
				}
			})
		},
		getCustomer(){
			get_static_data("customer").then(r=>{
				let _data = r.data.body
				this.customer = {
					today_customer_count:_data.today_customer_count  * 1.00,
					yesterday_customer_count:_data.yesterday_customer_count  * 1.00,
					week_customer_count:_data.week_customer_count  * 1.00
				}
			})
		},
		getImage(){
			get_static_data("image").then(r=>{
				let _data = r.data.body
				this.image = {
					bytes:_data.bytes * 1.00,
					bytes_value:_data.bytes_value,
					count:_data.count * 1.00,
					bytes_percent:_data.bytes_percent * 1.00
				}
				this.waterShow = true
			})
		},
		getOrderLine(){
			get_static_data("order_line").then(r=>{
				let _data = r.data.body
				this.order_line = {
					today:_data.today,
					yesterday:_data.yesterday
				}
				this.lineShow = true
			})
		},
		getService(){
			get_config().then(r=>{
				this.service.qrcode = r.data.service_qr_code
			})
		}
	},
	created(){
		this.getOrder()
		this.getImage()
		this.getCustomer()
		this.getOrderLine()
		this.getService()
	}
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';

.dashboard-contain {
	display: inline-block;
	width : 75%;
	vertical-align: top;
	margin-right: 10px;
}
.ad-contain {
	display: inline-block;
	width: calc(25% - 32px);
	vertical-align: top;
	padding:10px;
	border: 1px solid $line-color;
}
.dashboard-1 {
	width: 100%;
	height: 200px;
	border:1px solid $line-color;
	background-color:#fff;
	border-radius:5px;
}
.dashboard-title{
	height:30px;
	line-height: 20px;
	padding:10px 0 0 15px;
	border-bottom:1px solid $line-color;
	margin-bottom: 15px;
	font-size: $large-font-size;
}
.data-card-title-1{
	font-size: 20px;
	font-weight: 600;
	color:$main-color;
	margin-bottom: 8px;
}
.data-card-title-2{
	font-size: $middle-font-size;
	color:$sub-font-color;
	padding-bottom:5px;
	margin-bottom: 5px;
	border-bottom: 1px solid $line-color;
}
.data-card-title-3{
	font-size: $small-font-size;
	color:$other-font-color;
}
.dashboard-1-data {
	text-align: center;
}
.dashboard-1 .dashboard-1-data>div {
	display: inline-block;
	width: calc(20% - 40px);
	min-height:80px;
	margin-right: 15px;
	padding: 10px;
	border: 1px solid $line-color;
	border-radius: 5px;
	color:$main-font-color;
	font-size: $large-font-size;
	vertical-align: top;
}
.dashboard-2 {
	margin-top: 10px;
}
.dashboard-2>div {
	display: inline-block;
}
.dashboard-2>div:first-child{
	margin-right: 8px;
}
.dashboard-2 .image-info {
	width: 30%;
	height:100%;
	padding:10px;
	vertical-align: top;
	border:1px solid $line-color;
	background-color:#fff;
	border-radius:5px;
}

.dashboard-2 .order-line {
	width: calc(70% - 52px);
	height:100%;
	padding: 10px;
	vertical-align: top;
	border:1px solid $line-color;
	background-color:#fff;
	border-radius:5px;
}
.ad-contain{
	background-color:#fff;
	border-radius:5px;
}

.web-title-container{
	margin:5px 0 20px;
	padding-bottom:15px;
	border-bottom:1px solid $line-color * 0.95;
}
.web-title{
	font-size:$large-font-size * 1.5;
	line-height:30px;
	color:$sub-font-color;
	margin-bottom:5px;
	word-break:break-all;
}
.web-url{
	color:$other-font-color;
	cursor:pointer;
	word-break:break-all;
}
.web-url:hover{
	text-decoration:underline;
}
.web-item{
	margin-bottom:10px;
}
.web-item>div{
	display:inline-block;
	vertical-align:top;
}
.web-item>div:first-child{
	width:70px;
	color:$sub-font-color;
}
.web-item>div:last-child{
	width:calc(100% - 70px);
	word-break:break-all;
}
.web-item__level{
	color:$success-color;
}
.web-item__level i{
	color:#fff;
	background-color:$success-color;
	border-radius:50%;
	padding:1px 2px 2px;
	margin-right:3px;
}
.inline-button{
	margin-left:9px;
	font-size:14px;
	color:$main-color;
	word-break:keep-all;
	cursor:pointer;
}
.service-title{
	font-size:$large-font-size;
	margin:40px 0 5px;
	padding-bottom:10px;
	border-bottom:1px solid $line-color;
}
.service-info>div:first-child{
	width:158px;
	height:158px;
	border:1px solid $line-color;
	border-radius:5px;
	margin:20px auto 10px;
}
.service-info>div:first-child img{
	height:100%;
	width:100%;
	object-fit:scale-down;
}
.service-info>div:last-child{
	color:$sub-font-color;
	text-align:center;
	margin-bottom:10px;
}

.item-title{
	font-weight:500;
	margin-bottom:10px;
	margin-top:30px;
}
.item-title:first-child{
	margin-top:0;
}
.item-info{
	color:$main-color;
	font-weight:bold;
}
.item-tip{
	font-size:12px;
	margin-top:10px;
	color:$success-color;
}
.item-total{
	text-align:right;
	margin-top:10px;
	color:$main-color;
}
.item-total strong{
	font-size:15px;
}
</style>