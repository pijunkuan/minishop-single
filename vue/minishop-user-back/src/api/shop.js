import request from '../utils/request'

export function get_shop(){
	return request({
		url:'shop/info',
		method:'get'
	})
}

export function edit_shop(data){
	return request({
		url:'shop/update',
		method:"put",
		data
	})
}

export function get_shop_themes(){
	return request({
		url:'shop/template/cloud',
		method:"get"
	})
}

export function get_shop_payments(){
	return request({
		url:'shop/payment/sys_payment',
		method:"get"
	})
}

export function get_user(){
	return request({
		url:'me',
		method:'get'
	})
}

export function edit_user(data){
	return request({
		url:'me',
		method:"put",
		data
	})
}