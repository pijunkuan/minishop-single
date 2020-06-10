<template>
<div>
	<page-loading :loading="loading"></page-loading>
	<div style="overflow-y:scroll" :style="{height:height + 'px'}">
		<div class="checkout-address">
			<div class="checkout-address-icon">
				<shop-icon name="ditu" size="small"></shop-icon>
			</div>
			<div v-if="address" class="checkout-address-info">
				<div>
					<div><strong>收件人：</strong></div>
					<div>{{ address.name }}</div>
				</div>
				<div>
					<div><strong>联系方式：</strong></div>
					<div>{{ address.mobile }}</div>
				</div>
				<div>
					<div><strong>收货地址：</strong></div>
					<div>{{ address.province + ' ' + address.city + ' ' + address.district + ' ' + address.detail }}</div>
				</div>
			</div>
			<div class="checkout-address-button" :class="{'has-address':address}">
				<span v-if="address" @click="toEdit">修改地址</span>
				<span v-if="address" @click="toChange">更换地址</span>
				<span @click="toAdd">新增地址</span>
			</div>
		</div>
		<div class="checkout-item-list">
			<div class="checkout-item-list-item" v-for="(item,index) in items" :key="index">
				<div>
					<shop-image :src="item.img_url" :width="100" type="stretch" rounded>
						<div slot="error" class="item-image-placeholder"></div>
						<div slot="placeholder" class="item-image-placeholder"></div>
					</shop-image>
				</div>
				<div>
					<div class="checkout-item-list-item__title">{{ item.product_title }} </div>
					<div class="checkout-item-list-item__subtitle">{{ item.variant_title }}</div>
					<div class="checkout-item-list-item__price">¥ <strong>{{ item.price }}</strong></div>
					<div class="checkout-item-list-item__amount">x {{ item.quantity }}</div>
				</div>
			</div>
		</div>
		<div class="remark-info">
			<div>留言</div>
			<shop-input v-model="remark" size="small" placeholder="给我们留言" :rounded="false"></shop-input>
		</div>
		<div class="detail-info">
			<div>
				<div>商品费用：</div>
				<div>¥ <strong>{{ total.items_amount }}</strong></div>
			</div>
			<div>
				<div>运输费用：</div>
				<div>¥ <strong>{{ total.shipments_amount }}</strong></div>
			</div>
			<div>
				<div>优惠费用：</div>
				<div>¥ <strong>{{ total.discounts_amount }}</strong></div>
			</div>
		</div>
	</div>
	<div class="checkout-footer">
		<div>
			总计：<span>¥ <strong>{{ total.amount ? total.amount : '-' }}</strong></span>
		</div>
		<div>
			<shop-button :rounded="false" :disabled="btnLoading" @click="createOrder">提交订单</shop-button>
		</div>
	</div>
	
	<shop-popup :show="addShow" full>
        <div slot="title">{{ editType === 'edit' ? '编辑地址' : '新增地址' }}</div>
        <div slot="content" class="add-popup-content">
            <div>
                <div>收件人</div>
                <shop-input v-model="editAddress.name" :rounded="false"></shop-input>
            </div>
            <div>
                <div>联系方式</div>
                <shop-input v-model="editAddress.mobile" :rounded="false"></shop-input>
            </div>
            <div>
                <div>省市区</div>
                <shop-dispicker :value="editAddress.dispicker" @change="selectDispicker"></shop-dispicker>
            </div>
            <div>
                <div>邮政编码</div>
                <shop-input v-model="editAddress.zip" :rounded="false"></shop-input>
            </div>
            <div>
                <div>详细地址</div>
                <shop-input v-model="editAddress.detail" :rounded="false"></shop-input>
            </div>
        </div>
        <div slot="button">
            <div class="add-popup-button"><shop-button type="border" :rounded="false" @click="closeAdd">关闭</shop-button></div>
            <div class="add-popup-button"><shop-button :rounded="false" @click="confirmAdd">确认</shop-button></div>
        </div>
    </shop-popup>
    <shop-popup :show="selectShow" full>
        <div slot="title">地址簿</div>
        <div slot="content" class="add-popup-content">
            <ul
				class="address-list"
				:style="{height:popheight + 'px'}"
				v-infinite-scroll="getAddress"
				infinite-scroll-disabled="adisabled"
				infinite-scroll-distance="10"
				infinite-scroll-immediate-check>
				<li v-for="(address,index) in addresses" :key="index">
					<shop-radio class="address-radio" v-model="selectAddress" :label="address"><div></div></shop-radio>
					<div class="address-content">
						<div>
							<div>收件人：</div>
							<div>{{ address.name }}</div>
						</div>
						<div>
							<div>联系方式：</div>
							<div>{{ address.mobile }}</div>
						</div>
						<div>
							<div>收件地址：</div>
							<div>{{ address.province + ' ' + address.city + ' ' + address.district + ' ' + address.detail }}</div>
						</div>
					</div>
				</li>
				<div v-if="!nomore" class="address-placeholder">加载中...</div>
				<div v-if="nomore" class="address-placeholder">没有更多了</div>
			</ul>
        </div>
        <div slot="button">
            <div class="add-popup-button"><shop-button type="border" :rounded="false" @click="closeSelect">关闭</shop-button></div>
            <div class="add-popup-button"><shop-button :rounded="false" @click="confirmSelect">确认</shop-button></div>
        </div>
    </shop-popup>
