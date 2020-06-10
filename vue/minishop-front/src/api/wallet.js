import request from '../utils/request'

export function get_wallet(data){
	return request({
		url:'wallet',
		method:'get',
		params:data
	})
}

export function get_balance(){
	return request({
		url:'wallet/balance',
		method:'get'
	})
}


export function get_payments(){
	return request({
		url:'payment_methods',
		method:'get'
	})
}