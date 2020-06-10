/*
	后端数据发起
 */

import axios from 'axios'
import store from '@/store'
import { Toast } from 'mint-ui'
import { getToken } from '@/utils/auth'
// import defaultSetting from '@/settings'

const service = axios.create({
	// baseURL:defaultSetting.apiURL
	baseURL:location.origin + '/api/front/'
})

service.interceptors.request.use(
	config => {
		if(store.getters.token){
			config.headers['Authorization'] = 'Bearer ' + getToken()
		}
		return config
	},
	error => {
		return Promise.reject(error)
	}
)

service.interceptors.response.use(
	response => response,
	error => {
		if(error.response.status === 401){
			Toast({
				message:'登录超时，请重新登录',
				duration:2000
			})
			setTimeout(()=>{
				store.dispatch('logout')
			},1000)
		}
		return Promise.reject(error)
	}
)

export default service