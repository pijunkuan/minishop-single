<template>
<div>
    <div class="set-title">商城基础设置</div>
    <div class="set-image" :style="{height:height + 'px'}"><img src="@/assets/images/marginalia-online-shopping.png" /></div>
    <div class="set-form">
        <div class="set-form-item">
            <div>商城名称</div>
            <div>
                <el-input v-model="shop.shop_name"></el-input>
            </div>
        </div>
        <div class="set-form-item">
            <div>域名地址</div>
            <div>
                <el-input :disabled="shop.shop_level === '免费版' ? true : false" v-model="shop.shop_url">
                    <span slot="append">.{{ shop.shop_host }}</span>
                </el-input>
                <div class="set-tip" v-if="shop.shop_level === '免费版'">免费版域名地址自动生成，如需自定义域名地址，可在商城后台中升级</div>
            </div>
        </div>
        <div class="set-form-button">
            <el-button type="primary" :loading="btnLoading" @click="confirm">下一步</el-button>
        </div>
    </div>
</div>
</template>

<script>
export default{
    props:{
        shop:Object,
        btnLoading:Boolean
    },
    data(){
        return{
            height:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            if(window.innerHeight - 482 > 100) this.height = window.innerHeight - 482
                else this.height = 100
        })
        window.onresize = () => {
            if(window.innerHeight - 482 > 100) this.height = window.innerHeight - 482
                else this.height = 100
        }
    },
    methods:{
        confirm(){
            this.$emit('confirm')
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
.set-image img{
    width:100%;
    height:100%;
    object-fit:contain;
}
.set-form{
    margin:0 260px 40px;
}
.set-form-item{
    margin:15px 0;
}
.set-form-item>div{
    display:inline-block;
    vertical-align:top;
}
.set-form-item>div:first-child{
    width:70px;
    padding-top:10px;
}
.set-form-item>div:last-child{
    width:calc(100% - 70px);
}
.set-form-button{
    margin-top:30px;
    text-align:center;
}
.set-tip{
    color:$warning-color;
    margin-top:10px;
    font-size:$small-font-size;
}
</style>