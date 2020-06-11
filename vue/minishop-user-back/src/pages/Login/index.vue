<template>
<div class="login-page">
    <transition name="login-form">
        <div class="login-form" v-show="isShow" :style="{height:height + 'px', marginTop:-height / 2 - 65 + 'px'}">
            <div>{{ $store.getters.title }}</div>
            <el-form ref="login" :model="user" :rules="rules">
                <el-form-item prop="username">
                    <el-input v-model="user.username" placeholder="用户名">
                        <span slot="prefix"><i class="iconfont iconshequ"></i>用户名</span>
                    </el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input v-model="user.password" placeholder="密码" type="password">
                        <span slot="prefix"><i class="iconfont iconmima"></i>密码</span>
                    </el-input>
                </el-form-item>
            </el-form>
            <div style="margin-left:30px;margin-bottom:20px">
                <div>测试版本：</div>
                <div>用户名：admin，密码：111111</div>
            </div>
            <div>
                <el-button type="primary" @click="Login">登录</el-button>
            </div>
        </div>
    </transition>
</div>
</template>

<script>
export default{
    data(){
        return{
            isShow:false,
            user:{
                username:"",
                password:""
            },
            rules:{
                username:[
                    {required:true, message:'请填写用户名', trigger:'blur'}
                ],
                password:[
                    {required:true,message:'请填写密码',trigger:'blur'},
                    {min:6,message:'密码至少6位',trigger:'blur'}
                ]
            },
            height:0
        }
    },
    created(){
        setTimeout(()=>{
            this.isShow = true
        },50)
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight * 0.6
        })
    },
    methods:{
        Login(){
            this.$store.dispatch('login',this.user).then(()=>{
                this.$router.push('/dashboard')                
            }).catch(e=>{
                this.$message.error({
                    message:e.response.data.message
                })
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.login-page{
    position:absolute;
    top:0;
    bottom:0;
    right:0;
    left:0;
    background-image:url('~@/assets/images/sky-828648_1280.jpg');
    background-repeat:round;
}
.login-form{
    position:absolute;
    top:50%;
    left:50%;
    margin-left:-265px;
    width:500px;
    padding:15px 15px 75px;
    border:1px solid $line-color;
    box-shadow:0 0 10px 5px rgba(255,255,255,0.2);
    border-radius:8px;
    background-color:rgba(0,0,0,0.45);
    color:#fff;
}
.login-form>div:first-child{
    font-size:20px;
    font-weight:500;
    line-height:100px;
    padding-bottom:10px;
    margin-bottom:40px;
    text-align:center;
    border-bottom:1px solid $line-color;
}
.login-form>div:last-child button{
    margin-top:20px;
    width:100%;
}
.login-form-enter-active,.login-form-leave-active{
    transition:all .5s ease-in;
}
.login-form-enter,.login-form-leave-to{
    transform:translateY(-80px);
    opacity:0;
}
</style>

<style>
.login-form .el-input__inner{
    background:none;
    padding-left:85px;
    color:#fff;
}
.login-form .el-input__inner:hover{
    border-color:#fff;
}
.login-form .el-input__prefix{
    top:-1px;
    color:#fff;
}
.login-form .el-input__prefix i{
    margin-right:5px;
    font-size:20px;
    position:relative;
    top:3px;
}
.login-form input:-webkit-autofill{
    transition: background-color 86400s ease-in-out 0s;
    color:#fff!important;
    -webkit-text-fill-color:#fff!important;
}
.login-form input:-internal-autofill-selected{
    color:#fff!important;
}
</style>