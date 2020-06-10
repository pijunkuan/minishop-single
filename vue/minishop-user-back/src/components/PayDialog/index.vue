<template>
<el-dialog :visible="Visible" :title="Title" width="400px" :show-close="false" :close-on-click-modal="false" :close-on-press-escape="false">
    <div v-if="!gotUrl" style="text-align:center"><i class="el-icon-loading" style="margin-right:5px;font-size:20px"></i>正在获取二维码</div>
    <div v-if="gotUrl">
        <div>请打开{{ payment === 'alipay' ? '支付宝' : '微信' }}，扫描下方二维码进行付款</div>
        <div>
            <iframe v-if="gorUrl" :src="url" width="210" height="220"></iframe>
        </div>
    </div>
    <div slot="footer" style="text-align:center">
        <el-button size="medium" type="primary" @click="close">取消付款</el-button>
    </div>
</el-dialog>
</template>

<script>
export default{
    props:{
        Visible:Boolean,
        Title:String,
        gotUrl:Boolean,
        payment:String,
        url:String
    },
    methods:{
        close(){
            this.$emit('close')
        }
    }
}
</script>