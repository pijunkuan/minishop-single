import request from '../utils/request'

export function get_products(data){
	return request({
		url:'product',
		method:'get',
		params:data
	})
}

export function get_product(id){
	return request({
		url:'product/' + id,
		method:'get'
	})
}

export function get_types(){
	return request({
		url:'category',
		method:'get'
	})
}