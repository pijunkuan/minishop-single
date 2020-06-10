import request from '@/utils/request'

export function get_payments(){
	return request({
		url:'shop/payment',
		method:'get'
	})
}

export function start_payment(code){
	return request({
		url:'shop/payment/' + code,
		method:'post'
	})
}

export function stop_payment(code){
	return request({
		url:'shop/payment/' + code,
		method:'delete'
	})
}