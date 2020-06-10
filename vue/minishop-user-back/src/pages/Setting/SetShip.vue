<template>
<div>
	<div class="shipment-header">
		<el-button type="primary" @click="createShipment" size="small">创建新模板</el-button>
	</div>
	<div v-loading="loading" class="shipment-list">
		<div v-for="(item,index) in shipments" :key="index" class="shipment-list-item">
			<div class="shipment-basic-info">
				<div style="width:calc(100% - 220px)">
					<div style="display:inline-block;min-width:100px;vertical-align:bottom">
						<div class="shipment-info-title" style="font-weight:500;">{{ item.shipment_name }}</div>
						<div v-if="item.need_cost" class="shipment-info-sub">{{ item.cost_type }}收费</div>
						<div v-else class="shipment-info-sub">包邮</div>
					</div>
					<div style="display:inline-block;vertical-align:bottom">
						<el-button v-if="!item.detailShow" size="mini" type="primary" plain @click="showDetail(item)">查看详情</el-button>
						<el-button v-if="item.detailShow" size="mini" type="primary" plain @click="closeDetail(item)">收起</el-button>
					</div>
				</div>
				<div style="width:220px">
					<el-button size="mini" type="primary" @click="editShip(item)">修改</el-button>
					<el-button size="mini" type="primary" @click="copyShip(item)">复制模版</el-button>
					<el-button size="mini" type="danger" @click="deleteShip(item)">删除</el-button>
				</div>
			</div>
			<transition name="shipment-detail">
				<div v-show="item.detailShow" v-loading="itemLoading" class="shipment-detail-table">
					<div class="shipment-detail-list">
						<div style="min-width:80px">运送方式</div>
						<div style="width:100%">地区</div>
						<div style="min-width:80px">首重/件</div>
						<div style="min-width:80px">运费</div>
						<div style="min-width:80px">续重/件</div>
						<div style="min-width:80px">运费</div>
					</div>
					<div class="shipment-detail-list">
						<div style="min-width:80px">{{ item.shipment_title }}</div>
						<div style="width:100%">全国(除指定地区)</div>
						<div style="min-width:80px">{{ item.value_1 }}</div>
						<div style="min-width:80px">{{ item.price_1 }}</div>
						<div style="min-width:80px">{{ item.value_2 }}</div>
						<div style="min-width:80px">{{ item.price_2 }}</div>
					</div>
					<div v-for="(rule,index) in current_ship.rules" :key="'c'+index" class="shipment-detail-list">
						<div style="min-width:80px">{{ item.shipment_title }}</div>
						<div style="width:100%"><span v-for="(place,index) in rule.area" :key="'a'+index">{{ place + (index === rule.area.length - 1 ? '' : ', ') }}</span></div>
						<div style="min-width:80px">{{ rule.value_1 }}</div>
						<div style="min-width:80px">{{ rule.price_1 }}</div>
						<div style="min-width:80px">{{ rule.value_2 }}</div>
						<div style="min-width:80px">{{ rule.price_2 }}</div>
					</div>
				</div>
			</transition>
		</div>
	</div>
	<el-dialog :visible="editVisible" :title="editTitle" width="650px" :before-close="close">
		<div v-loading="editTitle === '修改模版' && diaLoading">
			<el-form :model="editData" :rules="editRules" ref="edit" label-width="80px">
				<el-form-item label="模版名称" prop="shipment_name">
					<el-input v-model="editData.shipment_name"></el-input>
				</el-form-item>
				<el-form-item label="是否包邮" prop="need_cost">
					<el-radio v-model="editData.need_cost" :label="true" @change="needCost = true">自定义运费</el-radio>
					<el-radio v-model="editData.need_cost" :label="false" @change="needCost = false">包邮</el-radio>
				</el-form-item>
				<el-form-item label="计价方式" prop="cost_type_code" v-if="needCost">
					<el-radio v-model="editData.cost_type_code" label="numeric">按件数</el-radio>
					<el-radio v-model="editData.cost_type_code" label="weight">按重量</el-radio>
				</el-form-item>
				<el-form-item label="运送方式" prop="shipment_code">
					<el-radio v-for="(item,index) in shipment_codes" :key="index" v-model="editData.shipment_code" :label="item.code">{{ item.title }}</el-radio>
				</el-form-item>
				<div class="edit-ship-list edit-ship-header" v-if="needCost">
					<div style="width:70px">默认运费</div>
					<div style="width:calc(100% - 70px)">
						<el-input style="display:inline-block;width:80px" size="mini" type="number" min="1" step="0.01" v-model="editData.value_1" @blur="handleBlur('default','value_1')"></el-input>
						<span style="margin:0 5px">{{ editData.cost_type_code === 'numeric' ? '件内' : 'kg内' }}</span>
						<el-input style="display:inline-block;width:80px" size="mini" type="number" min="0" step="0.01" v-model="editData.price_1" @blur="handleBlur('default','price_1')"></el-input>
						<span style="margin:0 5px">元，每增加</span>
						<el-input style="display:inline-block;width:80px" size="mini" type="number" min="1" step="0.01" v-model="editData.value_2" @blur="handleBlur('default','value_2')"></el-input>
						<span style="margin:0 5px">{{ editData.cost_type_code === 'numeric' ? '件' : 'kg' }}</span>
						<el-input style="display:inline-block;width:80px" size="mini" type="number" min="0" step="0.01" v-model="editData.price_2" @blur="handleBlur('default','price_2')"></el-input>
						<span style="margin:0 5px">元</span>
					</div>
				</div>
				<div class="edit-ship-list" v-if="needCost && editData.rules.length !== 0">
					<div style="width:calc(100% - 340px)">运送到</div>
					<div style="width:75px">{{ editData.cost_type_code === 'numeric' ? '首件数/件' : '首重量/kg' }}</div>
					<div style="width:75px">首费/元</div>
					<div style="width:75px">{{ editData.cost_type_code === 'numeric' ? '续件数/件' : '续重量/kg' }}</div>
					<div style="width:75px">续费/元</div>
					<div style="width:40px">操作</div>
				</div>
				<div v-if="needCost && editData.rules.length !== 0">
					<div v-for="(rule,index) in editData.rules" :key="index" class="edit-ship-list">
						<div style="width:calc(100% - 340px)">
							<div v-if="rule.area.length !== 0" style="display:inline-block;vertical-align:top;width:calc(100% - 40px)"><span v-for="(place,index) in rule.area" :key="'a'+index">{{ place + (index === rule.area.length - 1 ? '' : ', ') }}</span></div>
							<div v-if="rule.area.length === 0" style="display:inline-block;vertical-align:top;width:calc(100% - 40px)">未添加地区</div>
							<div style="display:inline-block;vertical-align:top;width:40px"><el-button type="text" size="mini" @click="addPlace(index,rule)">编辑</el-button></div>
						</div>
						<div style="width:75px">
							<el-input style="display:inline-block;width:70px" size="mini" type="number" min="1" step="0.01" v-model="rule.value_1" @blur="handleBlur(index,'value_1')"></el-input>
						</div>
						<div style="width:75px">
							<el-input style="display:inline-block;width:70px" size="mini" type="number" min="0" step="0.01" v-model="rule.price_1" @blur="handleBlur(index,'price_1')"></el-input>
						</div>
						<div style="width:75px">
							<el-input style="display:inline-block;width:70px" size="mini" type="number" min="1" step="0.01" v-model="rule.value_2" @blur="handleBlur(index,'value_2')"></el-input>
						</div>
						<div style="width:75px">
							<el-input style="display:inline-block;width:70px" size="mini" type="number" min="0" step="0.01" v-model="rule.price_2" @blur="handleBlur(index,'price_2')"></el-input>
						</div>
						<div style="width:40px">
							<el-button type="text" size="mini" @click="delRule(index)">删除</el-button>
						</div>
					</div>
				</div>
				<div style="padding:5px" v-if="needCost">
					<el-button type="text" size="mini" @click="addRule">为指定地区设置运费</el-button>
				</div>
			</el-form>
		</div>
		<div slot="footer" style="text-align:center">
			<el-button @click="close">取消</el-button>
			<el-button type="primary" @click="confirm" :loading="btnLoading">确定</el-button>
		</div>
	</el-dialog>
	<place-dialog
		:Visible="placeVisible"
		:selected="selected_place"
		@close="closePlace"
		@confirm="confirmPlace">
	</place-dialog>
