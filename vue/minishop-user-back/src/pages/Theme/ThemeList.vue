<template>
<div>
    <div class="template-block" v-loading="loading">
        <div class="template-block-title">
            <div style="width:calc(100% - 96px)">当前主题</div>
            <div style="width:96px;text-align:right"><el-button size="medium" type="text">前往主题商城</el-button></div>
        </div>
        <div class="template-current-block">
            <div style="margin-bottom:10px">
                <span class="template-name">{{ currentTheme.template_name }}</span>
                <el-button size="mini" type="primary" @click="toSet">设置主题</el-button>
            </div>
            <div class="template-desc">添加时间：{{ currentTheme.created_at }}</div>
            <div class="template-desc">到期时间：{{ currentTheme.exp_at }}</div>
        </div>
        <div class="template-block-title" style="margin-top:40px">我的其他可用主题</div>
        <div style="min-height:300px">
            <div v-if="!loading && themes.length === 0" class="loading-placeholder">
                <div>暂无其他可用主题<el-button size="mini" plain style="margin-left:10px">前往主题商城</el-button></div>
            </div>
            <div v-for="(item,index) in themes" :key="index" class="template-current-block">
                <div style="margin-bottom:10px">
                    <span class="template-name">{{ item.template_name }}</span>
                    <el-button size="small" type="primary">使用主题</el-button>
                </div>
                <div class="template-desc">添加时间：{{ item.created_at }}</div>
                <div class="template-desc">到期时间：{{ item.exp_at }}</div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { get_themes } from '@/api/theme'
export default{
    data(){
        return{
            currentTheme:{},
            themes:[],
            loading:false
        }
    },
    created(){
        this.getThemes()
    },
    methods:{
        getThemes(){
            this.loading = true
            get_themes().then(r=>{
                this.themes = r.data.body
                this.themes.map((v,n)=>{
                    if(v.active){
                        this.currentTheme = v
                        this.themes.splice(n,1)
                    }
                })
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        toSet(){
            this.$router.push({name:'ThemeSetting',query:{id:this.currentTheme.id,name:this.currentTheme.template_name}})
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
    padding:10px 20px;
    border:1px solid $line-color * 0.9;
    border-radius:10px;
    background-color:$light-back-sub * 0.995;
    width:240px;
}
.template-name{
    font-size:15px;
    margin-right:15px;
    font-weight:500;
    position:relative;
    top:2px;
}
.template-desc{
    color:$other-font-color;
    font-size:$small-font-size;
}
</style>