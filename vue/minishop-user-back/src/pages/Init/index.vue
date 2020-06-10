<template>
<div class="web-container">
    <div class="web-header">
        商城配置
        <div class="back-button" @click="returnCenter" style="cursor:pointer">回到主控台</div>
    </div>
    <div class="back-content" v-loading="loading">
        <set-basic 
            v-if="activeIndex === 1" 
            :shop="shop" 
            :btnLoading="btnLoading" 
            @confirm="setBasic">
        </set-basic>
        <set-theme 
            v-if="activeIndex === 2" 
            :activeIndex="activeIndex" 
            :shop="shop" 
            :btnLoading="btnLoading" 
            @confirm="confirmTheme" 
            @prev="toPrev"
            @complete="confirm">
        </set-theme>
        <div v-if="activeIndex === 4">
            <div v-if="!isComplete">
                <div class="image-tip image-tip-floating"><img src="@/assets/images/balloon_two_color.png" /></div>
                <div class="image-tip-text">安装中...</div>
            </div>
            <transition name="success">
                <div v-show="isComplete" class="setup-success">
                    <div class="image-tip"><img src="@/assets/images/shopping.png" /></div>
                    <div class="image-tip-text">安装成功</div>
                </div>
            </transition>
            <div v-if="isComplete" style="text-align:center;margin-top:40px">
                <el-button type="primary" @click="returnCenter">回到商城列表</el-button>
                <el-button type="primary" @click="$router.push({name:'Dashboard'})">进入商城</el-button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import SetBasic from './components/SetBasic'
import SetTheme from './components/SetTheme'
import { edit_shop } from '@/api/shop'
export default{
    components:{
        SetBasic,
        SetTheme
    },
    data(){
        return{
            activeIndex:1,
            loading:false,
            shop:{
                shop_name:'',
                shop_level:'',
                shop_url:'',
                shop_status:'',
                shop_id:'',
                shop_active:'',
                shop_host:'',
                shop_exp:''
            },
            btnLoading:false,
            isComplete:false,
            changeName:false,
            changeUrl:false
        }
    },
    created(){
        this.loading = true
    },
    methods:{
        toPrev(){
            this.activeIndex -= 1
            this.changeName = false
            this.changeUrl = false
        },
        setBasic(){
            if(this.changeName || this.changeUrl){
                this.btnLoading = true
                let _data = {
                    shop_name:this.shop.shop_name,
                    shop_url:this.shop.shop_url
                }
                edit_shop(_data).then(()=>{
                    this.activeIndex = 2
                    this.btnLoading = false
                }).catch(()=>{
                    this.btnLoading = false
                })
            }else{
                this.activeIndex = 2
            }
        },
        confirmTheme(){
            this.$message.success('创建主题成功')
            this.confirm()
        },
        confirm(){
            this.activeIndex = 4
            edit_shop({status:'active'}).then(()=>{
                setTimeout(()=>{
                    this.isComplete = true
                },5000)
            }).catch(e=>{
                this.activeIndex = 3
                this.$message.error(e.response.data.message)
            })
        },
        returnCenter(){
            let _url = location.origin.split('.')
            let _host = "//account."+_url[_url.length-2]+"."+_url[_url.length-1]
            location.href = _host
        }
    },
    watch:{
        '$store.getters.shop_status':{
            handler:function(nv){
                if(nv !== ''){
                    this.loading = false
                    this.shop.shop_name = this.$store.getters.shop_name
                    this.shop.shop_level = this.$store.getters.shop_level
                    this.shop.shop_url = this.$store.getters.shop_url
                    this.shop.shop_id = this.$store.getters.shop_id
                    this.shop.shop_status = this.$store.getters.shop_status
                    this.shop.shop_host = this.$store.getters.shop_host
                    this.shop.shop_active = this.$store.getters.shop_active
                    this.shop.shop_exp = this.$store.getters.shop_exp
                    if(this.shop.shop_status === 'active') this.$router.push({name:'Dashboard'})
                }
            }
        },
        'shop.shop_name':{
            deep:true,
            immediate:true,
            handler:function(nv,ov){
                if(nv !== ov) this.changeName = true
                    else this.changeName = false
            }
        },
        'shop.shop_url':{
            deep:true,
            immediate:true,
            handler:function(nv,ov){
                if(nv !== ov) this.changeUrl = true
                    else this.changeUrl = false
            }
        }
    }
}
</script>

<style scoped>
.back-button{
    position:absolute;
    line-height:60px;
    right:20px;
    font-size:14px;
    display:inline-block;
}
.image-tip{
    margin:20px 220px 0;
}
.image-tip-floating{
    -webkit-animation: floating 5s ease-in-out infinite;
    -moz-animation: floating 5s ease-in-out infinite;
    -ms-animation: floating 5s ease-in-out infinite;
    -o-animation: floating 5s ease-in-out infinite;
    animation: floating 5s ease-in-out infinite;
}
.image-tip img{
    width:100%;
    object-fit:contain;
}
@keyframes floating
{
    0%
        {
            transform: translateY(0);
            -o-transform: translateY(0);
            -ms-transform: translateY(0);
            -moz-transform: translateY(0);
            -webkit-transform: translateY(0);
        }
    50%
        {
            transform: translateY(60px);
            -o-transform: translateY(60px);
            -ms-transform: translateY(60px);
            -moz-transform: translateY(60px);
            -webkit-transform: translateY(60px);
        }
    100%
        {
            transform: translateY(0);
            -o-transform: translateY(0);
            -ms-transform: translateY(0);
            -moz-transform: translateY(0);
            -webkit-transform: translateY(0);
        }
}
.image-tip-text{
    text-align:center;
    margin-top:40px;
    font-size:18px;
    color:#66656a;
}
.success-enter-active,.success-leave-active{
    transition:opacity .5s ease-in;
}
.success-enter,.success-leave-to{
    opacity:0;
}
</style>