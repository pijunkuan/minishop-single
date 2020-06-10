<template>
<div>
    <div class="set-title">主题选择</div>
    <div v-if="current_theme.template_id !== undefined" class="current-theme">当前主题：<strong>{{ current_theme.template_name }}</strong></div>
    <div v-loading="loading" class="set-theme-list" :style="{height:height + 'px'}">
        <el-radio v-model="selected_theme" v-for="(theme,index) in themes" :key="index" :label="theme" class="theme-items" :disabled="theme.hasTheme">
            <div>
                <div class="theme-title">主题{{ index + 1 }}：<strong>{{ theme.template_name }}</strong></div>
                <div class="theme-image"><el-image style="width:200px" fit="contain" :src="theme.template_img"></el-image></div>
                <div v-if="theme.template_content">适用场景：{{ theme.template_content }}</div>
                <div>
                    <span>购买时长</span>
                    <el-select style="width:154px;margin-left:10px" :disabled="theme.hasTheme" v-model="theme.current_variant_id" @change="changeVariant(theme)">
                        <el-option v-for="(variant,index) in theme.variants" :key="'v'+index" :value="variant.variant_id" :label="variant.time + '个月'"></el-option>
                    </el-select>
                </div>
                <div class="theme-price">价格：¥ <strong>{{ theme.current_price }}</strong></div>
            </div>
        </el-radio>
    </div>
    <div class="payment-list">
        <span style="margin-right:10px">支付方式：</span>
        <el-radio-group v-model="payment_method" size="small" :disabled="themes.filter((val)=>{return val.hasTheme}).length === themes.length">
            <el-radio-button v-for="(pay,index) in payments" :key="index" :label="pay.method_code">{{ pay.method_title }}</el-radio-button>
        </el-radio-group>
    </div>
    <div class="set-form-button">
        <el-button type="primary" @click="toPrev" plain>上一步</el-button>
        <el-button type="primary" @click="toPay" :disabled="themes.filter((val)=>{return val.hasTheme}).length === themes.length" :loading="payLoading">保存</el-button>
        <el-button type="primary" @click="toComplete" v-if="hasSet" plain>完成设置</el-button>
    </div>
    <el-dialog :visible="tipShow" title="提示" :show-close="false" :close-on-click-modal="false" :close-on-press-escape="false" center>
        <div class="dialog-tip">
            <div>付款过程中请勿关闭当前页面，付款成功后请点击“付款成功”按钮，即可创建商城并设置商城基础信息</div>
            <div>付款失败或取消付款，请点击“取消付款”按钮，关闭弹窗</div>
        </div>
        <div slot="footer">
            <el-button size="small" @click="tipShow = false">取消付款</el-button>
            <el-button size="small" type="primary" @click="validPay" :loading="btnLoading">付款成功</el-button>
        </div>
    </el-dialog>
    <!-- <pay-dialog
        :Visible="payVisible"
        :Title="payTitle"
        :payment="payment_method"
        :gotUrl="gotUrl"
        :url="url"
        @close="closePay">
    </pay-dialog> -->
</div>
</template>

