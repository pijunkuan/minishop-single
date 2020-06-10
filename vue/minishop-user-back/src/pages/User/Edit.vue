<template>
<div>
	<div class="user-info">
		<div class="user-info-title"><strong>个人设置</strong> | {{$store.getters.username}}</div>
		<div class="user-info-contain">
			<el-form status-icon ref="edit" :model="password" :rules="rules">
				<el-form-item label="新密码" prop="password">
					<el-input v-model="password.password" type="password"></el-input>
				</el-form-item>
				<el-form-item label="确认密码" prop="password_confirmation">
					<el-input v-model="password.password_confirmation" type="password"></el-input>
				</el-form-item>
			</el-form>
			<div style="text-align: right">
				<el-button size="small" @click="reset">清空</el-button>
				<el-button type="primary" size="small" @click="editUser">确认修改</el-button>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	export default {
		data(){
			var password_confirmation_validate = (rule , value , callback)=>{
				if(value !== this.password.password){
					callback(new Error('两次密码不一致！！'))
				}else{
					callback()
				}
			}
			return {
				password:{
					password:"",
					password_confirmation:""
				},
				rules:{
					password:[
						{required:true, message:'请填写密码', trigger:'blur'},
						{min:6,message:'密码至少6位',trigger:'blur'}

					],
					password_confirmation:[
						{required:true,message:'请再一次填写密码',trigger:'blur'},
						{ validator: password_confirmation_validate,trigger:'blur'},
						{min:6,message:'密码至少6位',trigger:'blur'}
					]
				},
			}
		},
		methods:{
			reset(){
				this.password = {
					password:"",
					password_confirmation:""
				}
			},
			editUser(){
				this.$refs['edit'].validate((valid)=>{
					if(valid){
						this.$store.dispatch('edit',this.password).then(()=>{
						this.$message.success('修改成功')
						this.reset()
						}).catch(e=>{
							this.$message.error(e.response.data.message)
						})
					}else{
						this.$message.warning('输入有误！')
						return false
					}
				})
				
			}
		}
	}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';

.user-info {
	position:relative;
	margin-left:-201px;
	left:50%;
	width: 400px;
	height: 300px;
	border:1px solid $line-color;
	border-radius: 10px;
}
.user-info .user-info-title>strong {
	font-size:$large-font-size;
	color:$main-font-color;
}
.user-info .user-info-title {
	background-color: $background-color;
	font-size:$small-font-size;
	color:$sub-font-color;
	padding: 10px;
	border-radius: 10px 10px 0 0;
	border-bottom: 1px solid $line-color;
}
.user-info-contain {
	padding: 0 15px;
}
</style>