<template>
<div></div>
</template>
<script>
export default{
    created(){
        this.$store.dispatch('retakeToken',this.$route.query.key)
        // this.$store.dispatch('getShopInfo').then(()=>{
            if(this.$route.query.to === 'dashboard') this.$router.push({name:'Dashboard'})
                else this.$router.push({name:'ShopInit'})
        // })
    }
}
</script>