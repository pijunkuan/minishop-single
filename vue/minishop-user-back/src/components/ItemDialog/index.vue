<template>
<el-dialog :visible="Visible" title="选择商品" :before-close="close" width="80%">
    <div>
        <el-input style="width:calc(100% - 110px);margin-right:10px" v-model="query.name" placeholder="请输入商品名称搜索">
            <el-button slot="append" @click="search('search')">搜索</el-button>
        </el-input>
        <el-button type="primary" @click="search('all')">查看全部</el-button>
    </div>
    <div v-loading="loading">
        <div v-if="items.length === 0" class="loading-placeholder">暂无结果</div>
        <div class="item-dialog-header">
            <div style="width:80px">图片</div>
            <div style="width:calc(100% - 330px)">商品标题</div>
            <div style="width:100px">售价</div>
            <div style="width:50px">上下架</div>
            <div style="width:50px">操作</div>
        </div>
        <div v-for="(item,index) in items" :key="index" class="item-dialog-item" :class="{'is-select':selectId.indexOf(item.id) !== -1 || Current.indexOf(item.id) !== -1}">
            <el-image style="width:80px;height:80px" :src="item.img"></el-image>
            <div style="width:calc(100% - 330px)">{{ item.product_title }}</div>
            <div style="width:100px">¥ <strong>{{ item.price }}</strong></div>
            <div style="width:50px">{{ item.on_sale ? '上架' : '下架' }}</div>
            <div style="width:50px">
                <span v-if="Current.indexOf(item.id) !== -1">已选择</span>
                <el-button v-else style="cursor:pointer" type="text" size="mini" @click="changeSelect(item)">{{ selectId.indexOf(item.id) === -1 ? '选择' : '移除' }}</el-button>
            </div>
        </div>
    </div>
    <el-pagination
        style="margin-top:20px;text-align:center"
        :current-page="query.page"
        :page-size="query.pageSize"
        :total="total"
        layout="total, prev, pager, next, jumper"
        @current-change="handleCurrentChange">
    </el-pagination>
    <div slot="footer" style="text-align:center">
        <el-button @click="close">取消</el-button>
        <el-button type="primary" @click="confirm">确定</el-button>
    </div>
</el-dialog>
</template>

<script>
import { get_products } from '@/api/product'
export default{
    props:{
        Visible:Boolean,
        Current:Array
    },
    data(){
        return{
            loading:false,
            items:[],
            total:0,
            query:{
                page:1,
                pageSize:15,
                name:''
            },
            selectId:[],
            selectItem:[]
        }
    },
    methods:{
        getItems(){
            this.loading = true
            get_products(this.query).then(r=>{
                this.items = r.data.body.data
                this.total = r.data.body.page.total
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        handleCurrentChange(pagination){
            this.query.page = pagination
            this.getItems()
        },
        search(type){
            if(type === 'all') this.query.name = ''
            this.query.page = 1
            this.getItems()
        },
        changeSelect(item){
            const _index = this.selectId.indexOf(item.id)
            if(_index === -1){
                this.selectId.push(item.id)
                this.selectItem.push(item)
            }else{
                this.selectId.splice(_index,1)
                this.selectItem.splice(_index,1)
            }
        },
        close(){
            this.$emit('close')
        },
        confirm(){
            if(this.selectId.length === 0){
                this.$message.warning('请先选择商品')
                return
            }
            this.$emit('confirm',this.selectItem)
        }
    },
    watch:{
        Visible:{
            deep:true,
            handler:function(){
                if(this.Visible){
                    this.selectId = []
                    this.selectItem = []
                    this.getItems()
                }
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.item-dialog-header,.item-dialog-item{
    margin:10px 0;
    padding:10px;
    border-bottom:1px dotted $line-color;
}
.item-dialog-header>div,.item-dialog-item>div{
    display:inline-block;
    vertical-align:middle;
    margin-right:10px;
}
.item-dialog-item.is-select{
    background-color:$background-color;
}
</style>