<script>
import { get_themes } from '@/api/theme'
import { get_shop_themes } from '@/api/shop'
import { get_payments, create_order, get_order } from '@/api/backservice'
// import { create_order } from '@/api/backservice'
// import PayDialog from '@/components/PayDialog'
export default{
    // components:{
    //     PayDialog
    // },
    props:{
        shop:Object,
        activeIndex:Number
    },
    data(){
        return{
            loading:false,
            themes:[],
            height:0,
            selected_theme:{},
            selected_variant_id:'',
            payments:[],
            payment_method:'',
            payVisible:false,
            payTitle:'',
            gotUrl:false,
            url:'',
            current_theme:{},
            payLoading:false,
            tipShow:false,
            order_no:'',
            hasSet:false,
            btnLoading:false
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 377
        })
        window.onresize = () => {
            this.height = window.innerHeight - 377
        }
    },
    methods:{
        getThemes(){
            this.loading = true
            get_themes().then(r=>{
                if(r.data.body.length !== 0){
                    this.hasSet = true
                    this.current_theme = r.data.body.filter((val)=>{ return val.active })[0]
                }
                this.getPayments()
                get_shop_themes().then(res=>{
                    this.themes = res.data.body
                    this.loading = false
                    if(this.themes.length !== 0){
                        this.selected_theme = this.themes[0]
                        this.selected_variant_id = this.themes[0].variants[0].variant_id
                        let _themes = r.data.body
                        this.themes.map(v=>{
                            v['current_variant_id'] = v.variants[0].variant_id
                            v['current_price'] = v.variants[0].price
                            if(_themes.length !== 0){
                                if(_themes.filter((val)=>{ return val.template_id === v.template_id}).length !== 0) v['hasTheme'] = true
                                    else v['hasTheme'] = false
                            }else{
                                v['hasTheme'] = false
                            }
                        })
                    }
                }).catch(()=>{
                    this.loading = false
                })
            }).catch(()=>{
                this.loading = false
            })
        },
        getPayments(){
            get_payments().then(r=>{
                this.payments = r.data.body
                this.payment_method = this.payments[0].method_code
            })
        },
        changeVariant(theme){
            theme.current_price = theme.variants.filter((val)=>{return val.variant_id === theme.current_variant_id })[0].price
            this.selected_variant_id = theme.current_variant_id
        },
        toPrev(){
            this.$emit('prev')
        },
        toComplete(){
            this.$emit('complete')
        },
        toPay(){
            this.payLoading = true
            let _data = {
                shop_id:this.$store.getters.shop_id,
                payment_method: this.payment_method,
                item:{
                    type:'template',
                    item_id:this.selected_variant_id
                }
            }
            create_order(_data).then(r=>{
                this.order_no = r.data.body.no
                if(this.$store.getters.shop_level === '免费版'){
                    this.payLoading = false
                    this.hasSet = true
                    this.confirm()
                }else{
                    window.open(r.data.body.payment.pay_url,'_blank')
                    this.payLoading = false
                    this.tipShow = true
                }
            }).catch(()=>{
                this.payLoading = false
            })
        },
        validPay(){
            this.$loading()
            get_order(this.order_no).then(r=>{
                if(r.data.body.status_code === 'paid'){
                    this.$message.success({
                        message:'主题购买成功',
                        duration:1000
                    })
                    setTimeout(()=>{
                        this.confirm()
                        this.hasSet = true
                        this.$loading().close()
                    },1000)
                }else{
                    this.$message.error({
                        message:'未检测到成功的支付订单，请检查支付是否成功，如果确认支付成功，请联系客服核对并开通商城',
                        duration:4000
                    })
                    this.$loading().close()
                }
            }).catch(e=>{
                this.$message.error({
                    message:e.response.data.message,
                    duration:2000
                })
                this.$loading().close()
            })
        },
        closePay(){
            this.payVisible = false
            this.gotUrl = false
            this.url = ''
            this.payTitle = ''
        },
        confirm(){
            this.$emit('confirm')
        }
    },
    watch:{
        activeIndex:{
            deep:true,
            immediate:true,
            handler:function(){
                if(this.activeIndex === 2) this.getThemes()
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.set-title{
    font-size:$title-font-size;
    text-align:center;
    line-height:80px;
}
.set-theme-list{
    overflow-y:auto;
    margin-bottom:20px;
    padding: 0 20px;
}
.set-theme-list::-webkit-scrollbar{
    display:none;
}
.set-form-button{
    text-align:center;
}

.current-theme{
    line-height:20px;
    margin:0 48px 15px;
    color:$main-color;
}
.theme-items{
    width:248px;
    margin:0 28px 20px;
}
.theme-items .el-radio__label>div{
    display:inline-block;
}
.theme-title{
    margin-bottom:10px;
}
.theme-title strong{
    font-size:$large-font-size;
}
.theme-image{
    border:10px solid $line-color;
    border-bottom-width:20px;
    margin-bottom:10px;
}
.theme-price{
    margin-top:10px;
}
.theme-price strong{
    font-size:$large-font-size;
}
.payment-list{
    margin:0 48px 10px;
}
</style>

<style>
.theme-items .el-radio__input .el-radio__inner{
    border-radius:2px;
    width:18px;
    height:18px;
}
.theme-items .el-radio__input.is-checked .el-radio__inner::after{
    transform: rotate(45deg) scaleY(1);
}
.theme-items .el-radio__inner::after{
    box-sizing: content-box;
    content: "";
    border: 1px solid #fff;
    border-left: 0;
    border-top: 0;
    height: 10px;
    left: 5px;
    position: absolute;
    top: 0px;
    width: 5px;
    transform: rotate(45deg) scaleY(0);
    transition: transform .15s ease-in .05s;
    transform-origin: center;
    background:none;
    border-radius:0;
}
.theme-items .el-radio__input.is-disabled.is-checked .el-radio__inner::after{
    background:none;
}
.theme-items .el-radio__input,
.theme-items .el-radio__label{
    vertical-align:top;
}
</style>