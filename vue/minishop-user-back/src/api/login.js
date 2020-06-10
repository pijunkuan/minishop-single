import request from '../utils/request'

export function login(data){
	return request({
		url:'login',
		method:'post',
		data
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