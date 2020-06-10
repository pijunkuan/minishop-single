import { getToken, getLogo } from './auth.js'
import store from '@/store'
import router from '@/router'
import { Message } from 'element-ui'

const whiteList = ['/center/session']

router.beforeEach((to,from,next)=>{
	if(!getLogo()){
		store.dispatch('getLogo')
	}
	if(whiteList.indexOf(to.path) !== -1){
		next()
	}else{
		if(getToken()){
			try {
				store.dispatch('getShopInfo')
				next()
			}
			catch(error) {
				if(error.response.status === 401){
					Message.error({
						message:'登录超时，请重新登录',
						duration:0
					})
					store.dispatch('logout')
					let _url = location.origin.split('.')
					let _host = "//account."+_url[_url.length-2]+"."+_url[_url.length-1]+"/login"
					setTimeout(()=>{
						location.href = _host
					},500)
				}
			}
		}else{
			Message.error({
				message:'登录错误，请重新登录',
				duration:0
			})
			store.dispatch('logout')
			let _url = location.origin.split('.')
			let _host = "//account."+_url[_url.length-2]+"."+_url[_url.length-1]+"/login"
			setTimeout(()=>{
				location.href = _host
			},500)
		}
	}
})