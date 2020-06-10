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
        <shop-input ref="mobile" class="login-form-input" v-model="user.mobile" validate :rules="rules.mobile" placeholder="请输入手机号">
            <span slot="prepend" class="login-prepend">登录名</span>
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
        <shop-button class="login-form-button" :class="{'is-loading':btnLoading}" @click="confirmLogin">登 录</shop-button>
        <div class="login-form-bottom">
            <div>还没有账号？<div class="bottom-btn" @click="toRegister">注册</div></div>
            <div class="bottom-btn" @click="toForget" style="text-align:right">找回密码</div>
        </div>
    </div>
</div>
</template>

<script>
import { Toast } from 'mint-ui'
export default{
    data(){
        return{
            user:{
                mobile:'',
                password:''
            },
            passType:'password',
            rules:{
                mobile:[
                    { required:true, message:'请输入手机号' }
                ],
                password:[
                    { required:true, message:'请输入密码' },
                    { min:6, message:'密码至少为6位' }
                ]
            },
            btnLoading:false
        }
    },
    methods:{
        toRegister(){
            this.$router.push({name:'Register'})
        },
        toForget(){
            this.$router.push({name:'Forget'})
        },
        confirmLogin(){
            if(this.btnLoading) return
            this.$refs.mobile.validation((valid)=>{
                this.$refs.password.validation((validate)=>{
                    if(valid && validate){
                        this.btnLoading = true
                        this.login()
                    }
                })
            })
        },
        login(){
            this.$store.dispatch('login',this.user).then(()=>{
                Toast('登录成功')
                setTimeout(()=>{
                    this.afterLogin()
                    this.btnLoading = false
                },1000)
            }).catch(e=>{
                Toast({
                    message: e.response.data.message,
                    duration:2000
                })
                this.btnLoading = false
            })
        },
        afterLogin(){
            this.$refs.mobile.clearValidate()
            this.$refs.password.clearValidate()
            if(this.$route.query.from !== undefined){
                if(this.$route.query.type !== undefined){
                    let _query = {}
                    _query[this.$route.query.type] = this.$route.query.query
                    this.$router.push({name:this.$route.query.from, query:_query})
                }else{
                    this.$router.push({name:this.$route.query.from})
                }
            }else{
                this.$router.push({name:'UserHome'})
            }
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
    margin:80px 15px 0;
    padding:60px 10px 30px;
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
    top:30px;
    left:50%;
    margin-left:-42px;
    border:2px solid #fff;
    border-radius:50%;
}
.login-form-input{
    margin-bottom:15px;
}
.login-form-button{
    margin-top:20px;
    opacity:1;
}
.login-form-button.is-loading{
    transition:opacity .2s ease-in;
    opacity:0.8;
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

.bottom-btn{
    display:inline-block;
    @include base-color(1);
    font-weight:500;
    font-size:$middle-font-size;
}
</style>