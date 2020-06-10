import QRCode from 'qrcodejs2'

export function to_qrcode({
	id,
	link,
	height = 150,
	width = 150
} = {}) {
	let qrcode = new QRCode(id,{
		text:link,
		height:height,
		width:width,
		colorDark:'#000',
		colorLight:'#fff'
	})
	return qrcode
}