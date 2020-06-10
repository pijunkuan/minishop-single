import Vue from 'vue'
import Vuex from 'vuex'
import getters from './getters'
import user from './modules/user'
import web from './modules/web'
import shop from './modules/shop'
import sys from './modules/sys'

Vue.use(Vuex)

const store = new Vuex.Store({
	modules:{
		user,
		web,
		shop,
		sys
	},
	getters
})

export default store