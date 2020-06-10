import Vue from 'vue'
import Router from 'vue-router'

import Layout from '@/components/Layout'

Vue.use(Router)

export const allRoutes = [
	{
		path:'/login',
		name:'Login',
		component: r => require(['./pages/Login/index.vue'],r)
	},
	{
		path:'/',
		hidden:true,
		redirect:'/dashboard'
	},
	{
		path:'/dashboard',
		hidden:false,
		meta:{ title:'首页', icon:'iconshouye' },
		component: Layout,
		children:[
			{ path:'', name:'Dashboard', component: r => require(['./pages/Home/index.vue'],r), hidden:false, meta:{ title:'首页', icon:'iconshouye' }}
		]
	},
	{
		path:'/order',
		hidden:false,
		meta:{ title:'订单管理', icon:'icondingdan' },
		component: Layout,
		children:[
			{ path:'list', name:'OrderList', component: r => require(['./pages/Orders/OrderList.vue'],r), meta:{ title:'订单管理', icon:'icondingdan', keepAlive:true }, hidden:false },
			{ path:'detail', name:'OrderDetail', component: r => require(['./pages/Orders/OrderDetail.vue'],r), meta:{ title:'订单详情', icon:'icondingdan', keepAlive:false }, hidden:true }
		]
	},
	{
		path:'/product',
		hidden:false,
		meta:{ title:'商品管理', icon:'iconliwu' },
		component: Layout,
		children:[
			{ path:'list', name:'ProductList', component: r => require(['./pages/Products/ItemList.vue'],r), meta:{ title:'商品管理', icon:'iconliwu', keepAlive:true }, hidden:false },
			{ path:'edit', name:'ProductItem', component: r => require(['./pages/Products/ItemEdit.vue'],r), meta:{ title:'商品修改', icon:'iconliwu', keepAlive:false }, hidden:true },
			{ path:'add', name:'ProductAdd', component: r => require(['./pages/Products/ItemAdd.vue'],r), meta:{ title:'新增商品', icon:'iconliwu', keepAlive:false }, hidden:true },
			{ path:'category', name:'SetCategory', component: r => require(['./pages/Products/SetCategory.vue'],r), meta:{ title:'商品分类', icon:'iconfuzhi' }, hidden:false }
		]
	},
	{
		path:'/customer',
		hidden:false,
		meta:{ title:'顾客管理', icon:'iconshequ'},
		component: Layout,
		children:[
			{ path:'list', name:'CustomerList', component: r => require(['./pages/Customer/CustomerList.vue'],r), meta:{ title:'顾客管理', icon:'iconshequ' }, hidden:false },
		]
	},
	{
		path:'/theme',
		hidden:false,
		meta:{ title:'主题管理', icon:'iconfaxian'},
		component: Layout,
		children:[
			{ path:'list', name:'ThemeList', component: r => require(['./pages/Theme/ThemeList.vue'],r), meta:{ title:'主题管理', icon:'iconfaxian' }, hidden:false },
			{ path:'setting', name:'ThemeSetting', component: r => require(['./pages/Theme/ThemeSetting.vue'],r), meta:{ title:'主题设置', icon:'iconfaxian' }, hidden:true }
		]
	},
	{
		path:'/image',
		hidden:false,
		meta:{ title:'图片管理', icon:'icontupian'},
		component: Layout,
		children:[
			{ path:'', name:'ImageList', component: r => require(['./pages/Images/ImageList.vue'],r), meta:{ title:'图片管理', icon:'icontupian' }, hidden:false }
		]
	},
	{
		path:'/settings',
		hidden:false,
		meta:{ title:'基础设置', icon:'iconshezhi'},
		component: Layout,
		children:[
			{ path:'basic', name:'SetBasic', component: r => require(['./pages/Setting/SetBasic.vue'],r), meta:{ title:'网站设置', icon:'iconshezhi' }, hidden:false },
			{ path:'message', name:'SetMessage', component: r => require(['./pages/Setting/SetMessage.vue'],r), meta:{ title:'短信通知', icon:'icontaolunqu' }, hidden:false },
			{ path:'payment', name:'SetPayment', component: r => require(['./pages/Setting/SetPayment.vue'],r), meta:{ title:'收款方式', icon:'iconyinhangka' }, hidden:false },
			{ path:'shipment', name:'SetShip', component: r => require(['./pages/Setting/SetShip.vue'],r), meta:{ title:'运费模版', icon:'icondingwei' }, hidden:false }
		]
	},
	{
		path:'/user',
		hidden:true,
		meta:{title:'个人设置',icon:"iconxiaolian"},
		component:Layout,
		children:[
			{ path:'edit',name:'UserEdit',component:r=> require(['./pages/User/Edit.vue'],r),meta:{title:'个人设置'}, hidden:false}
		]
	}
]

export default new Router({
	mode:'history',
	routes:allRoutes
})