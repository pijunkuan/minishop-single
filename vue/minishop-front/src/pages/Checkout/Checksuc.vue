<template>
<div>
    <div class="checksuc-header"></div>
    <div class="checksuc-content">
        <div class="checksuc-icon"><i class="iconfont iconchenggong"></i></div>
        <div class="checksuc-tip">支付成功！</div>
        <div class="checksuc-button">
            <div><span @click="toOrder">查看订单</span></div>
            <div><span @click="toProducts">逛逛其他的吧</span></div>
        </div>
    </div>
</div>
</template>

<script>
export default{
    methods:{
        toOrder(){
            this.$router.push({name:'UserOrderDetail', query:{ no: this.$route.query.no }})
        },
        toProducts(){
            this.$router.push({name:'Types'})
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.checksuc-content{
    width:220px;
    padding:20px;
    position:absolute;
    left:50%;
    margin-left:-130px;
    background-color:#fff;
    height:230px;
    top:50%;
    margin-top:-150px;
    border-radius:8px;
    border:1px solid $line-color;
    box-shadow:0 0 5px 3px rgba(0,0,0,0.05);
    text-align:center;
}
.checksuc-icon i{
    font-size:50px;
    color:$success-color;
}
.checksuc-tip{
    margin:15px 0 40px;
    font-size:$normal-font-size;
    color:$success-color;
}
.checksuc-button{
    font-size:$middle-font-size;
    color:$sub-font-color;
}
.checksuc-button div{
    margin:25px 0;
}
.checksuc-button span{
    padding:5px 10px;
    border:1px solid $line-color;
    border-radius:25px;
}
.checksuc-header{
    height:50px;
    @include base-background(1);
}
</style>