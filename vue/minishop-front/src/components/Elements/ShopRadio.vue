<template>
<label
    class="shop-radio"
    :class="[
        type + '-radio',
        size + '-radio',
        {
            'is-bordered': bordered,
            'is-rounded': rounded,
            'is-checked': model === label,
            'is-disabled':disabled
        }
    ]">
    <div class="shop-radio-input"></div>
    <input 
        type="radio"
        ref="radio"
        v-model="model"
        :value="label"
        :disabled="disabled"
        @focus="focus = true"
        @blur="focus = false"
        @change="handleChange"
        />
    <div class="shop-radio-label">
        <slot></slot>
        <template v-if="!$slots.default">{{ label }}</template>
    </div>
</label>
</template>

<script>
export default{
    name:'ShopRadio',
    props:{
        type:{
            type:String,
            default:'default'
        },
        size:{
            type:String,
            default:'large'
        },
        bordered:{
            type:Boolean,
            default:false
        },
        rounded:{
            type:Boolean,
            default:false
        },
        label:{},
        value:{},
        disabled:{
            type:Boolean,
            default:false
        }
    },
    data(){
        return{
            focus:false
        }
    },
    computed:{
        model:{
            get(){
                return this.value
            },
            set(val){
                this.$emit('input',val)
                this.$refs.radio && (this.$refs.radio.checked = this.model === this.label)
            }
        }
    },
    methods:{
        handleChange(){
            this.$nextTick(()=>{
                this.$emit('change',this.model)
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-radio{
    margin-right:10px;
}
.shop-radio-input{
    border: 1px solid #dcdfe6;
    border-radius: 100%;
    width: 18px;
    height: 18px;
    margin-right:10px;
    background-color: #fff;
    position: relative;
    cursor: pointer;
    display: inline-block;
    box-sizing: border-box;
    vertical-align:middle;
}
.shop-radio-input:after{
    width: 4px;
    height: 4px;
    border-radius: 100%;
    background-color: #fff;
    content: "";
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%) scale(0);
    transition: transform .15s ease-in;
}
.shop-radio.is-checked .shop-radio-input{
    @include element-border-color(1);
    @include element-background(1);
}
.shop-radio.is-checked .shop-radio-input:after{
    transform: translate(-50%,-50%) scale(1)
}

.shop-radio input{
    opacity: 0;
    outline: none;
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0;
}
.shop-radio input:focus{
    outline:none;
}
.shop-radio .shop-radio-label{
    display:inline-block;
    vertical-align:middle;
}

.shop-radio.button-radio .shop-radio-input{
    display:none;
}
.shop-radio.button-radio .shop-radio-label{
    color:#fff;
    background-color:$other-font-color;
    border-width:1px;
    border-style:solid;
    border-color:$other-font-color;
}

.shop-radio.button-radio.is-rounded .shop-radio-label{
    border-radius:5px;
}

.shop-radio.button-radio.is-bordered .shop-radio-label{
    background-color:#fff;
    border-color:$other-font-color;
    color:$other-font-color;
}

.shop-radio.button-radio.is-checked .shop-radio-label{
    @include element-border-color(1);
    @include element-background(1);
    color:#fff;
}

.shop-radio.is-disabled .shop-radio-label{
    border-color:$disabled-color!important;
    background-color:$disabled-color!important;
    color:$other-font-color;
}

.button-radio.large-radio .shop-radio-label{
    padding:10px 20px;
}
.button-radio.medium-radio .shop-radio-label{
    padding:5px 15px;
    font-size:14px;
}
.button-radio.small-radio .shop-radio-label{
    padding:3px 8px;
    font-size:12px;
}
</style>