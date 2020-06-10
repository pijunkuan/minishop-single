<template>
<div>
    <div
        class="shop-dispicker-input"
        :class="[
            size + 'dispicker-input',
            {
                'is-focused':focused,
                'is-disabled':disabled
            }
        ]"
        @click="handleFocus">
        {{ (currentValue[0] === undefined ? '.. ' : currentValue[0]) + '/' + (currentValue[1] === undefined ? '.. ' : currentValue[1]) + '/' + (currentValue[2] === undefined ? '..' : currentValue[2]) }}
    </div>
    <transition name="shop-dispicker-select">
        <div v-show="isSelect" class="shop-dispicker-select">
            <div class="shop-dispicker-select__title" @click="handleFocus">{{ title ? title : '选择省市区' }}</div>
            <mt-picker
                ref="shopPicker"
                :slots="districts"
                class="shop-dispicker"
                @change="handleDistrictChange">
            </mt-picker>
            <div class="shop-dispicker-select__button">
                <shop-button
                    :rounded="false"
                    @click="confirm">
                    确定
                </shop-button>
            </div>
        </div>
    </transition>
    <transition name="shop-dispicker-cover">
        <div v-show="isSelect" class="shop-dispicker-cover" @click="confirm"></div>
    </transition>
</div>
</template>

<script>
import Data from '@/assets/json/district.json'
export default{
    props:{
        value:Array,
        size:{
            type:String,
            default:'medium'
        },
        disabled:Boolean,
        title:String
    },
    data(){
        return{
            currentValue:[],
            newValue:[],
            oriValue:[],
            focused:false,
            isSelect:false,
            districts:[
                {
                    flex:1,
                    defaultIndex:0,
                    values:Object.keys(Data),
                    textAlign:'center'
                },{
                    divider:true,
                    content:'-'
                },{
                    flex:1,
                    defaultIndex:0,
                    values:[],
                    textAlign:'center'
                },{
                    divider:true,
                    content:'-'
                },{
                    flex:1,
                    defaultIndex:0,
                    values:[],
                    textAlign:'center'
                }
            ]
        }
    },
    methods:{
        handleDistrictChange(picker,values){
            let city = Object.keys(Data[values[0]])
            picker.setSlotValues(1,city)

            let index = city.indexOf(values[1])
            let district = Data[values[0]][city[index]]
            picker.setSlotValues(2,district)

            this.newValue = values
        },
        confirm(){
            this.currentValue = []
            this.currentValue.push(this.newValue[0])
            this.currentValue.push(this.newValue[1])
            this.currentValue.push(this.newValue[2])
            this.oriValue = this.currentValue
            this.$emit('change', this.newValue)
            this.handleBlur()
        },
        handleFocus(){
            if(this.disabled) return
            this.focused = true
            this.isSelect = true

            let provinceIndex
            if(this.oriValue[0] !== undefined){
                this.districts[0].defaultIndex = this.districts[0].values.indexOf(this.oriValue[0])
            }else{
                this.districts[0].defaultIndex = 0
            }

            provinceIndex = this.districts[0].defaultIndex
            let city = Object.keys(Data[this.districts[0].values[provinceIndex]])
            this.districts[2].values = city

            let cityIndex
            if(this.oriValue[1] !== undefined){
                this.districts[2].defaultIndex = city.indexOf(this.oriValue[1])
            }else{
                this.districts[2].defaultIndex = 0
            }

            cityIndex = this.districts[2].defaultIndex
            let district = Data[this.districts[0].values[provinceIndex]][city[cityIndex]]
            this.districts[4].values = district

            if(this.oriValue[2] !== undefined){
                this.districts[4].defaultIndex = district.indexOf(this.oriValue[2])
            }else{
                this.districts[4].defaultIndex = 0
            }
        },
        handleBlur(){
            this.focused = false
            this.isSelect = false
        }
    },
    watch:{
        value:{
            deep:true,
            immediate:true,
            handler:function(){
                this.currentValue = this.value
                this.oriValue = this.value
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.shop-dispicker-input{
    padding:10px 8px;
    border-width:1px;
    border-style:solid;
    border-radius:4px;
    border-color:$line-color;
    color:$sub-font-color;
}
.shop-dispicker-input.is-focused{
    @include element-border-color(1);
    @include element-color(1);
}
.shop-dispicker-input.is-disabled{
    background-color:$disabled-color;
    border-color:$disabled-color;
    color:$other-font-color;
}

.shop-dispicker-select-enter-active,.shop-dispicker-select-leave-active{
    transition:all .3s ease-in;
}
.shop-dispicker-select-enter,.shop-dispicker-select-leave-to{
    transform: translateY(200px);
}
.shop-dispicker-select{
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
.shop-dispicker-select__title{
    text-align:center;
    margin-bottom:10px;
    color:$main-font-color;
}
.shop-dispicker-select__button{
    padding-top:10px;
}

.shop-dispicker-cover-enter-active,.shop-dispicker-cover-leave-active{
    transition:opacity .2s ease-in;
}
.shop-dispicker-cover-enter,.shop-dispicker-cover-leave-to{
    opacity:0;
}
.shop-dispicker-cover{
    position:fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background-color:rgba(0,0,0,.75);
    z-index:2000;
}
</style>