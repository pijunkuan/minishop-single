import { get_shop } from '@/api/shop'

const shop = {
	state:{
		id:'',
		name:'',
		url:'',
		host:'',
		exp_at:'',
		level:'',
		status:'',
		active:'',
		img_limit:0,
		item_limit:0,
		shop_sms:0
	},
	mutations:{
		SET_ID:(state,id)=>{
			state.id = id
		},
		SET_NAME:(state,name)=>{
			state.name = name
		},
		SET_URL:(state,url)=>{
			state.url = url
		},
		SET_HOST:(state,host)=>{
			state.host = host
		},
		SET_EXP:(state,exp_at)=>{
			state.exp_at = exp_at
		},
		SET_LEVEL:(state,level)=>{
			state.level = level
		},
		SET_STATUS:(state,status)=>{
			state.status = status
		},
		SET_ACTIVE:(state,active)=>{
			state.active = active
		},
		SET_IMG:(state,img)=>{
			state.img_limit = img
		},
		SET_ITEM:(state,item)=>{
			state.item_limit = item
		},
		SET_SMS:(state,shop_sms)=>{
			state.shop_sms = shop_sms
		},
        SET_AUTO_CLOSE_IN:(state,auto_close_in)=>{
            state.shop_auto_close_in = auto_close_in
        },
        SET_AUTO_RECEIVE_IN:(state,auto_receive_in)=>{
            state.shop_auto_receive_in = auto_receive_in
            
        }
	},
	actions:{
		getShopInfo({commit}){
			return new Promise((resolve)=>{
				get_shop().then(r=>{
					commit('SET_ID', r.data.body.shop_id)
					commit('SET_NAME', r.data.body.shop_name)
					commit('SET_LEVEL', r.data.body.level.level_name)
					commit('SET_EXP', r.data.body.level.exp_at)
					commit('SET_URL', r.data.body.shop_url)
					commit('SET_STATUS', r.data.body.status_code)
					commit('SET_HOST', r.data.body.host)
					commit('SET_ACTIVE', r.data.body.active)
					commit('SET_IMG', r.data.body.level.pic_limit)
					commit('SET_ITEM', r.data.body.level.item_limit)
					commit('SET_SMS', r.data.body.sms_amount)
					commit('SET_AUTO_CLOSE_IN', r.data.body.auto_close_in)
					commit('SET_AUTO_RECEIVE_IN', r.data.body.auto_receive_in)
					resolve(r)
				})
			})
		}
	}
}

export default shop