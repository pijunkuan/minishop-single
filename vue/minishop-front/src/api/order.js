import request from '../utils/request'

export function calc_order_total(data){
	return request({
		url:'order/calc',
		method:'post',
		data
	})
}


export function create_order(data){
	return request({
		url:'order',
		method:'post',
		data
	})
}


export function create_pay(order_no,payment){
	return request({
		url:'order/' + order_no + '/pay/' + payment,
		method:'post'
	})
}


export function get_orders(data){
	return request({
		url:'order',
		method:'get',
		params:data
	})
}


export function get_order(order_no){
	return request({
		url:'order/' + order_no,
		method:'get'
	})
}


export function change_order_status(order_no,data){
	return request({
		url:'order/' + order_no,
		method:'put',
		data
	})
}