</div>
</template>

<script>
import { get_cart_cache } from '@/api/cart'
import { calc_order_total, create_order } from '@/api/order'
import { get_addresses, create_address, edit_address } from '@/api/address'
import { Toast } from 'mint-ui'
	export default{
		data(){
			return {
				loading:false,
				address:null,
				items:[],
				total:{
					items_amount:0,
					shipments_amount:0,
					discounts_amount:0,
					amount:0
				},
				height:0,
				remark:"",
				addShow:false,
				editType:'add',
				editAddress:{
					name:'',
					dispicker:[],
					address:'',
					mobile:'',
					zip:''
				},
				selectShow:false,
				selectAddress:{},
				adisabled:false,
				nomore:false,
				aloading:false,
				addresses:[],
				popheight:0,
				fetchQuery:{
					page:0,
					pageSize:15
				},
				btnLoading:false
			}
		},
		created(){
			this.getItems()
		},
		methods:{
			getItems(){
				this.loading = true
				get_cart_cache(this.$route.query.key).then(r=>{
					this.fetchQuery.page = 1
					get_addresses(this.fetchQuery).then(res=>{
						if(res.data.body.data.length !== 0){
							this.address = res.data.body.data[0]
						}
						let _data = {
							address:this.address,
							items:r.data.body.items
						}
						this.calcTotal(_data)
					})
				}).catch(()=>{
					this.loading = false
					Toast({
						message:'订单已超时',
						duration:1000
					})
					setTimeout(()=>{
						this.$router.push({name:'Cart',query:{from:'Checkout'}})
					},1000)
				})
			},
			calcTotal(data){
				calc_order_total(data).then(r=>{
					this.items = r.data.body.items
					this.total.amount = r.data.body.amount
					this.total.discounts_amount = r.data.body.discounts_amount
					this.total.shipments_amount = r.data.body.shipments_amount
					this.total.items_amount = r.data.body.items_amount
					this.loading = false
				}).catch(()=>{
					this.loading = false
				})
			},
			closeAdd(){
				this.addShow = false
				this.editAddress = {
					name:'',
					dispicker:[],
					address:'',
					detail:''
				}
			},
			toEdit(){
				this.addShow = true
				this.editAddress.id = this.address.id
				this.editAddress.name = this.address.name
				this.editAddress.mobile = this.address.mobile
				this.editAddress.detail = this.address.detail
				this.editAddress.zip = this.address.zip
				this.editAddress.dispicker.push(this.address.province)
				this.editAddress.dispicker.push(this.address.city)
				this.editAddress.dispicker.push(this.address.district)
				this.editType = 'edit'
			},
			toAdd(){
				this.addShow = true
				this.editType = 'add'
			},
			toChange(){
				this.selectShow = true
			},
			selectDispicker(val){
				this.editAddress.dispicker = val
			},
			getAddress(){
				this.fetchQuery.page += 1
				setTimeout(()=>{
					get_addresses(this.fetchQuery.page).then(r=>{
						if(r.data.body.data.length === 0){
							this.nomore = true
							this.adisabled = true
						}else{
							this.nomore = false
							this.adisabled = false
							this.addresses = this.addresses.concat(r.data.body.data)
						}
					}).catch(()=>{
						this.adisabled = true
						this.nomore = true
					})
				},200)
			},
			confirmAdd(){
				let _data = {
					name:this.editAddress.name,
					province:this.editAddress.dispicker[0],
					city:this.editAddress.dispicker[1],
					district:this.editAddress.dispicker[2],
					detail:this.editAddress.detail,
					mobile:this.editAddress.mobile,
					zip:this.editAddress.zip
				}
				let _renew = {
					address:_data,
					items:this.items
				}
				if(this.editType === 'edit'){
					edit_address(this.editAddress.id,_data).then(()=>{
						this.address = _data
						this.address['id'] = this.editAddress.id
						this.closeAdd()
						this.loading = true
						this.calcTotal(_renew)
					}).catch(e=>{
						Toast(e.response.data.message)
					})
				}else{
					create_address(_data).then(()=>{
						this.closeAdd()
						this.loading = true
						this.address = _data
						this.calcTotal(_renew)
					}).catch(e=>{
						Toast(e.response.data.message)
					})
				}
			},
			closeSelect(){
				this.fetchQuery.page = 0
				this.selectShow = false
				this.selectAddress = {}
			},
			confirmSelect(){
				this.address = this.selectAddress
				let _renew = {
					address:this.address,
					items:this.items
				}
				this.loading = true
				this.calcTotal(_renew)
				this.closeSelect()
			},
			createOrder(){
				if(!this.address){
					Toast('请添加地址再提交订单')
					return
				}
				if(this.btnLoading) return
				this.btnLoading = true
				let _data = {
					address:this.address,
					items:[],
					remark:this.remark
				}
				this.items.map(v=>{
					let item = {
						variant_id:v.variant_id,
						quantity:v.quantity
					}
					_data.items.push(item)
				})
				create_order(_data).then(r=>{
					this.$router.push({name:'Checkpay',query:{no:r.data.body.no}})
					this.btnLoading = false
				}).catch(()=>{
					this.btnLoading = false
				})
			}
		},
		mounted(){
			this.$nextTick(()=>{
				this.height = window.innerHeight - 94
				this.popheight = window.innerHeight - 85
			})
			window.onresize = () => {
				this.height = window.innerHeight - 94
				this.popheight = window.innerHeight - 85
			}
		}
	}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.checkout-address{
	margin-bottom:10px;
	padding:20px 5px;
	background-color:#fff;
}
.checkout-address>div{
	display:inline-block;
	vertical-align:middle;
}
.checkout-address-icon{
	width:40px;
	margin-right:10px;
	text-align:center;
}
.checkout-address-info{
	width:calc(100% - 50px);
	font-size:$middle-font-size;
	line-height:$middle-font-height;
	color:$main-font-color;
}
.checkout-address-info strong{
	color:$sub-font-color;
}
.checkout-address-info>div>div{
	display:inline-block;
}
.checkout-address-info>div>div:first-child{
	width:80px;
	vertical-align:top;
}
.checkout-address-info>div>div:last-child{
	width:calc(100% - 80px);
	vertical-align:top;
}
.checkout-address-button{
	font-size:$middle-font-size;
	@include base-color(1);
}
.checkout-address-button.has-address{
	margin-left:50px;
	margin-top:12px;
}
.checkout-address-button span{
	margin-right:15px;
}

