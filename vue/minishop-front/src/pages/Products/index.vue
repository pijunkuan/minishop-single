<template>
<div>
    <infinite-list
        :loading="loading"
        :nomore="noresult"
        :items="items"
        :height="height"
        @load="getItems"></infinite-list>
</div>
</template>

<script>
import InfiniteList from '@/components/Modules/ProductList/InfiniteList'
export default{
    components:{
        InfiniteList
    },
    data(){
        return{
            height:0,
            loading:false,
            noresult:false,
            items:[]
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 114
        })
        window.onresize = () => {
            this.height = window.innerHeight - 114
        }
    },
    methods:{
        getItems(){
            setTimeout(()=>{
                let _data = []
                for(let i = 0; i<= 10; i++){
                    _data.push(i)
                }
                if(_data.length === 0){
                    this.loading = true
                    this.noresult = true
                }else{
                    this.loading = false
                    this.noresult = false
                    this.items = this.items.concat(_data)
                }
            },1000)
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.page-block{
    margin:10px 0;
}

.home-menu-block{
    background-color:#fff;
    padding:15px 10px;
}
.home-menu-block>div{
    display:inline-block;
    width:60px;
    vertical-align:middle;
    text-align:center;
    font-size:$small-font-size;
    color:$sub-font-color;
}
.home-menu-block__placeholder{
    margin-bottom:5px;
    width:60px;
    height:60px;
    background-color:#000;
    border-radius:50%;
}
</style>