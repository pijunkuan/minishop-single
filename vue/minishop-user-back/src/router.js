import Vue from 'vue'
import Router from 'vue-router'

import Layout from '@/components/Layout'

Vue.use(Router)

export const allRoutes = [
	{
		path:'/center/session',
		hidden:true,
		component: r => require(['./pages/Redirect/index.vue'],r)
	},
	{
		path:'/center/init',
		hidden:true,
		name:'ShopInit',
		component: r => require(['./pages/Init/index.vue'],r)
	},
	{
		path:'/',
		hidden:true,
		redirect:'/center/dashboard'
	},
	{
		path:'/center',
		hidden:true,
		redirect:'/center/dashboard'
	},
	{
		path:'/center/dashboard',
		hidden:false,
		meta:{ title:'首页', icon:'iconshouye' },
		component: Layout,
		children:[
			{ path:'', name:'Dashboard', component: r => require(['./pages/Home/index.vue'],r), hidden:false, meta:{ title:'首页', icon:'iconshouye' }}
		]
	},
	{
		path:'/center/certify',
		hidden:true,
		meta:{ title:'认证', icon:'iconrenzheng' },
		component: Layout,
		children:[
			{ path:'', name:'Certify', component: r => require(['./pages/Certify/index.vue'],r), hidden:true, meta:{ title:'认证', icon:'iconrenzheng' }}
		]
	},
	{
		path:'/center/order',
		hidden:false,
		meta:{ title:'订单管理', icon:'icondingdan' },
		component: Layout,
		children:[
			{ path:'list', name:'OrderList', component: r => require(['./pages/Orders/OrderList.vue'],r), meta:{ title:'订单管理', icon:'icondingdan', keepAlive:true }, hidden:false },
			{ path:'detail', name:'OrderDetail', component: r => require(['./pages/Orders/OrderDetail.vue'],r), meta:{ title:'订单详情', icon:'icondingdan', keepAlive:false }, hidden:true }
		]
	},
	{
		path:'/center/product',
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
		path:'/center/customer',
		hidden:false,
		meta:{ title:'顾客管理', icon:'iconshequ'},
		component: Layout,
		children:[
			{ path:'list', name:'CustomerList', component: r => require(['./pages/Customer/CustomerList.vue'],r), meta:{ title:'顾客管理', icon:'iconshequ' }, hidden:false },
		]
	},
	{
		path:'/center/sale',
		hidden:true,
		meta:{ title:'营销管理', icon:'iconkanjia'},
		component: Layout,
		children:[
			{ path:'coupon/list', name:'CouponHome', component: r => require(['./pages/Sale/Coupon/CouponList.vue'],r), meta:{ title:'优惠券', icon:'iconyouhuiquan' }, hidden:false },
			{ path:'coupon/edit', name:'CouponEdit', component: r => require(['./pages/Sale/Coupon/CouponEdit.vue'],r), meta:{ title:'优惠券修改', icon:'iconyouhuiquan' }, hidden:true },
			{ path:'coupon/add', name:'CouponAdd', component: r => require(['./pages/Sale/Coupon/CouponAdd.vue'],r), meta:{ title:'优惠券添加', icon:'iconyouhuiquan' }, hidden:true },

			{ path:'discount/list', name:'DiscountHome', component: r => require(['./pages/Sale/Discount/DiscountList.vue'],r), meta:{ title:'满减满折', icon:'iconkanjia' }, hidden:false },
			{ path:'discount/edit', name:'DiscountEdit', component: r => require(['./pages/Sale/Discount/DiscountEdit.vue'],r), meta:{ title:'修改满减满折', icon:'iconkanjia' }, hidden:true },
			{ path:'discount/add', name:'DiscountAdd', component: r => require(['./pages/Sale/Discount/DiscountAdd.vue'],r), meta:{ title:'创建满减满折', icon:'iconkanjia' }, hidden:true },

			{ path:'match/list', name:'MatchHome', component: r => require(['./pages/Sale/Match/MatchList.vue'],r), meta:{ title:'搭配宝', icon:'iconmaiyizengyi' }, hidden:false },
			{ path:'match/edit', name:'MatchEdit', component: r => require(['./pages/Sale/Match/MatchEdit.vue'],r), meta:{ title:'搭配宝修改', icon:'iconmaiyizengyi' }, hidden:true },
			{ path:'match/add', name:'MatchAdd', component: r => require(['./pages/Sale/Match/MatchAdd.vue'],r), meta:{ title:'搭配宝添加', icon:'iconmaiyizengyi' }, hidden:true }
		]
	},
	{
		path:'/center/theme',
		hidden:false,
		meta:{ title:'主题管理', icon:'iconfaxian'},
		component: Layout,
		children:[
			{ path:'list', name:'ThemeList', component: r => require(['./pages/Theme/ThemeList.vue'],r), meta:{ title:'主题管理', icon:'iconfaxian' }, hidden:false },
			{ path:'setting', name:'ThemeSetting', component: r => require(['./pages/Theme/ThemeSetting.vue'],r), meta:{ title:'主题设置', icon:'iconfaxian' }, hidden:true }
		]
	},
	{
		path:'/center/plugins',
		hidden:false,
		meta:{ title:'插件管理', icon:'icontishi'},
		component: Layout,
		children:[
			{ path:'list', name:'PluginList', component: r => require(['./pages/Plugins/index.vue'],r), meta:{ title:'插件管理', icon:'icontishi' }, hidden:false }
		]
	},
	{
		path:'/center/image',
		hidden:false,
		meta:{ title:'图片管理', icon:'icontupian'},
		component: Layout,
		children:[
			{ path:'', name:'ImageList', component: r => require(['./pages/Images/ImageList.vue'],r), meta:{ title:'图片管理', icon:'icontupian' }, hidden:false }
		]
	},
	{
		path:'/center/settings',
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
		path:'/center/user',
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