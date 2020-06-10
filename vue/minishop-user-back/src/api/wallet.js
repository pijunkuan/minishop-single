import request from '../utils/request'

export function get_wallet_list(id,data){
	return request({
		url:'wallet/'+id,
		method:'get',
		params:data
	})
}

export function pay_wallet(id,data){
	return request({
		url:'wallet/'+id,
		method:'post',
		data
	})
}

export function get_wallet_balance(id){
	return request({
		url:"wallet/"+id+"/balance",
		method:"get"
	})
}

