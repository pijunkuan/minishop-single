import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export const routes = [
	{
		path:'/redirect',
		hidden:true,
		children:[
			{ path:'/redirect/:path*', component: r => require(['./pages/Redirect/index.vue'],r)}
		]
	},
	{
		path:'/404',
		name:'404',
		hidden:true,
		component: r => require(['./pages/Error/404.vue'], r)
	},
	{
		path:'/login',
		name:'Login',
		meta:{ title:'登录' },
		component: r => require(['./pages/Login/Login.vue'],r)
	},
	{
		path:'/register',
		name:'Register',
		meta:{ title:'注册' },
		component: r => require(['./pages/Login/Register.vue'],r)
	},
	{
		path:'/forget',
		name:'Forget',
		meta:{ title:'忘记密码' },
		component: r => require(['./pages/Login/Forget.vue'],r)
	},
	{
		path:'/',
		name:'Home',
		meta:{ title:'首页' },
		component: r => require(['./pages/Home/index.vue'], r)
	},
	{
		path:'/search',
		name:'Search',
		meta:{ keepAlive:true },
		component: r => require(['./pages/Search/index.vue'], r)
	},
	{
		path:'/product',
		name:'Products',
		meta:{ title:'全部商品', keepAlive:true },
		component: r => require(['./pages/Products/index.vue'], r)
	},
	{
		path:'/types',
		name:'Types',
		meta:{ title:'分类菜单', keepAlive:true },
		component: r => require(['./pages/Types/index.vue'], r)
	},
	{
		path:'/product/:id',
		name:'Product',
		meta:{ title:'商品详情' },
		component: r => require(['./pages/Product/index.vue'], r)
	},
	{
		path:'/cart',
		name:'Cart',
		meta:{ title:'购物车' },
		component: r => require(['./pages/Cart/index.vue'], r)
	},
	{
		path:'/checkout',
		children:[
			{
				path:'create',
				name:'Checkout',
				meta:{ title:'确认订单' },
				component: r => require(['./pages/Checkout/Checkout.vue'], r)
			},
			{
				path:'pay',
				name:'Checkpay',
				component: r => require(['./pages/Checkout/Checkpay.vue'], r)
			},
			{
				path:'succeed',
				name:'Checksuc',
				component: r => require(['./pages/Checkout/Checksuc.vue'], r)
			}
		]
	},
	{
		path:'/user',
		children:[
			{
				path:'home',
				name:'UserHome',
				component: r => require(['./pages/User/Home.vue'], r)
			},
			{
				path:'wallet',
				name:'UserWallet',
				meta:{ title:'我的钱包' },
				component: r => require(['./pages/User/Wallet.vue'], r)
			},
			{
				path:'order',
				name:'UserOrder',
				meta:{ title:'我的订单', keepAlive:true },
				component: r => require(['./pages/User/Order.vue'], r)
			},
			{
				path:'order/detail',
				name:'UserOrderDetail',
				meta:{ title:'订单详情' },
				component: r => require(['./pages/User/OrderDetail.vue'], r)
			},
			{
				path:'address',
				name:'UserAddress',
				meta:{ title:'我的地址' },
				component: r => require(['./pages/User/Address.vue'], r)
			}
		]
	},
	{
		path:'*',
		redirect:'/404',
		hidden:true
	}
]

export default new Router({
	mode:'history',
	routes:routes
})