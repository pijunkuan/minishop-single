<template>
<el-dialog :visible="Visible" :title="Title" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
    <el-progress :text-inside="true" :stroke-width="20" :percentage="percentage"></el-progress>
    <div style="text-align:right;margin-top:20px;">已处理<strong>{{ Index }}</strong>条，共<strong>{{ Total }}</strong>条</div>
</el-dialog>
</template>

<script>
export default{
    props:{
        Visible:Boolean,
        Title:String,
        Index:Number,
        Total:Number
    },
    data(){
        return{
            percentage:0
        }
    },
    watch:{
        Index:{
            deep:true,
            immediate:true,
            handler:function(){
                if(this.Total){
                    this.percentage = (this.Index / this.Total * 100).toFixed(2) * 1
                }else{
                    this.percentage = 0
                }
            }
        }
    }
}
</script>