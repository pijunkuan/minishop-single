import { getToken, getLogo } from './auth.js'
import store from '@/store'
import router from '@/router'
import { Message } from 'element-ui'


router.beforeEach((to,from,next)=>{
	if(!getLogo()){
		store.dispatch('getLogo')
	}
	if(getToken()){
		try {
			store.dispatch('getShopInfo')
			if(to.path === '/login') next('/dashboard')
				else next()
		}
		catch(error) {
			if(error.response.status === 401){
				Message.error({
					message:'登录超时，请重新登录',
					duration:0
				})
				store.dispatch('logout')
				next(`/login?redirect=${to.path}`)
			}
		}
	}else{
		if(to.path === '/login') next()
			else next(`/login?redirect=${to.path}`)
	}
})