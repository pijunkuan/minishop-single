import request from '../utils/request'

export function get_cart(){
	return request({
		url:'cart',
		method:'get'
	})
}


export function add_cart(data){
	return request({
		url:'cart',
		method:'post',
		data
	})
}


export function edit_cart(data){
	return request({
		url:'cart',
		method:'put',
		data
	})
}


export function delete_cart(variant_id){
	return request({
		url:'cart/' + variant_id,
		method:'delete'
	})
}


export function create_cart_cache(data){
	return request({
		url:'cart/cache',
		method:'post',
		data
	})
}

export function get_cart_cache(key){
	return request({
		url:'cart/cache/' + key,
		method:'get'
	})
}