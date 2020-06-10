import request from '../utils/request'

export function get_static_data(type){
	return request({
		url:'dashboard/'+type,
		method:'get'
	})
}