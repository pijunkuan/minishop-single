<template>
<div 
    v-infinite-scroll="getItems"
    infinite-scroll-disabled="infLoading"
    infinite-scroll-distance="10"
    infinite-scroll-immediate-check>
    <page-loading :loading="loading"></page-loading>
    <img-carousel :images="banners" type="cover" :height="bannerHeight"></img-carousel>
    <div class="page-block home-menu-block">
        <div v-for="(menu,index) in menus.items" :key="index" :style="{marginLeft: ((innerWidth - 20) / 4 - 60) / 2 + 'px', marginRight:((innerWidth - 20) / 4 - 60) / 2 + 'px'}" @click="toPage(menu)">
            <div v-if="menu.img">
                <shop-image :width="60" :src="menu.img" circled type="stretch"></shop-image>
            </div>
            <div v-else class="home-menu-block__placeholder" :style="{backgroundColor:menu.color}"></div>
            <div style="margin-top:8px">{{ menu.title }}</div>
        </div>
    </div>
    <marquee-msg v-if="message" class="page-block" :message="message"></marquee-msg>
    <item-block-limit
        class="page-block"
        title="限时打折"
        v-if="limit.visible"
        :loading="limitLoading"
        :endTime="limit.endTime"
        :items="limit.items"></item-block-limit>
    <item-block-column
        class="page-block"
        title="今日上新"
        v-if="newitem.visible"
        :loading="colLoading"
        :items="newitem.items"></item-block-column>
    <item-block-mono
        v-if="ad.visible"
        class="page-block"
        :item="ad"
        :loading="adLoading"></item-block-mono>
    <img-txt-horizon
        v-if="scrollMenu.length !== 0"
        type="totaltext"
        :items="scrollMenu"
        :loading="menuLoading"></img-txt-horizon>
    <div class="text-title">你可能会喜欢</div>
    <home-infinite-list
        :nomore="noresult"
        :items="infItems">
    </home-infinite-list>
</div>
</template>

<script>
import ImgCarousel from '@/components/Modules/ImgTxtBlock/ImgCarousel'
import MarqueeMsg from '@/components/Modules/ImgTxtBlock/MarqueeMsg'
import ItemBlockLimit from '@/components/Modules/ItemBlock/ItemBlockLimit'
import ItemBlockColumn from '@/components/Modules/ItemBlock/ItemBlockColumn'
import ItemBlockMono from '@/components/Modules/ItemBlock/ItemBlockMono'
import ImgTxtHorizon from '@/components/Modules/ImgTxtBlock/ImgTxtHorizon'
import HomeInfiniteList from '@/components/Modules/ProductList/HomeInfiniteList'
import { get_products } from '@/api/products'
import { get_template } from '@/api/template'
export default{
    components:{
        ImgCarousel,
        MarqueeMsg,
        ItemBlockLimit,
        ItemBlockColumn,
        ItemBlockMono,
        ImgTxtHorizon,
        HomeInfiniteList
    },
    data(){
        return{
            loading:false,
            bannerHeight:0,
            banners:[],
            menus:{
                items:[],
                visible:false
            },
            innerWidth:0,
            message:'',
            limitLoading:false,
            limit:{
                visible:false,
                endTime:null,
                items:[]
            },
            colLoading:false,
            newitem:{
                visible:false,
                items:[]
            },
            ad:{
                visible:false,
                img:'',
                link:{},
                ori_price:'',
                price:'',
                sub_title:'',
                title:'',
            },
            adLoading:false,
            scrollMenu:[],
            menuLoading:false,

            width:0,
            infHeight:0,

            infLoading:false,
            noresult:false,
            infItems:[],
            fetchQuery:{
                page:0,
                Q4S:1,
                pageSize:4
            }
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.infHeight = window.innerHeight - 115
            this.width = window.innerWidth / 2 - 10
            this.innerWidth = window.innerWidth
            this.bannerHeight = window.innerWidth * 500 / 750
        })
        window.onresize = () => {
            this.infHeight = window.innerHeight - 115
            this.width = window.innerWidth / 2 - 10
            this.innerWidth = window.innerWidth
            this.bannerHeight = window.innerWidth * 500 / 750
        }
    },
    created(){
        this.getTemplate()
    },
    methods:{
        getItems(){
            this.infLoading = true
            this.noresult = false
            this.fetchQuery.page += 1
            get_products(this.fetchQuery).then(r=>{
                let _data = r.data.body.data
                if(_data.length === 0){
                    this.infLoading = true
                    this.noresult = true
                }else{
                    this.infLoading = false
                    this.noresult = false
                    this.infItems = this.infItems.concat(_data)
                }
            }).catch(()=>{
                this.infLoading = true
                this.noresult = true
            })
        },
        getTemplate(){
            this.loading = true
            get_template().then(r=>{
                let _data = r.data.body
                if(_data.banners !== undefined) this.banners = _data.banners
                if(_data.message !== undefined && _data.message) this.message = _data.message
                if(_data.types !== undefined && _data.types.visible) this.menus = _data.types
                if(_data.limit !== undefined && _data.limit.visible) this.limit = _data.limit 
                if(_data.newitem !== undefined && _data.newitem.visible) this.newitem = _data.newitem 
                if(_data.ad !== undefined && _data.ad.visible) this.ad = _data.ad
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        toPage(menu){
            if(menu.link === undefined || !menu.link.link) return
            if(menu.link.link === 'Product'){
                this.$router.push({name:menu.link.link, query:{id:menu.link.query}})
            }else if(menu.link.link === 'Types'){
                this.$router.push({name:menu.link.link, params:{id:menu.link.query}})
            }else{
                this.$router.push({name:menu.link.link})
            }
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
.text-title{
    padding:0 10px;
    line-height:40px;
    background-color:#fff;
    font-size:$middle-font-size;
    color:$main-font-color;
}
</style>