import request from '../utils/request'

export function get_template(){
	return request({
		url:'/template',
		method:'get'
	})
}