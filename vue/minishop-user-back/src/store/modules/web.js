const web = {
	state:{
		title:'',
		sidebar:false
	},
	mutations:{
		'SET_TITLE':(state,title)=>{
			state.title = title
		},
		'SET_SIDEBAR':(state,toggleShow)=>{
			state.sidebar = toggleShow
		}
	},
	actions:{
		setTitle({commit},title){
			commit('SET_TITLE',title)
		},
		toggleSide({commit},toggleShow){
			commit('SET_SIDEBAR',toggleShow)
		}
	}
}

export default web