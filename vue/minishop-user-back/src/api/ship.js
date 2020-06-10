import request from '@/utils/request'

export function get_shipment(){
	return request({
		url:"shipment",
		method:"get"
	})
}

export function get_shipment_by_id(id){
	return request({
		url:"shipment/" + id,
		method:"get"
	})
}

export function edit_shipment(id,data){
	return request({
		url:"shipment/" + id,
		method:"put",
		data
	})
}

export function create_shipment(data){
	return request({
		url:"shipment",
		method:"post",
		data
	})
}

export function delete_shipment(id){
	return request({
		url:"shipment/"+ id,
		method:"delete"
	})
}

export function get_shipment_code(){
	return request({
		url:"shipment/type",
		method:"get"
	})
}