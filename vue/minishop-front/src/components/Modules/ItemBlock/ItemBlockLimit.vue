<template>
<div style="position:realtive">
    <div class="item-block-5__title">
        <div class="item-block-5__title-title">{{ title }}</div>
        <div class="item-block-5__title-time">
            <div class="item-time-timeblock">{{ day > 9 ? day : ('0' + day) }}</div>
            <div>:</div>
            <div class="item-time-timeblock">{{ hour > 9 ? hour : ('0' + hour) }}</div>
            <div>:</div>
            <div class="item-time-timeblock">{{ minute > 9 ? minute : ('0' + minute) }}</div>
            <div>:</div>
            <div class="item-time-timeblock">{{ second > 9 ? second : ('0' + second) }}</div>
        </div>
    </div>
    <div v-if="isEnd" class="limit-cover">活动已结束</div>
    <horizon-list :items="items" :loading="loading"></horizon-list>
</div>
</template>

<script>
import HorizonList from '../ProductList/HorizonList'
export default{
    components:{
        HorizonList
    },
    props:{
        title:String,
        items:Array,
        endTime:String,
        loading:Boolean
    },
    data(){
        return{
            day:0,
            hour:0,
            minute:0,
            second:0,
            end: null,
            isEnd:false
        }
    },
    methods:{
        getTime(){
            this.end = new Date(this.endTime)
            let _now = new Date()
            let _left = this.end.getTime() - _now.getTime()
            if(_left <= 0){
                this.day = 0
                this.hour = 0
                this.minute = 0
                this.second = 0
            }else{
                this.day = Math.floor(_left / 1000 / 60 / 60 / 24).toFixed(0)
                this.hour = Math.floor(_left / 1000 / 60 / 60 - this.day * 24).toFixed(0)
                this.minute = Math.floor(_left / 1000 / 60 - this.hour * 60 - this.day * 24 * 60).toFixed(0)
                this.second = Math.floor(_left / 1000 - this.minute * 60 - this.hour * 60 * 60 - this.day * 24 * 60 * 60).toFixed(0)
            }
        },
        timeCount(){
            let _left = new Date(this.endTime) - new Date().getTime()
            if(_left <= 0){
                this.isEnd = true
                return
            }
            setTimeout(()=>{
                this.timeCount()
                this.getTime()
            },1000)
        }
    },
    watch:{
        endTime:{
            deep:true,
            immediate:true,
            handler:function(){
                if(this.endTime){
                    this.isEnd = false
                    this.timeCount()
                }
            }
        }
    },
    beforeDestroy:function(){
        this.getTime.destroy()
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.item-block-5__title{
    padding:10px;
    border-bottom:1px solid $line-color;
    background-color:#fff;
    font-size:$normal-font-size;
    color:$main-font-color;
}
.item-block-5__title-title,.item-block-5__title-time{
    display:inline-block;
}
.item-block-5__title-title{
    width:calc(100% - 120px);
}
.item-block-5__title-time{
    font-size:$small-font-size;
    width:120px;
    text-align:right;
}
.item-block-5__title-time>div{
    display:inline-block;
    vertical-align:middle;
}
.item-time-timeblock{
    margin:0 2px;
    width:20px;
    height:20px;
    line-height:20px;
    text-align:center;
    background-color:$sub-font-color;
    color:#fff;
}
.item-time-timeblock:last-child{
    @include sale-background(1);
}

.limit-cover{
    width:100%;
    height:140px;
    position:absolute;
    z-index:2;
    background-color:rgba(0,0,0,0.8);
    color:#fff;
    text-align:center;
    line-height:140px;
}
</style>