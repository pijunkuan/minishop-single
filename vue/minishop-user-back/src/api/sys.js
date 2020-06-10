import request from '../utils/request'

export function get_config(){
	return request({
		url:'config',
		method:'get'
	})
}