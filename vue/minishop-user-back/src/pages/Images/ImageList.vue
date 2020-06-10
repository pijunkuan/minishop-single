<template>
<div>
	<div class="image-button">
		<div style="width:100px">
			<el-upload class="avatar-uploader upload-btn"
				:headers="{'Authorization': 'Bearer ' + $store.getters.token}"
				:action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/image'"
				name="file"
				:before-upload="beforeUpload"
				:show-file-list="false"
				:on-success="successUpload">
				<el-button type="primary" size="small">新增图片</el-button>
			</el-upload>
		</div>
		<div class="image-space" style="width:calc(100% - 100px)">
			<span>图片空间：共 <strong>{{ image.count }} </strong>张图片，已使用<strong> {{ image.bytes_value }} </strong> / 总大小<strong> {{ $store.getters.shop_img_limit }} GB</strong></span>
			<!-- <el-button size="small" type="primary" style="margin-left:20px">购买图片空间</el-button> -->
		</div>
	</div>
	<div v-loading="loading" style="background-color:#fff;padding:10px;border-radius:5px">
		<div style="min-height:500px;">
			<div v-for="(image , index) in images" :key="index" class="image-list-item" style="position:relative" @mouseenter="activeIndex = index" @mouseleave="activeIndex = null"> 
				<div class="image-edit-button" :class="{'is-active':activeIndex === index}">
					<span style="width:20px;padding-right:5px;text-align:right;cursor:pointer" @click="imgDelete(image)"><i class="iconfont iconshanchu1"></i></span>
				</div>
				<el-image class="image-content" style="width:180px;height:180px" :src="image.url" fit="scale-down"></el-image>
				<div>{{image.name}}</div>
			</div>
		</div>
		<el-pagination
			style="text-align:center;margin-top:20px;"
			:current-page="query.page"
			:page-size="query.pageSize"
			:total="total"
			layout="total, prev, pager, next, jumper"
			@current-change="handleCurrentChange">
		</el-pagination>
	</div>
</div>
</template>

<script>
	import { get_images, delete_image } from "@/api/image"
	import { get_static_data } from '@/api/static'
	export default {
		data(){
			return {
				loading:false,
				images:[],
				query:{
					page:1,
					pageSize:15
				},
				total:0,
				activeIndex:null,
				image:{
					bytes_value:0,
					count:0
				}
			}
		},
		created(){
			this.getData()
		},
		methods:{
			getData(){
				this.loading = true
				get_images(this.query).then(r=>{
					this.images = r.data.body.data
					this.total = r.data.body.page.total
					this.loading = false
				}).catch(()=>{
					this.loading = false
				})
				get_static_data('image').then(r=>{
					this.image.bytes_value = r.data.body.bytes_value
					this.image.count = r.data.body.count
				})
			},
			handleCurrentChange(pagination){
				this.query.page = pagination
				this.getData()
			},
			beforeUpload(file){
				const isJPG = (file.type === 'image/jpeg' || file.type === 'image/png')
				const isLt2M = file.size / 1024 / 1024 < 2

				if (!isJPG) {
					this.$message.error('上传图片只能是 JPG 格式 或 PNG 格式!')
				}
				if (!isLt2M) {
					this.$message.error('上传图片大小不能超过 2MB!')
				}
				return isJPG && isLt2M
			},
			successUpload(){
				this.$message.success('上传成功')
				this.getData()
			},
			imgDelete(image){
				this.$confirm('是否确认删除？','提示',{
					confirmButtonText:'确定',
					cancelButtonText:'取消'
				}).then(()=>{
					delete_image(image.id).then(()=>{
						this.$message.success('已成功删除')
						this.getData()
					}).catch(()=>{})
				}).catch(()=>{})
			}
		}
	}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.image-button{
	margin:10px 0;
	padding:10px;
	background-color:#fff;
	border-radius:5px;
	border-bottom:1px solid $line-color;
}
.image-button>div{
	display:inline-block;
	vertical-align:middle;
}
.image-button>div:last-child{
	text-align:right;
}
.image-list-item{
	display:inline-block;
	width:180px;
	height:230px;
	vertical-align:middle;
	margin-right:10px;
	margin-bottom:10px;
	text-align:center;
}
.image-list-item>div.image-content{
	height:180px;
	width:180px;
	border:1px solid $line-color;
}
.image-edit-button{
    position:absolute;
    margin-top:161px;
    height:30px;
    width:182px;
    line-height:30px;
    z-index:2;
    background-color:rgba(0,0,0,0.5);
    opacity:0;
    color:#fff;
}
.image-edit-button.is-active{
    transition:all .2s ease-in;
    transform:translateY(-10px);
    opacity:1;
}
.image-space{
	display:inline-block;
	color:$other-font-color;
}
.image-space strong{
	color:$sub-font-color;
}
</style>