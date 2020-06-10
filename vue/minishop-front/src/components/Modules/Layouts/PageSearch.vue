<template>
<div class="searchbar">
    <div
        class="search-bar"
        :class="{'is-searching':visible}">
        <form action="" v-on:submit.prevent="">
            <shop-input
                ref="shopSearch"
                :placeholder="'大家都在搜 ' + placeholder"
                type="search"
                size="small" 
                v-model="search"
                @focus="handleFocus"
                @keyup.enter.native="getSearch">
                <shop-icon slot="prepend" size="mini" name="sousuo"></shop-icon>
            </shop-input>
        </form>
    </div>
    <div v-if="visible" class="search-btn">
        <span @click="handelCancel">取消</span>
    </div>
</div>
</template>

<script>
export default{
    data(){
        return{
            placeholder:'',
            search:'',
            visible:false
        }
    },
    methods:{
        handleFocus(){
            this.visible = true
        },
        handelCancel(){
            this.$refs.shopSearch.clear()
            this.search = ''
            this.visible = false
        },
        getSearch(){
            this.$router.replace({name:'Search', query:{ search: this.search }}).catch(()=>{})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.searchbar{
    width:calc(100% - 20px);
    padding:7px 10px;
    height:35px;
    @include base-background(1);
}
.search-bar,.search-btn{
    display:inline-block;
    vertical-align:middle;
}
.search-bar{
    width:100%;
}
.search-bar.is-searching{
    width:calc(100% - 40px);
}
.search-btn{
    width:40px;
    font-size:14px;
    color:$line-color;
    text-align:center;
}
</style>
