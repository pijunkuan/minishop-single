<template>
<div>
    <div
        class="input-num-btn input-num-btn__minus"
        :class="{'is-disabled':minusDisabled}"
        @click="toMinus">
        -
    </div>
    <div 
        class="input-num-input"
        :class="[
            size + '-num-input',
            {
                'is-focused':focused,
                'is-disabled':disabled
            }
        ]"
        @click="handleFocus">
        {{ currentValue }}
    </div>
    <div
        class="input-num-btn input-num-btn__plus"
        :class="{'is-disabled':addDisabled}"
        @click="toAdd">
        +
    </div>
    <transition name="input-num-select">
        <div v-show="isSelect" class="input-num-select">
            <div class="input-num-select__title" @click="handleFocus">{{ title ? title : '选择数值' }}</div>
            <mt-picker 
            ref="test"
                :slots="valueArray" 
                @change="handleChange">
            </mt-picker>
            <div class="input-num-select__button">
                <shop-button 
                    :rounded="false" 
                    @click="confirm">
                    确定
                </shop-button>
            </div>
        </div>
    </transition>
    <transition name="input-num-cover">
        <div v-show="isSelect" class="input-num-cover" @click="confirm"></div>
    </transition>
</div>
</template>

<script>
export default{
    name:'ShopInputNum',
    props:{
        max:{
            type:Number,
            default:Infinity
        },
        min:{
            type:Number,
            default:0
        },
        value:{},
        disabled:Boolean,
        size:{
            type:String,
            default:'medium'
        },
        title:{
            type:String,
            default:null
        }
    },
    data(){
        return{
            currentValue:0,
            oriValue:0,
            isSelect:false,
            valueArray:[
                {
                    flex: 1,
                    defaultIndex:null,
                    values: [],
                    className: 'input-num-slot'
                }
            ],
            focused:false,
            loading:false
        }
    },
    computed:{
        addDisabled(){
            return this.currentValue >= this.max || this.disabled
        },
        minusDisabled(){
            return this.currentValue <= this.min || this.disabled
        }
    },
    watch:{
        value:{
            immediate:true,
            handler:function(val){
                let newVal = val === undefined ? val : Number(val)
                if(newVal !== undefined){
                    if(isNaN(newVal)) return
                }
                if(newVal >= this.max) newVal = this.max
                if(newVal <= this.min) newVal = this.min
                this.currentValue = newVal
                this.oriValue = newVal
            }
        }
    },
    methods:{
        handleFocus(){
            if(this.disabled) return
            this.focused = true
            this.isSelect = true
            if(this.max !== Infinity){
                for(let i = this.min; i <= this.max; i++){
                    this.valueArray[0].values.push(i)
                }
            }else if(this.max === Infinity){
                for(let i = this.min; i < 1000; i++){
                    this.valueArray[0].values.push(i)
                }
            }
            this.valueArray[0].defaultIndex = this.valueArray[0].values.indexOf(this.currentValue)
        },
        handleBlur(){
            this.focused = false
            this.isSelect = false
            this.valueArray[0].values = []
        },
        handleChange(picker,values){
            if(values.indexOf(undefined) !== -1){
                return
            }else{
                this.currentValue = values[0]
            }
        },
        confirm(){
            this.oriValue = Number(this.currentValue)
            this.$emit('change',this.currentValue)
            this.handleBlur()
        },
        toAdd(){
            if(this.addDisabled) return
            if(this.loading) return
            this.loading = true
            this.currentValue += 1
            this.$emit('change',this.currentValue)
            setTimeout(()=>{
                this.loading = false
            },500)
        },
        toMinus(){
            if(this.minusDisabled) return
            if(this.loading) return
            this.loading = true
            this.currentValue -= 1
            this.$emit('change',this.currentValue)
            setTimeout(()=>{
                this.loading = false
            },500)

        }
    }
}
</script>

<style>
.input-num-input input{
    border-radius:5px!important;
    padding:5px 8px!important;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.input-num-btn,.input-num-input{
    display:inline-block;
    vertical-align:middle;
    text-align:center;
}
.input-num-btn.is-disabled{
    color:$disabled-color;
}
.input-num-btn__minus{
    border-top-left-radius:3px;
    border-bottom-left-radius:3px;
}
.input-num-btn__plus{
    border-top-right-radius:3px;
    border-bottom-right-radius:3px;
}
.input-num-btn{
    height:24px;
    width:24px;
    background-color:$line-color;
    border:1px solid $line-color;
    color:$sub-font-color;
}
.input-num-input{
    width:40px;
    height:24px;
    padding:0 8px;
    font-size:$small-font-size;
    line-height:24px;
    border-width:1px;
    border-style:solid;
    border-color:$line-color;
    color:$sub-font-color;
}
.input-num-input.is-focused{
    @include element-border-color(1);
    @include element-color(1);
}
.input-num-input.is-disabled{
    background-color:$line-color;
    color:$disabled-color;
}

.input-num-select-enter-active,.input-num-select-leave-active{
    transition:all .3s ease-in;
}
.input-num-select-enter,.input-num-select-leave-to{
    transform: translateY(200px);
}
.input-num-select{
    position:fixed;
    left:0;
    right:0;
    bottom:0;
    z-index:2001;
    padding-top:10px;
    border-top:$line-color;
    border-width:1px;
    border-style:solid;
    background-color:#fff;
}
.input-num-select__title{
    text-align:center;
    margin-bottom:10px;
    color:$main-font-color;
}
.input-num-select__button{
    padding-top:10px;
}

.input-num-cover-enter-active,.input-num-cover-leave-active{
    transition:opacity .2s ease-in;
}
.input-num-cover-enter,.input-num-cover-leave-to{
    opacity:0;
}
.input-num-cover{
    position:fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background-color:rgba(0,0,0,.75);
    z-index:2000;
}
</style>