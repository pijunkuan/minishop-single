import { getToken, setToken, removeToken, getExtime, setExtime, removeExtime } from '@/utils/auth'
import { login, get_user, edit_user } from '@/api/login'

const user = {
	state:{
		token:getToken(),
		expire_time:getExtime(),
		username:''
	},
	mutations:{
		SET_USER:(state,username)=>{
			state.username = username
		},
		SET_TOKEN:(state,token)=>{
			state.token = token
		},
		SET_TIME:(state, time)=>{
			state.expire_time = time
		}
	},
	actions:{
		login({commit},info){
			return new Promise((resolve,reject)=>{
				login(info).then(r=>{
					let expire = new Date().getTime() + r.data.body.expires_in * 1000
					commit('SET_TOKEN',r.data.body.access_token)
					commit('SET_TIME',expire)
					setToken(r.data.body.access_token)
					setExtime(expire)
					resolve(r)
				}).catch(e=>{
					reject(e)
				})
			})
		},
		getUserInfo({commit}){
			return new Promise((resolve,reject)=>{
				get_user().then(r=>{
					commit('SET_USER',r.data.body.username)
					resolve(r)
				}).catch(e=>{
					reject(e)
				})
			})
		},
		edit({commit},data){
			return new Promise((resolve,reject)=>{
				edit_user(data).then(r=>{
					let expire = new Date().getTime() + r.data.body.expires_in * 1000
					commit('SET_TOKEN',r.data.body.access_token)
					commit('SET_TIME',expire)
					setToken(r.data.body.access_token)
					setExtime(expire)
					resolve(r)
				}).catch(e=>{
					reject(e)
				})
			})
		},
		logout({commit}){
			return new Promise((resolve)=>{
				removeToken()
				removeExtime()
				commit('SET_TOKEN','')
				resolve()
			})
		}
	}
}

export default user