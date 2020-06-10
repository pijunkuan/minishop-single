<template>
<div>
    <div class="address-functional">
        <div v-if="!isEdit"><shop-button size="small" @click="toAdd">新增地址</shop-button></div>
        <div v-if="isEdit"><shop-button size="small" @click="toDelete">删除地址</shop-button></div>
        <div><shop-button size="small" @click="cancelEdit" type="border">{{ isEdit ? '取消' : '删除' }}</shop-button></div>
    </div>
    <ul
        class="address-list"
        :style="{height: height - 1 + 'px'}"
        v-infinite-scroll="getMore"
        infinite-scroll-disabled="loading"
        infinite-scroll-distance="10"
        infinite-scroll-immediate-check>
        <li v-for="(item,index) in addresses" :key="index">
            <shop-checkbox class="address-checkbox" v-if="isEdit" v-model="selected" :label="item.id"></shop-checkbox>
            <div class="address-content" :class="{'has-width':isEdit}" @click="toEdit(item)">
                <div>
                    <div>收件人：</div>
                    <div>{{ item.name }}</div>
                </div>
                <div>
                    <div>联系方式：</div>
                    <div>{{ item.mobile }}</div>
                </div>
                <div>
                    <div>收件地址：</div>
                    <div>{{ item.full_address }}</div>
                </div>
            </div>
        </li>
        <div v-if="!nomore" class="address-placeholder">加载中...</div>
        <div v-if="nomore" class="address-placeholder">没有更多了</div>
    </ul>
    <shop-popup :show="addShow" full>
        <div slot="title">{{ editType === 'edit' ? '编辑地址' : '新增地址' }}</div>
        <div slot="content" class="add-popup-content">
            <div>
                <div>收件人</div>
                <shop-input v-model="editAddress.name" :rounded="false"></shop-input>
            </div>
            <div>
                <div>联系方式</div>
                <shop-input v-model="editAddress.mobile" :rounded="false"></shop-input>
            </div>
            <div>
                <div>省市区</div>
                <shop-dispicker :value="editAddress.dispicker" @change="changeDispicker"></shop-dispicker>
            </div>
            <div>
                <div>邮政编码</div>
                <shop-input v-model="editAddress.zip" :rounded="false"></shop-input>
            </div>
            <div>
                <div>详细地址</div>
                <shop-input v-model="editAddress.detail" :rounded="false"></shop-input>
            </div>
        </div>
        <div slot="button">
            <div class="add-popup-button"><shop-button type="border" :rounded="false" @click="closeAdd">关闭</shop-button></div>
            <div class="add-popup-button"><shop-button :rounded="false" @click="confirmAdd">确认</shop-button></div>
        </div>
    </shop-popup>
</div>
</template>

<script>
import { get_addresses, create_address, edit_address, delete_address } from '@/api/address'
import { Toast } from 'mint-ui'
export default{
    data(){
        return{
            loading:false,
            nomore:false,
            height:0,
            addresses:[],
            fetchQuery:{
                page:0,
                pageSize:15
            },
            selected:[],
            addShow:false,
            isEdit:false,
            editType:'add',
            editAddress:{
                name:'',
                dispicker:[],
                detail:'',
                mobile:'',
                zip:''
            },
            index:0
        }
    },
    mounted(){
        this.$nextTick(()=>{
            this.height = window.innerHeight - 170
        })
        window.onresize = () => {
            this.height = window.innerHeight - 170
        }
    },
    methods:{
        getMore(){
            this.loading = true
            this.noresult = false
            let _data = []
            this.fetchQuery.page += 1
            get_addresses(this.fetchQuery).then(r=>{
                _data = r.data.body.data
                if(_data.length === 0){
                    this.loading = true
                    this.nomore = true
                }else{
                    this.addresses = this.addresses.concat(_data)
                    this.loading = false
                    this.nomore = false
                }
            }).catch(e=>{
                this.loading = true
                this.nomore = true
                if(e.response.status === 401){
                    Toast({message:'登录超时',duration:1000})
                    this.$store.dispatch('logout')
                    setTimeout(()=>{
                        this.$router.push({name:'Login',query:{from:this.$route.name}})
                    },1000)
                }
            })
        },
        cancelEdit(){
            this.isEdit = !this.isEdit
            this.selected = []
        },
        toAdd(){
            this.addShow = true
            this.editType = 'add'
        },
        toEdit(item){
            this.addShow = true
            this.editType = 'edit'
            this.editAddress = {
                id:item.id,
                name:item.name,
                mobile:item.mobile,
                dispicker:[],
                detail:item.detail,
                zip:item.zip
            }
            this.editAddress.dispicker.push(item.province)
            this.editAddress.dispicker.push(item.city)
            this.editAddress.dispicker.push(item.district)
        },
        changeDispicker(val){
            this.editAddress.dispicker = val
        },
        closeAdd(){
            this.addShow = false
            this.editAddress = {
                name:'',
                mobile:'',
                dispicker:[],
                detail:'',
                zip:''
            }
        },
        confirmAdd(){
            let _data = {
                name:this.editAddress.name,
                province:this.editAddress.dispicker[0],
                city:this.editAddress.dispicker[1],
                district:this.editAddress.dispicker[2],
                detail:this.editAddress.detail,
                mobile:this.editAddress.mobile,
                zip:this.editAddress.zip
            }
            if(this.editType === 'edit'){
                edit_address(this.editAddress.id,_data).then(()=>{
                    this.fetchQuery.page = 0
                    this.addresses = []
                    this.getMore()
                    this.closeAdd()
                }).catch(e=>{
                    Toast(e.response.data.message)
                })
            }else{
                create_address(_data).then(()=>{
                    this.fetchQuery.page = 0
                    this.addresses = []
                    this.getMore()
                    this.closeAdd()
                }).catch(e=>{
                    Toast(e.response.data.message)
                })
            }
        },
        toDelete(){
            if(this.index === this.selected.length){
                this.index = 0
                this.selected = []
                this.isEdit = false
                this.fetchQuery.page = 0
                this.addresses = []
                this.getMore()
                return
            }
            let _id = this.selected[this.index]
            delete_address(_id).then(()=>{
                this.index += 1
                this.toDelete()
            }).catch(e=>{
                if(e.response.status === 401){
                    this.$router.push({name:'Login',query:{from:'UserAddress'}})
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.address-functional{
    height:25px;
    padding:10px 10px;
    background-color:#fff;
}
.address-functional>div{
    display:inline-block;
    vertical-align:middle;
    width:50%;
}
.address-functional>div:last-child{
    text-align:right;
}
.address-list{
    margin:0;
    margin-top:10px;
    padding:0;
    overflow-y:scroll;
    background-color:#fff;
}
.address-list li{
    padding:15px 0;
    margin:0 10px;
    border-bottom:1px solid $line-color;
}

.address-list li>div{
    display:inline-block;
    vertical-align:middle;
}
.address-checkbox,.address-radio{
    width:40px;
}
.address-content{
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.address-content.has-width{
    width:calc(100% - 40px);
}
.address-content>div>div{
    display:inline-block;
    vertical-align:top;
}
.address-content>div>div:first-child{
    width:80px;
}
.address-content>div>div:last-child{
    width:calc(100% - 80px);
    color:$main-font-color;
}

.address-placeholder{
    line-height:30px;
    text-align:center;
    font-size:$middle-font-size;
    color:$other-font-color;
}

.add-popup-content{
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.add-popup-content>div{
    margin:15px 0;
}
.add-popup-content>div>div:first-child{
    margin-bottom:10px;
}
.add-popup-button{
    display:inline-block;
    width:50%;
}
</style>