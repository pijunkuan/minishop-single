<template>
<div class="login-background">
    <div class="login-form">
        <shop-image class="login-form-avatar" :width="80" circled>
            <div slot="placeholder">
                <shop-icon style="background-color:#fff" name="iconzhucetouxiang"></shop-icon>
            </div>
            <div slot="error">
                <shop-icon style="background-color:#fff" name="iconzhucetouxiang"></shop-icon>
            </div>
        </shop-image>
        <!-- <div class="login-form-switch">
            <div style="width:50%">
                <shop-button :type="regType === 'mobile' ? 'default' : 'border'" :rounded="false" size="medium" @click="changeType('email','mobile')">手机注册</shop-button>
            </div>
            <div style="width:50%">
                <shop-button :type="regType === 'email' ? 'default' : 'border'" :rounded="false" size="medium" @click="changeType('mobile','email')">邮箱注册</shop-button>
            </div>
        </div> -->
        <shop-input ref="email" v-if="regType === 'email'" class="login-form-input" v-model="user.email" validate :rules="rules.email">
            <span slot="prepend" class="login-prepend">邮箱</span>
        </shop-input>
        <shop-input ref="mobile" v-if="regType === 'mobile'" class="login-form-input" v-model="user.mobile" validate :rules="rules.mobile">
            <span slot="prepend" class="login-prepend">手机号</span>
        </shop-input>
        <shop-input ref="verification" class="login-form-input verify-input" v-model="user.verification_code" :type="passType" validate :rules="rules.verification_code">
            <span slot="prepend" class="login-prepend">验证码</span>
            <span slot="append" @click="getVerify">
                {{ code_tip }}
            </span>
            <span @click="passType = 'password'" slot="append">
                <shop-icon v-if="passType === 'text'" name="yanjing" size="mini"></shop-icon>
            </span>
        </shop-input>
        <shop-input ref="password" class="login-form-input" v-model="user.password" :type="passType" validate :rules="rules.password">
            <span slot="prepend" class="login-prepend">密码</span>
            <span @click="passType = 'text'" slot="append">
                <shop-icon v-if="passType === 'password'" name="yanjing-bi" size="mini"></shop-icon>
            </span>
            <span @click="passType = 'password'" slot="append">
                <shop-icon v-if="passType === 'text'" name="yanjing" size="mini"></shop-icon>
            </span>
        </shop-input>
        <shop-button class="login-form-button" :class="{'is-loading':btnLoading}" @click="confirmLogin">注 册</shop-button>
        <div class="login-form-bottom">
            <div>已有账号？<div class="bottom-btn" @click="toLogin">登录</div></div>
            <!-- <div class="bottom-btn" @click="toForget">找回密码</div> -->
        </div>
    </div>
</div>
</template>

<script>
import { Toast } from 'mint-ui'
import { register, get_verify } from '@/api/login'
export default{
    data(){
        return{
            regType:'mobile',
            user:{
                email:'',
                mobile:'',
                password:'',
                verification_code:''
            },
            passType:'password',
            rules:{
                email:[
                    { required:true, message:'请输入邮箱' }
                ],
                mobile:[
                    { required:true, message:'请输入手机号' }
                ],
                password:[
                    { required:true, message:'请输入密码' },
                    { min:6, message:'密码至少为6位' }
                ],
                verification_code:[
                    { required:true, message:'请输入验证码' },
                    { len:4, message:'验证码为4位' }
                ]
            },
            btnLoading:false,
            code_tip:'获取验证码',
            time:30,
            codeLoading:false
        }
    },
    methods:{
        changeType(oritype,type){
            this.$refs[oritype].clearValidate()
            this.regType = type
        },
        toLogin(){
            this.$router.push({name:'Login'})
        },
        toForget(){
            this.$router.push({name:'Forget'})
        },
        getVerify(){
            if(this.codeLoading) return
            this.codeLoading = true
            this.$refs.verification.clearValidate()
            this.verifyLoading = true
            this.code_tip = this.time + 's后重试'
            this.timeOut()
            get_verify({mobile:this.user.mobile}).then(()=>{
                Toast('验证码已发送至手机，请及时查收')
            })
        },
        timeOut(){
            if(this.time === 0){
                this.code_tip = '获取验证码'
                this.time = 30
                this.codeLoading = false
                return
            }
            setTimeout(()=>{
                this.time --
                this.code_tip = this.time + 's后重试'
                this.timeOut()
            },1000)
        },
        confirmLogin(){
            if(this.btnLoading) return
            this.$refs.mobile.validation((valid)=>{
                this.$refs.password.validation((validate)=>{
                    this.$refs.verification.validation((val)=>{
                        if(valid && validate && val){
                            this.btnLoading = true
                            this.register()
                        }
                    })
                })
            })
        },
        register(){
            let _data = {
                mobile: this.user.mobile,
                password:this.user.password,
                password_confirmation:this.user.password,
                verification_code:this.user.verification_code
            }
            register(_data).then(()=>{
                Toast({
                    message:'注册成功，正在跳转登录中...',
                    duration:2000
                })
                this.$store.dispatch('login',{mobile:this.user.mobile, password:this.user.password}).then(()=>{
                    if(this.$route.query.from === 'Login' || this.$route.query.from === undefined) this.$router.push({ name:'UserHome' })
                        else this.$router.push({ name: this.$route.query.from })
                })
                setTimeout(()=>{
                    this.$refs.mobile.clearValidate()
                    this.$refs.password.clearValidate()
                    this.$refs.verification.clearValidate()
                    this.btnLoading = false
                },2000)
            }).catch(e=>{
                Toast({
                    message:e.response.data.message,
                    duration:2000
                })
                this.btnLoading = false
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.login-background{
    @include block-background(1);
}
.login-form{
    margin:70px 15px 30px;
    padding:45px 10px 30px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:10px;
    box-shadow:0 0 10px 5px rgba(0,0,0,0.15);
}
.login-prepend{
    color:$sub-font-color;
}

.login-form-avatar{
    position:absolute;
    top:20px;
    left:50%;
    margin-left:-42px;
    border:2px solid #fff;
    border-radius:50%;
}

.login-form-switch{
    margin:0 10px 30px;
}
.login-form-switch>div{
    display:inline-block;
    vertical-align:middle;
}
.login-form-switch>div:first-child{
    text-align:right;
}

.login-form-input{
    margin-bottom:15px;
}
.login-form-button{
    margin-top:20px;
    opacity:1;
}

.login-form-bottom{
    margin-top:10px;
    color:$sub-font-color;
    font-size:$small-font-size;
}
.login-form-bottom>div{
    display:inline-block;
    width:50%;
    vertical-align:middle;
}
// .login-form-bottom>div:last-child{
//     text-align:right;
// }

.login-form-button.is-loading{
    transition:opacity .2s ease-in;
    opacity:0.8;
}

.bottom-btn{
    display:inline-block;
    @include base-color(1);
    font-weight:500;
    font-size:$middle-font-size;
}
</style>

<style>
.verify-input .input-append{
    width:80px;
    text-align:center;
}
.verify-input .shop-input.is-append.is-prepend{
    width:calc(100% - 142px);
}
</style>