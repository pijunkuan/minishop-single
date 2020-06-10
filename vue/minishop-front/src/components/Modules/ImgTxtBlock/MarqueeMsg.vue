<template>
<div>
    <div class="scroll-message-block">
        <shop-icon class="scroll-message-icon" name="gonggao" size="mini"></shop-icon>
        <div id="box" class="scroll-message-content">
            <div id="content"><p id="message" class="scroll-message-content__scroll">{{ message }}</p></div>
        </div>
    </div>
</div>
</template>

<script>
export default{
    props:{
        message:String
    },
    mounted(){
        this.$nextTick(()=>{
            setTimeout(()=>{
                let _boxWidth = window.innerWidth - 35
                if(document.getElementById('message')){
                    let _msgWidth = document.getElementById('message').offsetWidth
                    if(_boxWidth > _msgWidth) return
                    document.getElementById('message').classList.add('padding')
                    this.scrollText(_msgWidth)
                }
            },1000)
        })
        window.onresize = () => {
            setTimeout(()=>{
                let _boxWidth = window.innerWidth - 35
                if(document.getElementById('message')){
                    let _msgWidth = document.getElementById('message').offsetWidth
                    if(_boxWidth > _msgWidth) return
                    document.getElementById('message').classList.add('padding')
                    this.scrollText(_msgWidth)
                }
            },1000)
        }
    },
    methods:{
        scrollText(msgWidth){
            let box = document.getElementById('box')
            if(!box) return
            let maxScroll = box.scrollWidth - box.clientWidth
            if(box.scrollLeft >= maxScroll){
                box.scrollLeft = 0
                setTimeout(()=>{
                    this.scrollText(msgWidth)
                },1000)
                return
            }
            setTimeout(()=>{
                box.scrollLeft ++
                this.scrollText(msgWidth)
            },40)
        }
    }
}
</script>

<style>
.scroll-message-icon i{
    position:relative;
    top:-1px;
}
</style>
<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.scroll-message-block{
    background-color:#fff;
    padding:10px 5px;
}
.scroll-message-icon,.scroll-message-content{
    display:inline-block;
    vertical-align:middle;
}
.scroll-message-icon{
    margin-right:5px;
}
.scroll-message-content{
    width:calc(100% - 25px);
    font-size:$middle-font-size;
    line-height:$middle-font-height;
    height:$middle-font-height;
    overflow:hidden;
    white-space:nowrap;
}
.scroll-message-content__scroll{
    display:inline-block;
    line-height:$middle-font-height;
    margin:0;
    color:$main-font-color;
}
.scroll-message-content__scroll.padding{
    padding-right:40px;
}
</style>