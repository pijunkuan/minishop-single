<template>
<div>
    <div class="sale-header">
        <el-button size="mini">返回</el-button>
    </div>
    <div class="sale-content">
        <div class="sale-content-item">
            <div>活动名称</div>
            <div><el-input v-model="discount.discount_name"></el-input></div>
        </div>
        <div class="sale-content-item">
            <div>活动备注</div>
            <div>
                <el-input v-model="discount.discount_desc"></el-input>
                <div class="discount-tip">此项仅展示在后台页面</div>
            </div>
        </div>
        <div class="sale-content-item">
            <div>活动时间</div>
            <div>
                <el-date-picker v-model="datepicker" type="datetimerange" start-placeholder="开始日期" end-placeholder="结束日期" :default-time="['00:00:00','23:59:59']"></el-date-picker>
            </div>
        </div>
        <div class="sale-content-item">
            <div>适用用户</div>
            <div>
                <el-radio-group size="medium" v-model="discount.user_type">
                    <el-radio-button label="all">全部类型用户</el-radio-button>
                    <el-radio-button label="normal">普通用户</el-radio-button>
                    <el-radio-button label="vip">VIP会员</el-radio-button>
                </el-radio-group>
            </div>
        </div>
        <div class="sale-content-item">
            <div>活动类型</div>
            <div>
                <el-radio-group size="medium" v-model="discount.discount_type_code">
                    <el-radio-button label="price_minus">满元减</el-radio-button>
                    <el-radio-button label="price_discount">满元折</el-radio-button>
                    <el-radio-button label="piece_minus">满件减</el-radio-button>
                    <el-radio-button label="piece_discount">满件折</el-radio-button>
                </el-radio-group>
            </div>
        </div>
        <div class="sale-content-item">
            <div>活动商品</div>
            <div>
                <div style="margin:20px 0">
                    <el-button type="primary" size="small" :disabled="isAll" @click="selectItems">添加商品</el-button>
                    <el-checkbox style="margin-left:10px" v-model="isAll" size="small" @change="selectAll">选择全部商品</el-checkbox>
                </div>
                <div class="discount-items">
                    <div style="margin-bottom:6px">已选商品：</div>
                    <div v-if="isAll">全部商品</div>
                    <div v-if="discount.items.length === 0 && !isAll">暂无商品</div>
                    <div v-if="discount.items.length !== 0">
                        <span>{{ discount.items.length }} 个商品</span>
                        <el-button size="medium" type="text" style="margin-left:15px" @click="checkVisible = true">修改商品</el-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="sale-content-item">
            <div>活动优惠</div>
            <div>
                <div v-for="(rule,index) in discount.rules" :key="index" class="discount-rule-block">
                    <div class="rule-block-header">
                        <strong>优惠 {{ index + 1 }}</strong>
                        <el-button type="text" size="medium" style="float:right;position:relative;top:-8px;" @click="delRule(index)">删除优惠</el-button>
                    </div>
                    <div>
                        <div class="rule-content-header">优惠条件</div>
                        <div>
                            <span>单笔订单买满 </span>
                            <el-input-number v-model="rule.condition" :min="1" :precision="0" size="small" controls-position="right"></el-input-number>
                            <span> {{ discount.discount_type_code.indexOf('price') !== -1 ? '元' : '件' }}</span>
                        </div>
                        <div class="rule-content-header" style="margin-top:30px">优惠内容</div>
                        <div>
                            <span>{{ discount.discount_type_code.indexOf('minus') !== -1 ? '减' : '打' }} </span>
                            <el-input-number v-if="discount.discount_type_code.indexOf('minus') !== -1" v-model="rule.value" :min="1" :precision="2" size="small" controls-position="right"></el-input-number>
                            <el-input-number v-if="discount.discount_type_code.indexOf('discount') !== -1" v-model="rule.value" :min="1" :max="10" :precision="1" size="small" controls-position="right"></el-input-number>
                            <span> {{ discount.discount_type_code.indexOf('minus') !== -1 ? '元' : '折' }}</span>
                            <el-checkbox v-if="index === 0" style="margin-left:30px" v-model="rule.noLimit" @change="changeLimit($event)">上不封顶</el-checkbox>
                        </div>
                    </div>
                </div>
                <div v-if="discount.rules.length < 3"><el-button type="text" @click="addRule" :disabled="discount.rules[0].noLimit">添加优惠等级</el-button></div>
            </div>
        </div>
    </div>
    <item-dialog 
        :Visible="itemVisible"
        :Current="current_items"
        @confirm="confirmItems"
        @close="closeItems">
    </item-dialog>
    <el-dialog :visible="checkVisible" title="修改商品" width="800px">
        <div v-for="(item,index) in discount.items" :key="index" class="check-item-list">
            <el-image :src="item.img" style="width:50px;height:50px;" fit="scale-down"></el-image>
            <div style="width:calc(100% - 320px);margin:0 10px"><span class="item-tag" :class="{'is-sale':item.on_sale}">{{ item.on_sale ? '上架' : '下架' }}</span>{{ item.product_title }}</div>
            <div style="width:100px;">¥ {{ item.price }}</div>
            <div style="width:100px;">库存：{{ item.stock }}</div>
            <div style="width:50px"><el-button type="text" size="medium" @click="discount.items.splice(index,1)">删除</el-button></div>
        </div>
        <div slot="footer" style="text-align:center">
            <el-button size="small" type="primary" @click="checkVisible = false">确认</el-button>
        </div>
    </el-dialog>