</div>
</template>

<script>
import { get_shipment_code, get_shipment, get_shipment_by_id, edit_shipment, create_shipment, delete_shipment } from "@/api/ship"
import PlaceDialog from '@/components/PlaceDialog'
export default {
	components:{
		PlaceDialog
	},
	data(){
		return {
			loading:false,
			shipments:[],
			shipment_codes:[],
			current_ship:{},
			itemLoading:false,
			editTitle:'',
			editVisible:false,
			editData:{
				shipment_name:'',
				need_cost:true,
				cost_type_code:'',
				shipment_code:'',
				value_1:1,
				price_1:0,
				value_2:1,
				price_2:0,
				rules:[]
			},
			editRules:{
				shipment_name:[{ required:true, message:'请填写运费模版名称', trigger:'blur' }],
				need_cost:[{ required:true, message:'请选择是否包邮', trigger:'blur' }],
				cost_type_code:[{ required:true, message:'请选择计价方式', trigger:'blur' }],
				shipment_code:[{ required:true, message:'请选择运送方式', trigger:'blur' }],
			},
			diaLoading:false,
			placeVisible:false,
			current_place:null,
			selected_place:[],
			needCost:true,
			btnLoading:false
		}
	},
	created(){
		this.getData()
	},
	methods:{
		getData(){
			this.loading = true
			get_shipment_code().then(r=>{
				this.shipment_codes = r.data.body
			})
			get_shipment().then(r=>{
				this.shipments = r.data.body
				this.shipments.map(v=>{
					v['detailShow'] = false
				})
				this.loading = false
			}).catch(()=>{
				this.loading = false
			})
		},
		showDetail(item){
			this.itemLoading = true
			item.detailShow = true
			get_shipment_by_id(item.id).then(r=>{
				this.current_ship = r.data.body
				this.itemLoading = false
			}).catch(()=>{
				this.itemLoading = false
			})
		},
		closeDetail(item){
			this.current_ship = {}
			item.detailShow = false
		},
		createShipment(){
			this.editVisible = true
			this.editTitle = '创建模版'
			this.editData.shipment_code = this.shipment_codes[0].code
		},
		editShip(item){
			this.editVisible = true
			this.editTitle = '修改模版'
			this.diaLoading = true
			get_shipment_by_id(item.id).then(r=>{
				this.editData = r.data.body
				this.editData.value_1 = this.editData.value_1 ? this.editData.value_1 : 1
				this.editData.value_2 = this.editData.value_2 ? this.editData.value_2 : 1
				this.editData.price_1 = this.editData.price_1 !== null ? this.editData.price_1 : 0
				this.editData.price_2 = this.editData.price_2 !== null ? this.editData.price_2 : 0
				this.diaLoading = false
			}).catch(()=>{
				this.diaLoading = false
			})
		},
		close(){
			this.editTitle = ''
			this.editVisible = false
			this.editData = {
				shipment_name:'',
				need_cost:true,
				cost_type_code:'',
				shipment_code:'',
				value_1:1,
				price_1:0,
				value_2:1,
				price_2:0,
				rules:[]
			}
			this.$refs.edit.clearValidate()
			this.needCost = true
		},
		confirm(){
			if(this.editTitle === '修改模版') this.confirmEdit()
				else this.confirmCreate()
		},
		confirmEdit(){
			this.$refs.edit.validate((valid)=>{
				if(valid){
					this.btnLoading = true
					edit_shipment(this.editData.id,this.editData).then(()=>{
						this.$message.success('修改成功')
						this.btnLoading = false
						this.close()
						this.getData()
					}).catch(()=>{
						this.btnLoading = false
					})
				}
			})
		},
		confirmCreate(){
			this.$refs.edit.validate((valid)=>{
				if(valid){
					this.btnLoading = true
					create_shipment(this.editData).then(()=>{
						this.$message.success('创建成功')
						this.btnLoading = false
						this.close()
						this.getData()
					}).catch(()=>{
						this.btnLoading = false
					})
				}
			})
		},
		addRule(){
			this.editData.rules.push({
				area:[],
				value_1:1,
				price_1:0,
				value_2:1,
				price_2:0
			})
		},
		delRule(index){
			this.editData.rules.splice(index,1)
		},
		handleBlur(index,type){
			if(index === 'default'){
				this.editData[type] = this.editData[type] !== '' ? this.editData[type] : (type === 'price_1' || type === 'price_2' ? 0 : 1)
			}else{
				this.editData.rules[index][type] = this.editData.rules[index][type] !== '' ? this.editData.rules[index][type] : (type === 'price_1' || type === 'price_2' ? 0 : 1)
			}
		},
		addPlace(index,item){
			this.current_place = index
			this.selected_place = item.area
			this.placeVisible = true
		},
		closePlace(){
			this.placeVisible = false
			this.selected_place = []
			this.current_place = null
		},
		confirmPlace(val){
			this.editData.rules[this.current_place].area = val
			this.placeVisible = false
		},
		copyShip(item){
			this.$loading()
			get_shipment_by_id(item.id).then(r=>{
				let _data = {
					shipment_name: item.shipment_name + '_副本',
					need_cost:item.need_cost,
					cost_type_code:item.cost_type_code,
					shipment_code:item.shipment_code,
					value_1:item.value_1,
					price_1:item.price_1,
					value_2:item.value_2,
					price_2:item.price_2,
					rules:r.data.body.rules
				}
				create_shipment(_data).then(()=>{
					this.getData()
					this.$loading().close()
				}).catch(()=>{
					this.$loading().close()
				})
			}).catch(()=>{
				this.$loading().close()
			})
		},
		deleteShip(item){
			this.$confirm('是否确认删除该模版？','提示',{
				confirmButtonText:'确认',
				cancelButtonText:'取消'
			}).then(()=>{
				this.$loading()
				delete_shipment(item.id).then(()=>{
					this.$message.success('删除成功')
					this.getData()
					this.$loading().close()
				}).catch(e=>{
					this.$message.warning({
						message:e.response.data.message,
						duration:4000
					})
					this.$loading().close()
				})
			}).catch(()=>{})
		}
	}
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shipment-header{
	background-color:#fff;
	padding:10px;
	border-radius:5px;
	border:1px solid $line-color;
	margin-bottom:10px;
}
.shipment-list{
	background-color:#fff;
	min-height:300px;
	padding:10px;
	border-radius:5px;
	border:1px solid $line-color;
}
.shipment-list-item{
	border:1px solid $line-color * 0.95;
	border-radius:5px;
	margin-bottom:10px;
}
.shipment-basic-info{
	margin:10px 0;
	padding:0 10px;
}
.shipment-basic-info>div{
	display:inline-block;
	vertical-align:bottom;
}
.shipment-info-title{
	font-size:$large-font-size;
}
.shipment-info-sub{
	color:$other-font-color;
	font-size:12px;
}

.shipment-detail-table{
	margin:20px 10px 10px;
	border:1px solid $line-color;
	border-radius:5px;
}
.shipment-detail-list{
	border-bottom:1px solid $line-color;
}
.shipment-detail-list:first-child{
	background-color:$light-back-sub * 0.95;
	color:$sub-font-color;
}
.shipment-detail-list:last-child{
	border-bottom:none;
}
.shipment-detail-list>div{
	display:table-cell;
	vertical-align:middle;
	border-right:1px solid $line-color;
	padding:6px;
}
.shipment-detail-list>div:last-child{
	border-right:none;
}

.shipment-detail-enter-active,.shipment-detail-leave-active{
	transition:all .2s linear;
}
.shipment-detail-enter,.shipment-detail-leave-to{
	transform:translateY(-30px);
	height:0;
	opacity:0;
}

.edit-ship-list{
	background-color:$light-back-sub;
	padding:6px 10px;
	border-bottom:1px solid $line-color * 0.98;
}
.edit-ship-list>div{
	display:inline-block;
	vertical-align:middle;
}
.edit-ship-header{
	background-color:$light-back-sub * 0.97;
	padding:8px 10px;
}
</style>