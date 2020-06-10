<template>
<div>
    <div class="search-header">
        <span>搜索结果：</span>
        <span class="search-header-content">{{ fetchQuery.name }}</span>
    </div>
    <infinite-list
        :items="items"
        :loading="loading"
        :nomore="nomore"
        :height="height"
        @load="getItems"></infinite-list>
</div>
</template>

<script>
import InfiniteList from '@/components/Modules/ProductList/InfiniteList'
import { get_products } from '@/api/products'
export default{
    components:{
        InfiniteList
    },
    data(){
        return{
            fetchQuery:{
                page:0,
                pageSize:15,
                Q4S:1,
                name:''
            },
            loading:false,
            nomore:false,
            items:[],
            height:0
        }
    },
    created(){
        this.fetchQuery.name = this.$route.query.search
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 165
        })
        window.onresize = () => {
            this.height = window.innerHeight - 165
        }
    },
    beforeRouteLeave(to,from,next){
        if(to.name === 'Product') from.meta.keepAlive = true
            else from.meta.keepAlive = false
        next()
    },
    methods:{
        getItems(){
            this.loading = true
            this.nomore = false
            this.fetchQuery.page += 1
            get_products(this.fetchQuery).then(r=>{
                let _data = r.data.body.data
                if(_data.length === 0){
                    this.loading = true
                    this.nomore = true
                }else{
                    this.items = this.items.concat(_data)
                    this.loading = false
                    this.nomore = false
                }
            }).catch(()=>{
                this.loading = true
                this.nomore = true
            })
        }
    },
    watch:{
        '$route'(nv,ov){
            if(nv !== ov){
                this.fetchQuery.name = this.$route.query.search
                this.items = []
                this.fetchQuery.page = 0
                this.getItems()
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.search-header{
    line-height:40px;
    padding:0 10px;
    margin-bottom:10px;
    font-size:$middle-font-size;
    color:$sub-font-color;
    background-color:#fff;
}
.search-header-content{
    color:$main-font-color;
}
</style>