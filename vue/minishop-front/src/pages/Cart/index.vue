<template>
<div>
    <page-loading :loading="loading"></page-loading>
    <div class="cart-functional">
        <shop-checkbox v-model="selectAll" @change="checkAll">全选</shop-checkbox>
        <div><span @click="toEdit">{{ isEdit ? '取消' : '编辑' }}</span></div>
    </div>
    <div v-if="!loading && items.length === 0" class="cart-placeholder">
        <div>暂无商品</div>
        <div class="cart-placeholder-button" @click="toProducts"><span>去逛逛</span></div>
    </div>
    <div v-else class="cart-list" :style="{height:height + 'px'}">
        <div class="cart-list-items">
            <div class="cart-list-item" v-for="(item,index) in items" :key="index" style="position:relative">
                <shop-checkbox v-model="selected" :label="item.id" :disabled="!isEdit && !item.visibility" @change="changeSelect(item)"></shop-checkbox>
                <div class="cart-item-error" v-if="item.reason">{{ item.reason }}</div>
                <shop-image :src="item.img_url" rounded type="fit" :width="80">
                    <div class="cart-image-placeholder" slot="error"><i class="iconfont icontupian"></i></div>
                    <div class="cart-image-placeholder" slot="placeholder"><i class="iconfont icontupian"></i></div>
                </shop-image>
                <div class="cart-list-item__info">
                    <div class="cart-list-item__info-title">{{ item.product_title }}</div>
                    <div class="cart-list-item__info-subtitle"><div>{{ item.variant_title }}</div></div>
                    <div class="cart-list-item__info-price">¥ <strong>{{ item.price }}</strong></div>
                    <div class="cart-list-item__info-amount">
                        <shop-input-num
                            :value="item.quantity"
                            :max="item.stock"
                            :min="1"
                            :disabled="!item.visibility || isEdit"
                            @change="changeNum(item,$event)">
                        </shop-input-num>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-footer">
        <div class="cart-footer-num" v-if="!isEdit">
            总计：<span>¥ <strong>{{ total }}</strong></span>
        </div>
        <div class="cart-footer-button" v-if="!isEdit">
            <shop-button size="medium" @click="confirmOrder">确认订单</shop-button>
        </div>
        <div class="cart-footer-button" v-if="isEdit" style="text-align:center">
            <shop-button size="medium" @click="deleteCart('part')">移除选中</shop-button>
        </div>
        <div class="cart-footer-button" v-if="isEdit" style="text-align:center">
            <shop-button size="medium" @click="deleteCart('all')">全部移除</shop-button>
        </div>
    </div>
</div>
</template>

