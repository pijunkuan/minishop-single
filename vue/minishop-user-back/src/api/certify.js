import request from '../utils/request'

export function get_certify(){
	return request({
		url:'shop/authenticate',
		method:'get'
	})
}

export function create_certify(data){
	return request({
		url:'shop/authenticate',
		method:'post',
		data
	})
}

export function update_certify(data){
	return request({
		url:'shop/authenticate',
		method:'put',
		data
	})
}