import { getTheme, setTheme, removeTheme } from '@/utils/auth'
import { get_template } from '@/api/template'

const theme = {
	state:{
		name:getTheme()
	},
	mutations:{
		SET_THEME:(state,theme)=>{
			state.name = theme
		}
	},
	actions:{
		setTemplate({commit}){
			return new Promise((resolve,reject)=>{
				get_template().then(r=>{
					if(r.data.body.theme){
						commit('SET_THEME',r.data.body.theme)
						setTheme(r.data.body.theme)
					}else{
						commit('SET_THEME','purple')
						setTheme('purple')
					}
					resolve()
				}).catch(e=>{
					commit('SET_THEME','purple')
					setTheme('purple')
					reject(e)
				})
			})
		},
		clearTheme({commit}){
			commit('SET_THEME','')
			removeTheme()
		}
	}
}

export default theme