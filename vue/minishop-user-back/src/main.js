import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import ElementUI from 'element-ui'
import '@/assets/style/variable.scss'
Vue.use(ElementUI)

import './utils/permission.js'

new Vue({
	router,
	store,
	render: h => h(App),
}).$mount('#app')
