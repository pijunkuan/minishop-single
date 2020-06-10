<template>
    <button
        class="shop-button"
        :class="[
            type + '-button',
            size + '-button',
            {
                'is-loading':loading,
                'is-disabled':disabled,
                'is-rounded':rounded
            }
        ]"
        :disabled="disabled || loading"
        @click="handleClick">
        <span v-if="$slots.default"><slot></slot></span>
    </button>
</template>

<script>
export default {
    name:'ShopButton',
    props:{
        type:{
            type:String,
            default:'default'
        },
        size:{
            type:String,
            default:'large'
        },
        loading:{
            type:Boolean,
            default:false
        },
        disabled:{
            type:Boolean,
            default:false
        },
        rounded:{
            type:Boolean,
            default:true
        }
    },
    methods:{
        handleClick(event){
            this.$emit('click',event)
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-button{
    border-width:1px;
    border-radius:0;
}
.shop-button:active{
    border-style:solid;
}
.shop-button:focus{
    outline:none;
}
.shop-button.is-rounded{
    border-radius:5px;
}

.large-button{
    width:100%;
    height:44px;
    font-size:$normal-font-size;
}
.medium-button{
    width:115px;
    height:44px;
    font-size:$middle-font-size;
}
.small-button{
    width:80px;
    height:25px;
    font-size:$small-font-size;
}

.border-button{
    background-color:#fff;
    border-width:1px;
    @include element-color(1);
    @include element-border-color(1);
}
.border-button.is-loading{
    transition:all 1s ease-in;
    color:#fff;
    @include element-background(1.05);
    @include element-border-color(1.05);
}
.border-button.is-disabled{
    border-color:$disabled-color;
    color:$disabled-color;
}

.default-button{
    color:#fff;
    border-width:1px;
    @include element-background(1);
    @include element-border-color(1);
}
.default-button.is-loading{
    transition:all 1s ease-in;
    @include element-background(1.15);
    @include element-border-color(1.15);
}
.default-button.is-disabled{
    background-color:$disabled-color;
    border-color:$disabled-color;
}
</style>