import request from '@/utils/request'

export function to_base64(data){
	return request({
		url:'tools/img2base64',
		method:'post',
		data
	})
}