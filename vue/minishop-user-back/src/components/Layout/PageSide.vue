<template>
<div class="main-sidebar" :class="{'is-collapse':isCollapse}" :style="{height:height + 'px'}">
    <el-menu :collapse="isCollapse" :default-active="'m'+$route.meta.title">
        <div v-for="(menu,index) in menus" :key="index">
            <el-submenu v-if="menu.children !== undefined" :index="'m'+menu.title">
                <template slot="title">
                    <i class="iconfont" :class="menu.icon"></i>
                    <span slot="title">{{ menu.title }}</span>
                </template>
                <el-menu-item v-for="(sub,Index) in menu.children" :key="'s'+Index" :index="'m'+sub.title" @click="toPage(sub)">
                    <span>{{ sub.title }}</span>
                </el-menu-item>
            </el-submenu>
            <el-menu-item v-if="menu.children === undefined" :index="'m'+menu.title" @click="toPage(menu)">
                <i class="iconfont" :class="menu.icon"></i>
                <span>{{ menu.title }}</span>
            </el-menu-item>
        </div>
    </el-menu>
</div>
</template>

<script>
import { allRoutes } from '@/router'
export default{
    props:{
        isCollapse:Boolean,
        height:Number
    },
    data(){
        return{
            menus:[]
        }
    },
    created(){
        this.getMenu()
    },
    methods:{
        getMenu(){
            let routes = allRoutes.filter((val)=>{return !val.hidden})
            routes.map(v=>{
                if(v.children !== undefined){
                    v.children = v.children.filter((val)=>{ return !val.hidden })
                    if(v.children.length === 1){
                        let item = {
                            title:v.children[0].meta.title,
                            link:v.children[0].name,
                            icon:v.children[0].meta.icon
                        }
                        this.menus.push(item)
                    }else{
                        let item = {
                            title:v.meta.title,
                            icon:v.meta.icon,
                            children:[]
                        }
                        let temp = {}
                        v.children.map(t=>{
                            temp['title'] = t.meta.title
                            temp['icon'] = t.meta.icon
                            temp['link'] = t.name
                            item.children.push(temp)
                            temp = {}
                        })
                        this.menus.push(item)
                    }
                }
            })

        },
        toPage(menu){
            this.$router.replace({ name: menu.link }).catch(()=>{})
        }
    }
}
</script>

<style scoped lang="scss">
@import '@/assets/style/base.scss';
.main-sidebar{
    display:inline-block;
    vertical-align:top;
    width:165px;
    overflow-y:auto;
    overflow-x:hidden;
}
.main-sidebar::-webkit-scrollbar{
    display:none;
}
.main-sidebar:after{
    position:absolute;
    content:'';
    left:165px;
    height:calc(100% - 51px);
    top:51px;
    border-right:1px solid $light-back-main * 0.95;
}
.main-sidebar.is-collapse:after{
    left:64px;
}
.main-sidebar.is-collapse{
    width:65px;
}
.main-sidebar,.main-sidebar ul{
    height:100%;
    background-color:$light-back-sub;
}
</style>
<style lang="scss">
@import '@/assets/style/base.scss';
.main-sidebar .el-menu-item.is-active{
    background-color:$light-back-main * 0.95;
}
.el-menu{
    border-right:none!important;
}
</style>