.checkout-item-list{
	margin-bottom:10px;
	background-color:#fff;
}
.checkout-item-list-item{
	margin:0 8px;
	padding:10px 5px;
	border-bottom:1px solid $line-color;
}
.checkout-item-list-item:last-child{
	border-bottom:none;
}
.checkout-item-list-item>div{
	display:inline-block;
	vertical-align:middle;
}
.checkout-item-list-item>div:last-child{
	width:calc(100% - 110px);
	margin-left:10px;
}
.checkout-item-list-item__title{
	font-size:$middle-font-size;
	color:$main-font-color;
	line-height:$middle-font-height;
	max-height:$middle-font-height * 2;
	overflow:hidden;
}
.checkout-item-list-item__subtitle{
	font-size:$middle-font-size;
	color:$sub-font-color;
	line-height:$middle-font-height;
	height:$middle-font-height;
	overflow:hidden;
}
.checkout-item-list-item__price{
	font-size:$small-font-size;
	@include price-color(1);
}
.checkout-item-list-item__price strong{
	font-size:$normal-font-size;
}
.checkout-item-list-item__amount{
	font-size:$middle-font-size;
	color:$main-font-color;
	text-align:right;
}

.checkout-footer{
	position:fixed;
	bottom:0;
	left:0;
	right:0;
	background-color:#fff;
	border-top:1px solid $line-color;
	box-shadow:0 -1px 5px 2px rgba(0,0,0,0.05);
}
.checkout-footer>div{
	display:inline-block;
	width:50%;
}
.checkout-footer>div:first-child{
	width:calc(50% - 10px);
	padding:0 5px;
	font-size:$small-font-size;
}
.checkout-footer>div:first-child span{
	@include price-color(1);
}
.checkout-footer>div:first-child span strong{
	font-size:$large-font-size;
}

