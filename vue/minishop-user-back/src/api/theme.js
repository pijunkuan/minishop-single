import request from '@/utils/request'

export function get_themes(){
	return request({
		url:'shop/template',
		method:'get'
	})
}

export function get_theme(id){
	return request({
		url:'shop/template/' + id,
		method:'get'
	})
}

export function use_theme(id){
	return request({
		url:'shop/template/' + id + '/active',
		method:'put'
	})
}

export function change_theme(data,id){
	return request({
		url:'shop/template/' + id,
		method:'put',
		data
	})
}