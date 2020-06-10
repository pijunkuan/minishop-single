import { login, get_user } from '@/api/login'
import { get_balance } from '@/api/wallet'
import { getToken, removeToken, setToken, getExtime, removeExtime, setExtime, removeTheme } from '@/utils/auth'

const user = {
	state:{
		token:getToken(),
		time:getExtime(),
		mobile:'',
		avatar:'',
		username:'',
		balance:''
	},
	mutations:{
		SET_TOKEN:(state,token)=>{
			state.token = token
		},
		SET_TIME:(state,time)=>{
			state.time = time
		},
		SET_MOBILE:(state,mobile)=>{
			state.mobile = mobile
		},
		SET_AVATAR:(state,avatar)=>{
			state.avatar = avatar
		},
		SET_USERNAME:(state,username)=>{
			state.username = username
		},
		SET_BALANCE:(state,balance)=>{
			state.balance = balance
		}
	},
	actions:{
		login({commit},info){
			return new Promise((resolve,reject)=>{
				login(info).then(r=>{
					commit('SET_TOKEN', r.data.body.access_token)
					let _time = new Date().getTime() + 3600000
					commit('SET_TIME', _time)
					setToken(r.data.body.access_token)
					setExtime(_time)
					resolve()
				}).catch(e=>{
					reject(e)
				})
			})
		},
		getUserInfo({commit}){
			return new Promise((resolve,reject)=>{
				get_user().then(r=>{
					commit('SET_MOBILE',r.data.body.mobile)
					commit('SET_AVATAR',r.data.body.avatar)
					commit('SET_USERNAME',r.data.body.username)
					resolve()
				}).catch(e=>{
					reject(e)
				})
			})
		},
		getBalance({commit}){
			return new Promise((resolve,reject)=>{
				get_balance().then(r=>{
					commit('SET_BALANCE', r.data.body.balance)
					resolve()
				}).catch(e=>{
					reject(e)
				})
			})
		},
		logout({commit}){
			return new Promise((resolve)=>{
				commit('SET_TOKEN','')
				removeToken()
				removeExtime()
				removeTheme()
				resolve()
			})
		}
	}
}

export default user