<template>
<div
    class="shop-popup"
    :class="[
        type + '-popup',
        size + '-popup',
        {
            'full-cover':full,
            'display':show
        }
    ]">
    <transition name="shop-popup-content">
        <div v-show="show" class="shop-popup-content">
            <div 
                v-if="$slots.title && type === 'bottom'" 
                class="shop-popup-content__title">
                <slot name="title"></slot>
            </div>
            <div 
                v-if="$slots.content" 
                class="shop-popup-content__content"
                :class="{'no-title':!$slots.title, 'no-button':!$slots.button}">
                <slot name="content"></slot>
            </div>
            <div 
                v-if="$slots.button && type === 'bottom'" 
                class="shop-popup-content__button">
                <slot name="button"></slot>
            </div>
        </div>
    </transition>
    <transition name="shop-popup-cover">
        <div v-show="show" class="shop-popup-cover" @click="close"></div>
    </transition>
</div>
</template>

<script>
export default{
    name:'ShopPopup',
    props:{
        show:{
            type:Boolean,
            default:false
        },
        type:{
            type:String,
            default:'bottom'
        },
        size:{
            type:String,
            default:'large'
        },
        full:{
            type:Boolean,
            default:false
        }
    },
    data(){
        return{
            visible:false
        }
    },
    methods:{
        close(){
            this.$emit('close')
        }
    },
    watch:{
        show:{
            handler:function(nv){
                this.$emit('update:show',nv)
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-popup{
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    display:none;
}
.shop-popup.display{
    display:block;
}
.shop-popup-content-enter-active,.shop-popup-content-leave-active{
    transition:all .2s ease-in;
}
.shop-popup-content-enter,.shop-popup-content-leave-to{
    transform: translateY(80%);
}
.shop-popup-content{
    position:fixed;
    z-index:1001;
    padding-top:10px;
    border-top:$line-color;
    border-width:1px;
    border-style:solid;
    background-color:#fff;
    width:100%;
    overflow:hidden;
}
.shop-popup-content__title{
    text-align:center;
    line-height:30px;
    color:$sub-font-color;
}
.shop-popup-content__button{
    position:fixed;
    bottom:0;
    left:0;
    right:0;
    height:44px;
}
.shop-popup-content__content{
    margin-top:10px;
    padding:0 10px;
    height:calc(100% - 94px);
    overflow-y:scroll;
    overflow-x:none;
}
.shop-popup-content__content.no-title{
    margin-top:0;
    height:calc(100% - 54px);
}
.shop-popup-content__content.no-button{
    height:calc(100% - 50px);
}
.shop-popup-content__content.no-title.no-button{
    height:calc(100% - 10px);
}

.shop-popup.bottom-popup .shop-popup-content{
    left:0;
    right:0;
    bottom:0;
}
.shop-popup.right-popup .shop-popup-content{
    top:0;
    right:0;
    bottom:0;
}
.shop-popup.right-popup .shop-popup-content-enter,.shop-popup-content-leave-to{
    transform:translateX(80%);
}
.shop-popup.top-popup .shop-popup-content{
    left:0;
    right:0;
    top:0;
}
.shop-popup.top-popup .shop-popup-content-enter,.shop-popup-content-leave-to{
    transform:translateY(-80%);
}

.shop-popup.large-popup .shop-popup-content{
    height:80%;
}
.shop-popup.medium-popup .shop-popup-content{
    height:50%;
}
.shop-popup.small-popup .shop-popup-content{
    height:90px;
}

.shop-popup.right-popup.large-popup .shop-popup-content{
    width:80%;
    height:100%;
}
.shop-popup.right-popup.medium-popup .shop-popup-content{
    width:50%;
    height:100%;
}
.shop-popup.right-popup.small-popup .shop-popup-content{
    width:90px;
    height:100%;
}

.shop-popup.full-cover .shop-popup-content{
    height:100%!important;
    width:100%!important;
}

.shop-popup-cover-enter-active,.shop-popup-cover-leave-active{
    transition:opacity .2s ease-in;
}
.shop-popup-cover-enter,.shop-popup-cover-leave-to{
    opacity:0;
}
.shop-popup-cover{
    position:fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background-color:rgba(0,0,0,.75);
    z-index:1000;
}
.shop-popup-cover.full-cover{
    display:none;
}
</style>