.remark-info{
	margin:10px 0;
	padding:10px;
	background-color:#fff;
	font-size:$middle-font-size;
	color:$sub-font-color;
}
.remark-info>div{
	display:inline-block;
}
.remark-info>div:first-child{
	width:40px;
}
.remark-info>div:last-child{
	width:calc(100% - 40px);
}
.detail-info{
	padding:5px 10px;
	margin-bottom:10px;
	background-color:#fff;
	font-size:$middle-font-size;
	color:$sub-font-color;
}
.detail-info>div{
	padding:5px 0;
}
.detail-info>div>div{
	display:inline-block;
}
.detail-info>div>div:first-child{
	width:80px;
}
.detail-info>div>div:last-child{
	width:calc(100% - 80px);
	@include price-color(1);
	font-size:$small-font-size;
	text-align:right;
}
.detail-info>div>div:last-child strong{
	font-size: $normal-font-size
}

.address-list{
	position:absolute;
	left:0;
	right:0;
    margin:0;
    padding:0;
    overflow-y:scroll;
    background-color:#fff;
}
.address-list li{
    padding:15px 0;
    margin:0 10px;
    border-bottom:1px solid $line-color;
}

.address-list li>div{
    display:inline-block;
    vertical-align:middle;
}
.address-checkbox,.address-radio{
    width:40px;
}
.address-content{
    font-size:$middle-font-size;
    width:calc(100% - 40px);
    color:$sub-font-color;
}
.address-content.has-width{
    width:calc(100% - 40px);
}
.address-content>div>div{
    display:inline-block;
    vertical-align:top;
}
.address-content>div>div:first-child{
    width:80px;
}
.address-content>div>div:last-child{
    width:calc(100% - 80px);
    color:$main-font-color;
}
.add-popup-button{
	width:50%;
	display:inline-block;
}
.address-placeholder{
	text-align:center;
	font-size:$small-font-size;
	line-height:30px;
	color:$other-font-color;
}

.add-popup-content{
	color:$sub-font-color;
	font-size:$middle-font-size;
}
.add-popup-content>div{
	margin:10px 0 20px;
}
.add-popup-content>div>div{
	margin-bottom:5px;
}


.item-image-placeholder{
	width:100px;
	height:100px;
	background-color:$line-color;
	border-radius:5px;
}
</style>