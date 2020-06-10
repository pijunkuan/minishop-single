import request from '../utils/request'

export function get_categories(){
	return request({
		url:'category',
		method:'get'
	})
}

export function update_categories(id,data){
	return request({
		url:"category/"+id,
		method:"put",
		data
	})
}

export function create_categories(data){
	return request({
		url:"category",
		method:"post",
		data
	})
}

export function delete_categories(id){
	return request({
		url:"category/"+id,
		method:"delete"
	})
}