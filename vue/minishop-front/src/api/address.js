import request from '../utils/request'

export function get_addresses(data){
	return request({
		url:'address',
		method:'get',
		params:data
	})
}


export function create_address(data){
	return request({
		url:'address',
		method:'post',
		data
	})
}


export function edit_address(id,data){
	return request({
		url:'address/' + id,
		method:'put',
		data
	})
}


export function delete_address(id){
	return request({
		url:'address/' + id,
		method:'delete'
	})
}