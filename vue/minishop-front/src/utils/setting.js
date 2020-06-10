/*
	进入页面前获取主题模版
 */
import router from '@/router'
import store from '@/store'

router.beforeEach((to, from, next) => {
	store.dispatch('setTemplate')
	next()
})