<script>
import { get_cart, edit_cart, delete_cart, create_cart_cache } from '@/api/cart'
import { Toast, MessageBox } from 'mint-ui'
import { getToken } from '@/utils/auth'
export default{
    data(){
        return{
            loading:false,
            height:0,
            items:[],
            selected:[],
            selectItems:[],
            selectAll:false,
            isEdit:false,
            complete:0
        }
    },
    created(){
        if(getToken()) this.getItems()
            else this.$router.go(-1)
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 126
        })
        window.onresize = () => {
            this.height = window.innerHeight - 126
        }
    },
    computed:{
        total(){
            let _amount = 0
            if(this.selectItems.length !== 0){
                this.selectItems.map(v=>{
                    _amount += v.quantity * v.price
                })
                _amount = _amount.toFixed(2)
            }
            return _amount
        }
    },
    methods:{
        getItems(){
            this.loading = true
            get_cart().then(r=>{
                this.items = r.data.body
                this.loading = false
            }).catch(e=>{
                this.loading = false
                if(e.response.status === 401){
                    this.$store.dispatch('logout')
                    this.$router.go(-1)
                }
            })
        },
        checkAll(){
            this.selected = []
            this.selectItems = []
            if(!this.selectAll) return
            this.items.map(v=>{
                this.selected.push(v.id)
                this.selectItems.push(v)
            })
        },
        changeSelect(item){
            const _index = this.selected.indexOf(item.id)
            if(_index !== -1) this.selectItems.push(item)
                else this.selectItems.splice(_index,1)
        },
        changeNum(item,val){
            item.quantity = val
            let _data = {
                variant_id:item.variant_id,
                quantity:val
            }
            this.loading = true
            edit_cart(_data).then(()=>{
                this.loading = false
            }).catch(e=>{
                if(e.response.status === 401){
                    this.$store.dispatch('logout')
                    this.$router.go(-1)
                }else{
                    Toast(e.response.data.message)
                    this.loading = false
                }
            })
        },
        toEdit(){
            if(this.items.length === 0) return
            this.isEdit = !this.isEdit
            this.selectAll = false
            this.selected = []
            this.selectItems = []
        },
        toProducts(){
            this.$router.push({name:'Types'})
        },
        confirmOrder(){
            if(this.items.length === 0) return
            if(this.selectItems.length === 0){
                Toast('请先选择商品')
                return
            }
            let _data = []
            let _item = {}
            this.selectItems.map(v=>{
                _item['variant_id'] = v.variant_id
                _item['quantity'] = v.quantity
                _data.push(_item)
                _item = {}
            })
            create_cart_cache({items:_data}).then(r=>{
                this.$router.push({name:'Checkout',query:{key:r.data.body.key}})
            }).catch(e=>{
                if(e.response.status === 401){
                    MessageBox({
                        title:'提示',
                        message:'登录超时，请重新登录',
                        confirmButtonText:'现在登录',
                        showCancelButton:true,
                        cancelButtonText:'再逛逛'
                    }).then(()=>{
                        this.$router.push({name:'Login',query:{from:this.$route.name}})
                    }).catch(()=>{
                        this.$router.push({name:'Home'})
                    })
                }
            })
        },
        deleteCart(type){
            if(type === 'all') this.delete(this.items)
                else this.delete(this.selectItems)
        },
        delete(data){
            if(this.complete === data.length){
                this.getItems()
                this.selectAll = false
                this.complete = 0
                this.isEdit = false
                return
            }
            let _id = data[this.complete].variant_id
            delete_cart(_id).then(()=>{
                this.complete += 1
                this.delete(data)
            }).catch(e=>{
                if(e.response.status === 401){
                    this.$store.dispatch('logout')
                    this.$router.go(-1)
                }else{
                    Toast(e.response.data.message)
                }
            })
        }
    },
    watch:{
        selected:{
            deep:true,
            handler:function(){
                if(this.selected.length === 0) this.selectAll = false
                    else if(this.selected.length === this.items.length) this.selectAll = true
                        else if(this.selected.length !== this.items.length) this.selectAll = false
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.cart-functional{
    position:absolute;
    top:0;
    left:0;
    right:0;
    background-color:#fff;
    padding:5px;
    display:flex;
    font-size:$middle-font-size;
}
.cart-functional>div{
    flex:1;
}
.cart-functional>div:last-child{
    text-align:right;
}
.cart-list{
    margin-top:32px;
    overflow-y:scroll;
}
.cart-list-items{
    margin:10px 0;
    background-color:#fff;
}
.cart-list-item{
    padding:10px;
    border-bottom:1px solid $line-color;
}
.cart-list-item>div{
    display:inline-block;
    vertical-align:middle;
}

.cart-image-placeholder{
    background-color:$line-color;
    border-radius:5px;
    height:80px;
}
.cart-image-placeholder i{
    font-size:24px;
    color:$disabled-color;
}

.cart-list-item__info{
    margin-left:10px;
    width:calc(100% - 118px);
}
.cart-list-item__info-title{
    font-size:$middle-font-size;
    line-height:$middle-font-height;
    max-height:$middle-font-height * 2;
    overflow:hidden;
    color:$main-font-color;
    margin-bottom:5px;
}
.cart-list-item__info-subtitle{
    font-size:$small-font-size;
    line-height:$small-font-height;
    height:$small-font-height;
    overflow:hidden;
    display:inline-block;
    padding:3px 7px;
    background-color:$line-color;
    border-radius:3px;
    color:$sub-font-color;
}
.cart-list-item__info-price{
    font-size:$small-font-size;
    @include price-color(1);
}
.cart-list-item__info-price strong{
    font-size:$normal-font-size;
}
.cart-list-item__info-amount{
    text-align:right;
}

.cart-footer{
    position:fixed;
    display:flex;
    height:44px;
    bottom:0;
    right:0;
    left:0;
    padding:0 10px;
    background-color:#fff;
    border-top:1px solid $line-color;
    box-shadow: 0 -1px 5px 2px rgba(0,0,0,0.1);
}
.cart-footer>div{
    flex:1;
}
.cart-footer-num{
    line-height:44px;
    font-size:$small-font-size;
    color:$sub-font-color;
}
.cart-footer-num span{
    @include price-color(1);
}
.cart-footer-num strong{
    font-size:$normal-font-size;
}
.cart-footer .cart-footer-button{
    text-align:right;
}
.cart-footer .cart-footer-button button{
    margin-top:4px;
    height:35px;
    border-radius:25px;
}

.cart-placeholder{
    margin-top:100px;
    text-align:center;
    font-size:$normal-font-size;
    color:$other-font-color;
}
.cart-placeholder-button span{
    margin-top:10px;
    line-height:60px;
    padding:3px 10px;
    border:1px solid $disabled-color;
    border-radius:25px;
}
.cart-item-error{
    position:absolute;
    width:calc(100% - 50px);
    height:100px;
    line-height:100px;
    text-align:center;
    color:#fff;
    background-color:rgba(0,0,0,0.6);
}
</style>