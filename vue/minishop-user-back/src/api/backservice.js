import request from '../utils/backRequest'

export function create_order(data){
	return request({
		url:'/shop/order',
		method:'post',
		data
	})
}

export function get_order(no){
	return request({
		url:'/shop/order/' + no,
		method:'get'
	})
}

export function get_payments(){
	return request({
		url:'/shop/sys/payment_method',
		method:'get'
	})
}

export function get_sms_type(){
	return request({
		url:'/shop/sys/sms',
		method:'get'
	})
}

export function get_contrast(){
	return request({
		url:'/account/contrast',
		method:'get'
	})
}


export function logout(){
	return request({
		url:'/user/logout',
		method:'get'
	})
}

export function get_shop_level(){
	return request({
		url:'/shop/sys/level',
		method:'get'
	})
}