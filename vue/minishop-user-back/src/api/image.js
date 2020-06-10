import request from '../utils/request'

export function get_images(data){
	return request({
		url:'image',
		method:'get',
		params:data
	})
}

export function delete_image(id){
	return request({
		url:'image/' + id,
		method:'delete'
	})
}