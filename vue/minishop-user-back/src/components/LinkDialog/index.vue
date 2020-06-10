<template>
<el-dialog :visible="Visible" title="选择链接" width="80%" :before-close="close">
    <div>
        <ul class="link-select-menu">
            <li @click="changeItems(1)" :class="{'is-active':activeIndex === 1}">商品链接</li>
            <li @click="changeItems(2)" :class="{'is-active':activeIndex === 2}">分类链接</li>
            <li @click="changeItems(3)" :class="{'is-active':activeIndex === 3}">其他页面</li>
        </ul>
        <div v-loading="loading" style="min-height:100px">
            <div v-if="!loading && items.length === 0" style="text-align:center">暂无结果</div>
            <el-radio v-model="select" v-for="(item,index) in items" :key="index" :label="item" @change="changeSelect" class="link-select-radio">
                <el-image v-if="activeIndex === 1" :src="item.img" style="width:80px;height:80px;margin-right:10px"></el-image>
                <div style="width:calc(100% - 170px)">{{ item.name }}</div>
                <div style="width:40px;margin-left:10px" v-if="activeIndex === 1">{{ item.on_sale ? '上架' : '下架' }}</div>
            </el-radio>
        </div>
        <el-pagination
            v-if="activeIndex === 1"
            style="text-align:center;margin-top:10px"
            :current-change="query.page"
            :page-size="query.paegSize"
            :total="total"
            layout="total, prev, pager, next, jumper"
            @current-change="handleCurrentChange">
        </el-pagination>
    </div>
    <div slot="footer" style="text-align:center">
        <el-button @click="close">取消</el-button>
        <el-button @click="confirm" type="primary">确定</el-button>
    </div>
</el-dialog>
</template>

<script>
import { get_products } from '@/api/product'
import { get_categories } from '@/api/category'
export default{
    props:{
        Visible:Boolean
    },
    data(){
        return{
            loading:false,
            query:{
                page:1,
                pageSize:20,
                name:''
            },
            total:0,
            items:[],
            activeIndex:1,
            pages:[
                { name:'用户主页', link:'UserHome', type:'page' },
                { name:'用户订单页', link:'UserOrder', type:'page' },
                { name:'用户地址簿', link:'UserAddress', type:'page' },
                { name:'用户钱包', link:'UserWallet', type:'page' }
            ],
            link:{
                name:null,
                link:null,
                query:null
            },
            select:null
        }
    },
    methods:{
        changeItems(index){
            this.activeIndex = index
            this.items = []
            if(index === 1) this.getProducts()
                else if(index === 2) this.getTypes()
                    else this.getPages()
        },
        getProducts(){
            this.loading = true
            get_products(this.query).then(r=>{
                r.data.body.data.map(v=>{
                    this.items.push({
                        id:v.id,
                        img:v.img,
                        name:v.product_title,
                        on_sale:v.on_sale,
                        type:'product'
                    })
                })
                this.total = r.data.body.page.total
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        getTypes(){
            this.loading = true
            get_categories().then(r=>{
                r.data.body.map(v=>{
                    this.items.push({
                        id:v.id,
                        name:v.category_title,
                        type:'category'
                    })
                })
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        getPages(){
            this.loading = true
            setTimeout(()=>{
                this.items = this.pages
                this.loading = false
            },500)
        },
        handleCurrentChange(pagination){
            this.query.page = pagination
            this.getProducts()
        },
        close(){
            this.select = null
            this.$emit('close')
        },
        changeSelect(val){
            if(val.type === 'product'){
                this.link.query = val.id
                this.link.name = '商品链接：' + val.name
                this.link.link = 'Product'
            }else if(val.type === 'category'){
                this.link.query = val.id
                this.link.name = '分类链接：' + val.name
                this.link.link = 'Types'
            }else{
                this.link.query = null
                this.link.name = val.name
                this.link.link = val.link

            }
        },
        confirm(){
            this.$emit('confirm',this.link)
        }
    },
    watch:{
        Visible:{
            deep:true,
            handler:function(){
                if(this.Visible){
                    this.select = null
                    this.items = []
                    this.activeIndex = 1
                    this.getProducts()
                }
            }
        }
    }
}
</script>

<style>
.link-select-radio.el-radio:last-child{
    margin-right:10px;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.link-select-menu{
    margin:-20px 0 20px;
    padding:0;
    list-style:none;
    line-height:40px;
    border-bottom:1px solid $line-color;
}
.link-select-menu li{
    display:inline-block;
    width:80px;
    text-align:center;
    margin-right:20px;
}
.link-select-menu li:hover{
    cursor:pointer;
}
.link-select-menu li.is-active{
    color:$main-color;
}
.link-select-menu li.is-active:after{
    position:absolute;
    content:'';
    width:80px;
    margin-left:-68px;
    margin-top:39px;
    border-bottom:2px solid $main-color;
}

.link-select-radio{
    display:block;
    margin:0 10px 10px;
    padding-bottom:10px;
    border-bottom:1px dotted $line-color;
}
.link-select-radio .el-radio__label>div{
    display:inline-block;
    vertical-align:middle;
}
</style>