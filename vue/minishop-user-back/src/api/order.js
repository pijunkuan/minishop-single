import request from '@/utils/request'

export function get_orders(data){
	return request({
		url:'order',
		method:'get',
		params:data
	})
}

export function get_order(id){
	return request({
		url:'order/'+id,
		method:'get'
	})
}

export function status_update_order(id,data){
	return request({
		url:'order/status/' + id,
		method:'put',
		data
	})
}

export function update_order(id,data){
	return request({
		url:'order/' + id,
		method:"put",
		data
	})
}

export function send_order(id,data){
	return request({
		url:'order/'+id+'/shipment',
		method:"post",
		data
	})
}

export function get_refund_record(id){
	return request({
		url:'order/refund_record/' + id,
		method:'get'
	})
}

export function create_refund(id,data){
	return request({
		url:'order/refund_record/' + id,
		method:'post',
		data
	})
}

export function create_order_tip(id,data){
	return request({
		url:'order/' + id + '/tip',
		method:'post',
		data
	})
}

export function get_order_tip(id){
	return request({
		url:'order/' + id + '/tip',
		method:'get'
	})
}