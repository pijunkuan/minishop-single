<template>
    <div>
        <div class="basic-set-block">
            <div class="basic-set-item">
                <div class="basic-set-item__title">网站名称</div>
                <div>
                    <el-input v-model="shop.shop_name"></el-input>
                </div>
            </div>
            <div class="basic-set-item">
                <div class="basic-set-item__title">网站域名</div>
                <div>
                    <el-input v-model="shop.shop_url" :disabled="$store.getters.shop_level === '免费版'">
                        <span slot="append">.{{ $store.getters.shop_host }}</span>
                    </el-input>
                    <div v-if="$store.getters.shop_level === '免费版'" class="basic-tip">免费版不可自定义网站域名，升级专业版即可自定义网站域名</div>
                </div>
            </div>
            <div class="seperate-line"></div>
            <div class="basic-set-item">
                <div class="basic-set-item__title">订单支付有效时间</div>
                <div style="margin:-10px 200px 0 20px">
                    <el-slider v-model="order.valid_pay" :step="12.5" :show-tooltip="false" show-stops :marks="slide_marks"></el-slider>
                </div>
                <div style="margin:30px 0 0 15px">顾客下单未付款时，订单将在<strong> {{ slide_marks[order.valid_pay].label === undefined ? slide_marks[order.valid_pay] : slide_marks[order.valid_pay].label }}
                    </strong>内有效，超出有效时间订单自动关闭</div>
            </div>
            <div class="basic-set-item">
                <div class="basic-set-item__title">订单自动确认收货</div>
                <div>
                    <div style="margin-bottom:10px">
                        <el-radio v-model="order.auto_receive" :label="true" border>开启自动确认收货</el-radio>
                        <span>支付订单后 </span>
                        <el-input style="width:60px" size="mini" v-model="order.auto_receive_time" type="number" step="1"
                            min="1" max="28" :disabled="!order.auto_receive"></el-input>
                        <span> 天自动确认收货 <span class="basic-tip">（可填写1-28）</span></span>
                    </div>
                    <div>
                        <el-radio v-model="order.auto_receive" :label="false" border>关闭自动确认收货</el-radio>
                    </div>
                </div>
            </div>
            <div class="seperate-line"></div>
            <div>
                <el-button type="primary" size="medium" @click="saveShop">保存</el-button>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        edit_shop
    } from '@/api/shop.js'
    export default {
        data() {
            return {
                shop: {
                    shop_name: '',
                    shop_level: '',
                    shop_url: '',
                    shop_status: '',
                    shop_id: '',
                    shop_active: '',
                    shop_host: '',
                    shop_exp: ''
                },
                order: {
                    valid_pay: 100,
                    auto_receive: true,
                    auto_receive_time: 28
                },
                slide_marks: {
                    0: '30分钟',
                    12.5: '1小时',
                    25: '2小时',
                    37.5: '4小时',
                    50: '6小时',
                    62.5: '8小时',
                    75: '12小时',
                    87.5: '18小时',
                    100: {
                        style: {
                            width: '50px'
                        },
                        label: '24小时'
                    }
                },
                auto_close_time: [{
                        key: 0,
                        value: 30
                    },
                    {
                        key: 12.5,
                        value: 60
                    },
                    {
                        key: 25,
                        value: 120
                    },
                    {
                        key: 37.5,
                        value: 240
                    },
                    {
                        key: 50,
                        value: 360
                    },
                    {
                        key: 62.5,
                        value: 480
                    },
                    {
                        key: 75,
                        value: 720
                    },
                    {
                        key: 87.5,
                        value: 1080
                    },
                    {
                        key: 100,
                        value: 1440
                    },
                ]
            }
        },
        methods: {
            saveShop() {
                let _data = {
                    shop_name: this.shop.shop_name,
                    shop_url: this.shop.shop_url,
                    auto_close_in: this.auto_close_time.filter((val) => {
                        return val.key === this.order.valid_pay
                    })[0].value

                }
                if (this.order.auto_receive) {
                    _data.auto_receive_in = this.order.auto_receive_time
                } else {
                    _data.auto_receive_in = 0
                }
                console.log(_data)
                edit_shop(_data).then(() => {
                    this.$message.success('更新成功')
                    this.$store.dispatch('getShopInfo')
                    setTimeout(() => {
                        this.$router.go(0)
                    }, 1000)
                }).catch(() => {
                    this.$message.error('更新失败')
                })
            }
        },
        watch: {
            '$store.getters.shop_name': {
                deep: true,
                immediate: true,
                handler: function() {
                    if (this.$store.getters.shop_name !== undefined && this.$store.getters.shop_name) {
                        this.shop.shop_name = this.$store.getters.shop_name
                        this.shop.shop_url = this.$store.getters.shop_url
                        this.shop.shop_level = this.$store.getters.shop_level
                        this.shop.shop_id = this.$store.getters.shop_id
                        this.shop.shop_host = this.$store.getters.shop_host
                        this.shop.shop_active = this.$store.getters.shop_active
                        this.shop.shop_status = this.$store.getters.shop_status
                        this.shop.shop_exp = this.$store.getters.shop_exp
                        this.order.valid_pay = this.auto_close_time.filter((val) => {
                            return val.value === this.$store.getters.shop_auto_close_in
                        })[0].key
                        if (this.$store.getters.shop_auto_receive_in > 0) {
                            this.order.auto_receive_time = this.$store.getters.shop_auto_receive_in
                            this.order.auto_receive = true
                        } else {
                            this.order.auto_receive_time = 0
                            this.order.auto_receive = false
                        }
                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@/assets/style/base.scss';

    .basic-set-block {
        border: 1px solid $line-color;
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
    }

    .basic-set-item {
        margin-bottom: 50px;
    }

    .basic-set-item__title {
        margin-bottom: 10px;
        font-weight: 500;
    }

    .basic-tip {
        color: $warning-color;
        margin-top: 5px;
    }

    .seperate-line {
        width: 100%;
        height: 1px;
        margin: 20px 0 50px;
        border-bottom: 1px dotted $line-color * 0.93;
    }
</style>
<style lang="scss">
    @import '@/assets/style/base.scss';

    .el-slider__stop {
        background-color: $light-back-sub * 0.98 !important;
    }
</style>
