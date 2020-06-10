import { getToken, setToken, removeToken } from '@/utils/auth'
import { get_user } from '@/api/shop'

const user = {
	state:{
		token:getToken(),
		username:''
	},
	mutations:{
		SET_USER:(state,username)=>{
			state.username = username
		},
		SET_TOKEN:(state,token)=>{
			state.token = token
		}
	},
	actions:{
		retakeToken({commit},token){
			return new Promise((resolve)=>{
				commit('SET_TOKEN',token)
				setToken(token)
				resolve()
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
		logout({commit}){
			removeToken()
			commit('SET_TOKEN','') 
		}
	}
}

export default user