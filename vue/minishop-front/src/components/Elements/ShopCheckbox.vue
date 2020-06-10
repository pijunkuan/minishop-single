<template>
<label
    class="shop-checkbox"
    :class="[
        type + '-checkbox',
        type === 'button' ? size + '-checkbox' : '',
        {
            'is-checked':isChecked,
            'is-disabled':disabled,
            'is-rounded':rounded,
            'is-bordered':bordered
        }
    ]">
    <div class="shop-checkbox-input"></div>
    <input
        type="checkbox"
        :disabled="disabled"
        :value="label"
        v-model="model"
        @change="handleChange"
        @focus="focus = true"
        @blur="focus = false" />
    <div class="shop-checkbox-label">
        <slot></slot>
    </div>
</label>
</template>

<script>
export default{
    name:'ShopCheckbox',
    props:{
        type:{
            type:String,
            default:'default'
        },
        size:{
            type:String,
            default:'large'
        },
        disabled:Boolean,
        rounded:{
            type:Boolean,
            default:true
        },
        bordered:{
            type:Boolean,
            default:false
        },
        label:{},
        value:{},
    },
    computed:{
        model:{
            get(){
                return this.value !== undefined ? this.value : false
            },
            set(val){
                this.$emit('input',val)
            }
        },
        isChecked(){
            if({}.toString.call(this.model) === '[object Boolean]') return this.model
                else return this.model.indexOf(this.label) !== -1
        },
    },
    data(){
        return{
            focus:false
        }
    },
    methods:{
        handleChange(event){
            let value
            if(event.target.checked){
                value = this.label
            }
            this.$emit('change',value, event)
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-checkbox-input{
    border: 1px solid #dcdfe6;
    border-radius: 3px;
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
.shop-checkbox-input:after {
    box-sizing: content-box;
    content: "";
    border: 1px solid #fff;
    border-left: 0;
    border-top: 0;
    height: 9px;
    left: 5px;
    position: absolute;
    top: 1px;
    transform: rotate(45deg) scaleY(0);
    width: 5px;
    transition: transform .15s ease-in .05s;
    transform-origin: center;
}
.shop-checkbox.is-checked .shop-checkbox-input{
    @include element-background(1);
    @include element-border-color(1);
}
.shop-checkbox.is-checked .shop-checkbox-input:after{
    transform: rotate(45deg) scaleY(1);
}

.shop-checkbox input{
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
.shop-checkbox input:focus{
    outline:none;
}
.shop-checkbox-label{
    display:inline-block;
    vertical-align:middle;
}
.shop-checkbox-label>div{
    display:inline-block;
    vertical-align:middle;
}

.button-checkbox .shop-checkbox-input{
    display:none;
}
.button-checkbox .shop-checkbox-label{
    border-width:1px;
    border-style:solid;
    background-color:$other-font-color;
    border-color:$other-font-color;
    color:#fff;
}
.button-checkbox.is-rounded .shop-checkbox-label{
    border-radius:4px;
}
.button-checkbox.is-bordered .shop-checkbox-label{
    background-color:#fff;
    color:$other-font-color;
}

.button-checkbox.is-checked .shop-checkbox-label{
    @include element-background(1);
    @include element-border-color(1);
    color:#fff;
}

.shop-checkbox.is-disabled .shop-checkbox-input{
    border-color:$disabled-color;
    background-color:$disabled-color;
}
.shop-checkbox.is-disabled .shop-checkbox-label{
    color:$disabled-color;
}
.shop-checkbox.button-checkbox.is-disabled .shop-checkbox-label{
    color:$other-font-color;
    background-color:$disabled-color;
    border-color:$disabled-color;
}

.button-checkbox.large-checkbox .shop-checkbox-label{
    padding:10px 20px;
}
.button-checkbox.medium-checkbox .shop-checkbox-label{
    padding:8px 15px;
    font-size:14px;
}
.button-checkbox.small-checkbox .shop-checkbox-label{
    padding:3px 8px;
    font-size:12px;
}
</style>