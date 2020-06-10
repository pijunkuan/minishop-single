<template>
<div>
    <div 
        class="shop-input-content"
        :class="[
            size + '-input',
            validStatus ? validStatus + '-input' : '',
            {
                'is-rounded':rounded,
                'is-bordered':bordered
            }
        ]">
        <div v-if="$slots.prepend" class="input-prepend"><slot name="prepend"></slot></div>
        <input 
            ref="input"
            v-bind="$attrs"
            :type="type"
            class="shop-input"
            :class="{
                'is-append':$slots.append,
                'is-prepend':$slots.prepend
            }"
            :disabled="disabled"
            :placeholder="placeholder"
            @input="handleInput"
            @focus="handleFocus"
            @blur="handleBlur"
            @change="handleChange"
        />
        <!-- <div v-if="clearable" class="input-clear-btn">
            <i class="iconfont icon-close" @click="clear"></i>
        </div> -->
        <div v-if="$slots.append" class="input-append"><slot name="append"></slot></div>
    </div>
    <div class="validate" v-if="validate">
        <div class="validate-message" :class="validStatus + '-message'">{{ validMessage }}</div>
    </div>
</div>
</template>

<script>
import AsyncValidator from 'async-validator'
export default{
    name:'ShopInput',
    props:{
        value:[String, Number],
        size:{
            type:String,
            default:'large'
        },
        rounded:{
            type:Boolean,
            default:true
        },
        bordered:{
            type:Boolean,
            default:true
        },
        disabled:{
            type:Boolean,
            default:false
        },
        // showClear:{
        //     type:Boolean,
        //     default:true
        // },
        validate:{
            type:Boolean,
            default:false
        },
        rules:{
            type:Array, /* rules:[{required:true,message:''}]*/
            default:null
        },
        placeholder:String,
        type:String
    },
    data(){
        return{
            focused:false,
            inputValue:'',
            validMessage:'',
            validStatus:null
        }
    },
    mounted(){
        this.setNativeInputValue()
    },
    computed:{
        nativeInputValue(){
            return this.value === null || this.value === undefined ? '' : String(this.value)
        },
        // clearable(){
        //     return this.showClear && this.nativeInputValue
        // }
    },
    methods:{
        setNativeInputValue(){
            const input = this.getInput()
            if(!input) return
            if(input.value === this.nativeInputValue) return
            input.value = this.nativeInputValue
        },
        getInput(){
            return this.$refs.input
        },
        handleChange(event){
            this.$emit('change',event.target.value)
        },
        handleBlur(event){
            this.focused = false
            this.$emit('blur',event)
            if(this.validate) this.validation()
        },
        handleFocus(event){
            this.focused = true
            this.$emit('focus',event)
        },
        handleInput(event){
            this.$emit('input',event.target.value)
        },
        clear(){
            this.$emit('input','')
            this.$emit('change','')
            this.$emit('clear')
        },
        validation(callback){
            let valid = true
            let rule = {
                rule:this.rules
            }
            const validator = new AsyncValidator(rule)
            let model = {
                rule:this.value
            }
            validator.validate(model,(errors) => {
                this.validStatus = errors ? 'error' : 'success'
                this.validMessage = errors ? errors[0].message : ''
                if(errors){
                    valid = false
                }else{
                    valid = true
                }
                callback(valid)
            })
        },
        clearValidate(){
            this.validStatus = ''
            this.validMessage = ''
        }
    },
    watch:{
        nativeInputValue(){
            this.setNativeInputValue()
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-input-content{
    border:none;
    color:$sub-font-color;
    background-color:#fff;
    font-size:14px;
}
.shop-input:focus{
    outline:none;
}
.shop-input{
    width:calc(100% - 20px);
    margin:5px;
    border:none;
    vertical-align:middle;
}

.input-clear-btn{
    position:relative;
    display:inline-block;
    top:-1px;
    margin-left:-20px;
    margin-top:-2px;
    height:16px;
    width:16px;
    line-height:12px;
    text-align:center;
    background-color:$disabled-color;
    border-radius:50%;
}
.input-clear-btn i{
    font-size:12px;
}

.shop-input-content.is-bordered{
    border-width:1px;
    border-style:solid;
    border-color:$line-color;
    border-radius:5px;
}
.shop-input-content.is-bordered:focus{
    @include element-border-color(1);
}

.shop-input-content.is-rounded{
    border-radius:25px;
}

.validate{
    margin:5px 0;
}
.validate-message{
    font-size:14px;
    padding-left:10px;
}
.validate-message.error-message{
    color:$error-color;
}
.shop-input-content.success-input{
    border-color:$success-color;
}
.shop-input-content.error-input{
    border-color:$error-color;
}

.large-input{
    padding:8px 10px;
}
.medium-input{
    padding:5px 8px;
}
.small-input{
    padding:3px 5px;
}

.shop-input.is-append{
    width:calc(100% - 40px);
    margin-right:0;
}
.shop-input.is-prepend{
    width:calc(100% - 70px);
    margin-left:10px;
}
.shop-input.is-prepend.is-append{
    width:calc(100% - 114px);
    margin-left:10px;
    margin-right:0;
}

.input-prepend,.input-append{
    position:relative;
    display:inline-block;
    width:42px;
    overflow:hidden;
    vertical-align:middle;
}
.input-prepend{
    text-align:right;
}
.input-append{
    text-align:center;
}
</style>