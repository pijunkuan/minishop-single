const getters = {
	token:state => state.user.token,
	username: state => state.user.username,
	title: state => state.web.title,
	sidebar: state => state.web.sidebar,
	logo: state => state.sys.logo
}

export default getters