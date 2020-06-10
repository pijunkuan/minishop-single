import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import './utils/setting.js'

import index from '@/components/index'
Vue.use(index)

import 'mint-ui/lib/style.css'
import { 
	Picker,
	Swipe,
	SwipeItem,
	InfiniteScroll
} from 'mint-ui'
Vue.component(Picker.name,Picker)
Vue.component(Swipe.name,Swipe)
Vue.component(SwipeItem.name,SwipeItem)
Vue.use(InfiniteScroll)

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')
