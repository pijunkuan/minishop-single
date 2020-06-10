const getters = {
	token: state => state.user.token,
	mobile: state => state.user.mobile,
	avatar: state => state.user.avatar,
	username: state => state.user.username,
	balance: state => state.user.balance,
	cart: state => state.cart.amount,
	theme: state => state.theme.name
}

export default getters