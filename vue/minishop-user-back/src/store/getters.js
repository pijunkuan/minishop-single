const getters = {
	token:state => state.user.token,
	username: state => state.user.username,
	shop_name: state => state.shop.name,
	shop_url: state => state.shop.url,
	shop_level: state => state.shop.level,
	shop_status: state => state.shop.status,
	shop_id: state => state.shop.id,
	shop_exp: state => state.shop.exp_at,
	shop_host: state => state.shop.host,
	shop_active: state => state.shop.active,
	shop_img_limit: state => state.shop.img_limit,
	shop_item_limit: state => state.shop.item_limit,
	shop_sms: state => state.shop.shop_sms,
	shop_auto_close_in: state => state.shop.shop_auto_close_in,
	shop_auto_receive_in: state => state.shop.shop_auto_receive_in,
	title: state => state.web.title,
	sidebar: state => state.web.sidebar,
	logo: state => state.sys.logo
}

export default getters