</div>
</template>

<script>
import ItemDialog from '@/components/ItemDialog'
export default{
    components:{
        ItemDialog
    },
    data(){
        return{
            discount:{
                discount_name:'',
                discount_desc:'',
                start_time:'',
                end_time:'',
                discount_type_code:'price_minus',
                items:[],
                rules:[{ level:1, condition:1, value:1, noLimit:false }],
                status:'',
                user_type:'all'
            },
            datepicker:'',
            isAll:false,
            itemVisible:false,
            current_items:[],
            checkVisible:false
        }
    },
    methods:{
        selectItems(){
            this.current_items = []
            this.discount.items.map(v=>{
                this.current_items.push(v.id)
            })
            this.itemVisible = true
        },
        closeItems(){
            this.itemVisible = false
        },
        confirmItems(val){
            val.map(v=>{
                this.discount.items.push(v)
            })
            this.itemVisible = false
        },
        selectAll(){
            this.discount.items = []
        },
        addRule(){
            if(this.discount.rules.length === 3){
                this.$message.warning('最多只能添加3级优惠')
                return
            }
            let rule = {
                level: this.discount.rules.length,
                condition:0,
                value:0
            }
            this.discount.rules.push(rule)
        },
        delRule(index){
            if(this.discount.rules.length === 1){
                this.$message.warning('至少要有1级优惠')
                return
            }
            this.discount.rules.splice(index,1)
        },
        changeLimit(val){
            if(val && this.discount.rules.length !== 1){
                let _data = this.discount.rules[0]
                this.discount.rules = []
                this.discount.rules.push(_data)
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.sale-header{
    padding:10px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:5px 5px 0 0;
}
.sale-content{
    padding:10px;
    background-color:#fff;
    border:1px solid $line-color;
    border-radius:0 0 5px 5px;
}
.sale-content-item{
    padding:20px 10px;
    border-bottom:1px solid $line-color;
}
.sale-content-item>div:first-child{
    margin-bottom:5px;
    color:$sub-font-color;
}
.sale-content-item:last-child{
    border-bottom:none;
}
.discount-tip{
    color:$warning-color;
    margin-top:5px;
}
.discount-items{
    padding:10px;
    min-height:50px;
    border:1px solid $line-color * 0.98;
    border-radius:5px;
}

.check-item-list{
    margin-bottom:10px;
    border:1px solid $line-color * 0.98;
    border-radius:5px;
    padding:5px 10px;
}
.check-item-list>div{
    display:inline-block;
    vertical-align:middle;
}
.item-tag{
    padding:1px 5px;
    color:$other-font-color;
    font-size:$small-font-size;
    border:1px solid $other-font-color;
    border-radius:3px;
    margin-right:5px;
}
.item-tag.is-sale{
    color:$success-color;
    border-color:$success-color;
}

.discount-rule-block{
    border:1px solid $line-color * 0.98;
    border-radius:5px;
    margin-bottom:5px;
}
.discount-rule-block>div{
    padding:10px;
}
.rule-block-header{
    border-bottom:1px solid $line-color * 0.98;
    background-color:$light-back-sub;
}
.rule-content-header{
    margin-bottom:10px;
    color:$sub-font-color;
}
</style>