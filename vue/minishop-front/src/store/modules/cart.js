import { get_cart } from '@/api/cart'

const cart = {
	state:{
		amount:0
	},
	mutations:{
		SET_CART:(state,cart)=>{
			state.amount = cart
		}
	},
	actions:{
		getCart({commit}){
			return new Promise((resolve)=>{
				get_cart().then(r=>{
					let _items = r.data.body
					let _count = 0
					_items.map(v=>{
						_count = _count + v.quantity
					})
					_count = _count.toFixed(0) * 1
					commit('SET_CART', _count)
					resolve()
				}).catch(()=>{})
			})
		}
	}
}

export default cart