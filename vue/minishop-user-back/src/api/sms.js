import request from '@/utils/request'

export function get_sms_template(){
	return request({
		url:'sms',
		method:'get'
	})
}

export function update_sms_template_status(id){
	return request({
		url:'sms/' + id,
		method:'put'
	})
}

export function get_sms_history(data){
	return request({
		url:'sms/log',
		method:'get',
		params:data
	})
}

export function get_sms_sign(data){
	return request({
		url:'sms/sign',
		method:'get',
		params:data
	})
}

export function create_sms_sign(data){
	return request({
		url:'sms/sign',
		method:'post',
		data
	})
}

export function update_sms_sign(id,data){
	return request({
		url:'sms/sign/' + id,
		method:'put',
		data
	})
}

export function delete_sms_sign(id){
	return request({
		url:'sms/sign/' + id,
		method:'delete'
	})
}