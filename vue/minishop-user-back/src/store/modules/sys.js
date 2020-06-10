import { getLogo, setLogo } from '@/utils/auth'
import { get_config } from '@/api/sys'

const sys = {
	state:{
		logo:getLogo()
	},
	mutations:{
		'SET_LOGO':(state,logo)=>{
			state.logo = logo
		}
	},
	actions:{
		getLogo({commit}){
			get_config().then(r=>{
				commit('SET_LOGO',r.data.logo)
				setLogo(r.data.logo)
			})
		}
	}
}

export default sys