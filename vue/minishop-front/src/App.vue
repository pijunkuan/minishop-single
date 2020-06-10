<template>
    <div id="app" :data-theme="themeName + '-theme'" style="background-color:#f5f5f5">
        <page-header v-if="headerType === 'header'" class="page-header">
            <div slot="title">{{ $route.meta.title }}</div>
            <div slot="right" v-if="$route.name === 'Product'" @click="toCart">
                <shop-icon class="header-icon" name="shiwu-gouwuche" size="small"></shop-icon>
            </div>
            <div v-else slot="right">
                <shop-icon class="header-icon" name="xiaoxi" size="small"></shop-icon>
            </div>
        </page-header>
        <page-search v-if="headerType === 'search'"></page-search>
        <keep-alive>
            <router-view
                    v-if="$route.meta.keepAlive"
                    class="page-container"
                    :style="{bottom: (bottomType === 'menubar' ? 66 : 0) + 'px', top: (headerType === '' ? 0 : 49 + 'px')}"/>
        </keep-alive>
        <router-view
                v-if="!$route.meta.keepAlive"
                class="page-container"
                :style="{bottom: (bottomType === 'menubar' ? 66 : 0) + 'px', top: (headerType === '' ? 0 : 49 + 'px')}"/>
        <page-bottom
                v-if="bottomType === 'menubar'"
                class="page-bottom"
                :defaultIndex="defaultIndex"></page-bottom>
    </div>
</template>

<script>
    import PageHeader from '@/components/Modules/Layouts/PageHeader'
    import PageSearch from '@/components/Modules/Layouts/PageSearch'
    import PageBottom from '@/components/Modules/Layouts/PageBottom'
    import {getToken, getExtime, setToken, setExtime} from '@/utils/auth'
    import {refresh_token} from '@/api/login'
    import {MessageBox} from 'mint-ui'

    export default {
        components: {
            PageHeader,
            PageSearch,
            PageBottom
        },
        data() {
            return {
                headerType: '',
                bottomType: '',
                defaultIndex: 0,
                current_time: new Date().getTime(),
                t1: ''
            }
        },
        mounted() {
            this.t1 = getToken() ? setInterval(this.checkTime, 1000) : ''
        },
        computed: {
            themeName() {
                const theme = this.$store.getters.theme
                return theme
            }
        },
        methods: {
            checkTime() {
                this.current_time = new Date().getTime()
                if (this.current_time === (getExtime() * 1 - 1000)) {
                    refresh_token().then(r => {
                        setToken(r.data.body.access_token)
                        setExtime(r.data.body.expires_in)
                    }).catch(e => {
                        if (e.response.status === 401) this.$store.dispatch('logout')
                    })
                }
            },
            toCart() {
                if (getToken()) {
                    this.$router.push({name: 'Cart'})
                } else {
                    MessageBox({
                        title: '提示',
                        message: '请登录后加入购物车',
                        confirmButtonText: '现在登录',
                        showCancelButton: true,
                        cancelButtonText: '再逛逛'
                    }).then(action => {
                        if (action === 'cancel') {
                            return
                        } else {
                            this.$router.push({
                                name: 'Login',
                                query: {from: this.$route.name, type: 'id', query: this.$route.query.id}
                            })
                        }
                    })
                }
            }
        },
        watch: {
            '$route'(to) {
                //页头显示
                if (to.name === 'Search' || to.name === 'Home') this.headerType = 'search'
                else if (to.name === 'UserHome' || to.name === 'Checkpay' || to.name === 'Checksuc') this.headerType = ''
                else this.headerType = 'header'

                //页脚显示
                if (to.name !== 'Product' && to.name !== 'Cart' && to.name !== 'Checkout' && to.name !== 'UserOrderDetail') this.bottomType = 'menubar'
                else this.bottomType = ''

                //底部高亮
                if (to.name === 'Home') this.defaultIndex = 1
                else if (to.name === 'Types') this.defaultIndex = 2
                else if (to.name === 'Cart') this.defaultIndex = 3
                else if (to.name === 'Login' || to.name === 'Register' || to.name === 'Forget' || to.name === 'UserHome' || to.name === 'UserWallet') this.defaultIndex = 4
                else this.defaultIndex = 0
            }
        }
    }
</script>

<style lang="scss">
    @import '@/assets/style/base.scss';

    .page-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
    }

    .header-icon {
        color: rgba(255, 255, 255, .8) !important;
        text-align: right;
    }

    .page-container {
        position: absolute;
        left: 0;
        right: 0;
        background-color: $background-color;
        overflow-y: scroll;
    }

    .page-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        box-shadow: 0 -1px 5px 2px rgba(0, 0, 0, 0.05);
    }
</style>
