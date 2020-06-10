<template>
<div>
    <div class="sale-header">
        <el-button size="mini">返回</el-button>
        <el-button size="mini" type="primary" @click="toCreate">创建活动</el-button>
    </div>
    <div class="sale-content" v-loading="loading">
        <div style="min-height:400px">
            <div v-if="!loading && discounts.length === 0" class="loading-placeholder">暂无活动</div>
            <div v-for="(item,index) in discounts" :key="index" class="sale-content-item">
                <div class="sale-content-header">
                    <div style="width:calc(100% - 180px)"><i style="margin-right:5px" class="iconfont iconshijian"></i>活动时间：{{ item.start_time + ' - ' + item.end_time }}</div>
                    <div style="width:80px"><span class="sale-content-tag">{{ item.status_value }}</span></div>
                    <div style="width:100px"><el-button type="text" size="medium">查看活动详情</el-button></div>
                </div>
                <div>
                    <div style="width:calc(100% - 260px)">
                        <div>
                            <span class="sale-title-tag">{{ item.discount_type }}</span>
                            <span class="sale-title">{{ item.discount_name }}</span>
                        </div>
                        <div class="sale-title-items">参与商品：{{ item.items.length === 0 ? '全部商品' : (item.items.length + '个商品') }}</div>
                    </div>
                    <div style="width:260px">
                        <el-button size="small">修改活动</el-button>
                        <el-button size="small">添加商品</el-button>
                        <el-button size="small">移除商品</el-button>
                    </div>
                </div>
                <div class="sale-content-footer">备注：{{ item.discount_desc }}</div>
            </div>
        </div>
        <el-pagination
            style="margin:20px 0;text-align:center"
            :current-page="query.page"
            :page-size="query.pageSize"
            :total="total"
            layout="total, prev, pager, next, jumper"
            @current-change="handlerCurrentChange">
        </el-pagination>
    </div>
</div>
</template>

<script>
export default{
    data(){
        return{
            loading:false,
            discounts:[],
            query:{
                page:1,
                pageSize:15
            },
            total:0
        }
    },
    created(){
        this.getDiscounts()
    },
    methods:{
        getDiscounts(){
            this.loading = true
            setTimeout(()=>{
                this.loading = false
                this.discounts = [
                    { discount_name:'新品折扣', start_time:'2020-05-15', end_time:'2020-06-15', items:[], discount_type:'满元减', discount_type_code:'price_discount', status_value:'进行中', discount_desc:'xx商品活动' }
                ]
            },1000)
        },
        handlerCurrentChange(pagination){
            this.query.page = pagination
            this.getDiscounts()
        },
        toCreate(){
            this.$router.push({name:'DiscountAdd'})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.sale-header{
    padding:10px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:5px;
    margin-bottom:10px;
}
.sale-content{
    padding:10px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:5px;
}
.sale-content-item{
    border:1px solid $line-color * 0.95;
    border-radius:5px;
}
.sale-content-item>div>div{
    display:inline-block;
    vertical-align:top;
}
.sale-content-item>div{
    padding:10px;
}
.sale-content-header{
    background-color:$light-back-sub;
    padding:5px 10px!important;
    border-bottom:1px solid $line-color * 0.95;
}
.sale-content-header>div{
    vertical-align:middle!important;
}
.sale-content-tag{
    padding:2px 7px;
    font-size:12px;
    color:#fff;
    background-color:$dark-back-sub;
    border-radius:3px;
}
.sale-title-tag{
    padding:2px 5px;
    font-size:12px;
    color:#fff;
    background-color:$error-color;
    border-radius:3px;
    margin-right:10px;
}
.sale-title{
    font-weight:500;
    font-size:15px;
}
.sale-title-items{
    margin-left:43px;
    margin-top:10px;
    color:$sub-font-color;
}
.sale-content-footer{
    border-top:1px solid $line-color * 0.98;
    margin-top:10px;
    color:$sub-font-color * 1.2;
}
</style>