<template>
<div>
    <div class="template-block" v-loading="loading" v-if="pageHome">
        <div class="template-block-title">
            <div style="width:calc(100% - 96px)">我的插件</div>
        </div>
        <div style="min-height:200px">
            <div v-if="!loading && user_plugins.length === 0" class="loading-placeholder">
                <div>暂无可用插件</div>
            </div>
            <div v-for="(item,index) in user_plugins" :key="index" class="template-current-block">
                <div style="width:50px">
                    <i class="iconfont" :class="item.icon"></i>
                </div>
                <div style="width:calc(100% - 50px)">
                    <div class="template-name">
                        <span>{{ item.plugin_name }}</span>
                        <el-button size="mini" type="primary" style="float:right" @click="toPlugin(item)">进入</el-button>
                    </div>
                    <div class="template-desc">
                        <div>添加时间：{{ item.created_at }}</div>
                        <div>到期时间：{{ item.exp_at }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="template-block-title" style="margin-top:40px">插件市场</div>
        <div style="min-height:300px">
            <div v-if="!loading && plugins.length === 0" class="loading-placeholder">
                <div>暂无插件</div>
            </div>
            <div v-for="(item,index) in plugins" :key="index" class="template-current-block">
                <div style="width:50px">
                    <i class="iconfont" :class="item.icon"></i>
                </div>
                <div style="width:calc(100% - 50px)">
                    <div class="template-name">{{ item.plugin_name }}</div>
                    <div class="template-price">
                        <span>¥ <strong>{{ item.price }}</strong>/月</span>
                        <el-button size="small" type="primary" style="float:right;margin-top:2px">购买</el-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div>返回</div>
        <div v-if="plgLoading"></div>
        <iframe v-else ref="plugin" :src="src"></iframe>
    </div>
</div>
</template>

<script>
export default{
    data(){
        return{
            plugins:[],
            user_plugins:[],
            loading:false,
            pageHome:true,
            src:'',
            plgLoading:false
        }
    },
    created(){
        this.getPlugins()
    },
    methods:{
        getPlugins(){
            this.loading = true
            setTimeout(()=>{
                this.plugins = [
                    // { plugin_name:'满减满折', plugin_code:'Discount', type:'商品营销', desc:'', price:'20', icon:'iconkanjia' },
                    // { plugin_name:'搭配宝', plugin_code:'Match', type:'商品营销', desc:'', price:'20', icon:'iconmaiyizengyi' },
                    // { plugin_name:'优惠券', plugin_code:'Coupon', type:'商品营销', desc:'', price:'20', icon:'iconyouhuiquan' },
                    // { plugin_name:'VIP会员', plugin_code:'Vip', type:'顾客营销', desc:'', price:'20', icon:'iconyingchengka' }
                ]
                this.user_plugins = [
                    // { plugin_name:'满减满折', plugin_code:'Discount', type:'商品营销', price:'20', created_at:'2020-05-15', exp_at:'2020-06-15', icon:'iconkanjia' },
                    // { plugin_name:'搭配宝', plugin_code:'Match', type:'商品营销', price:'20', created_at:'2020-05-15', exp_at:'2020-06-15', icon:'iconmaiyizengyi' },
                    // { plugin_name:'优惠券', plugin_code:'Coupon', type:'商品营销', price:'20', created_at:'2020-05-15', exp_at:'2020-06-15', icon:'iconyouhuiquan' },
                    // { plugin_name:'VIP会员', plugin_code:'Vip', type:'顾客营销', price:'20', created_at:'2020-05-15', exp_at:'2020-06-15', icon:'iconyingchengka' }
                ]
                this.loading = false
            },1000)
        },
        toPlugin(item){
            this.src = location.href + '' + item.src
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.template-block{
    padding:10px;
    background-color:#fff;
    border-radius:5px;
    border:1px solid $line-color;
}
.template-block-title{
    line-height:30px;
    font-weight:500;
    padding-bottom:5px;
    margin-bottom:10px;
    border-bottom:1px solid $line-color * 0.98;
}
.template-block-title>div{
    display:inline-block;
    vertical-align:middle;
}
.template-current-block{
    display:inline-block;
    margin-right:5px;
    margin-bottom:10px;
    padding:10px;
    border:1px solid $line-color * 0.9;
    border-radius:10px;
    background-color:$light-back-sub * 0.995;
    width:calc(33.33% - 27px);
}
.template-current-block>div{
    display:inline-block;
    vertical-align:top;
}
.template-current-block i{
    font-size:24px;
    font-weight:500;
    position:relative;
    top:5px;
    margin-right:10px;
    color:$dark-back-sub;
    padding:4px;
    border:2px solid $dark-back-sub;
    border-radius:50%;
}
.template-name{
    font-size:15px;
    font-weight:500;
    position:relative;
    top:2px;
}
.template-desc,.template-price{
    color:$other-font-color;
    font-size:$small-font-size;
    margin-top:20px;
}
.template-price{
    margin-top:10px;
}
.template-price strong{
    font-size:$title-font-size;
    color:$main-color;
}
</style>