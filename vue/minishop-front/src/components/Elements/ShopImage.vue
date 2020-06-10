<template>
    <div 
        class="shop-image"
        :style="{ height: (height ? height : width) + 'px', width: width + 'px', lineHeight: (height ? height : width) + 'px' }"
        :class="[
            type + '-image',
            {
                'is-circled':circled,
                'is-rounded':rounded
            }
        ]">
        <div v-if="loading" class="shop-image__placeholder" :style="{lineHeight:(height ? height : width) + 'px',height: (height ? height : width) + 'px'}">
            <slot name="placeholder"></slot>
            <template v-if="!$slots.placeholder"><span>加载中...</span></template>
        </div>
        <div v-else-if="error" class="shop-image__error" :style="{lineHeight:(height ? height : width) + 'px',height: (height ? height : width) + 'px'}">
            <slot name="error"></slot>
            <template v-if="!$slots.error"><span>加载失败</span></template>
        </div>
        <img
            v-else
            v-on="$listeners"
            class="shop-image__inner"
            :src="src"
            :style="{
                left: (imgWidth > width ? - (imgWidth - width) / 2 : 0) + 'px'
            }" />
    </div>
</template>

<script>
    export default{
        name:"ShopImage",
        props:{
            src:String,
            height:{
                type: Number,
                default:0
            },
            width:{
                type:Number
            },
            type:{
                type:String,
                default:"default"
            },
            rounded:{
                type:Boolean,
                default:false
            },
            circled:{
                type:Boolean,
                default:false
            }
        },
        data(){
            return {
                loading:true,
                error:false,
                imgWidth:0
            }
        },
        watch:{
            src:{
                immediate:true,
                handler:function(){
                    this.loadImage()
                }
            }
        },
        methods:{
            loadImage(){
                if(this.$isServer) return
                this.loading = true
                this.error = false

                const img = new Image()
                img.onload = () => this.handleLoad(img)
                img.onerror = this.handleError.bind(this)
                img.src = this.src
            },
            handleLoad(img) {
                this.imgWidth = img.width
                this.loading = false
            },
            handleError(e) {
                this.loading = false
                this.error = true
                this.$emit('error', e)
            }
        }
    }
</script>

<style lang="scss" scoped>
@import "@/assets/style/base.scss";
.shop-image{
    text-align: center;
    overflow:hidden;
}

.shop-image.fit-image img{
    width:100%;
    height:100%;
    object-fit:scale-down;
}
.shop-image.stretch-image img{
    width:100%;
    height:100%;
}
.shop-image.cover-image img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.shop-image.center-image img{
    position:relative;
}

.shop-image__placeholder,.shop-image__error{
    color:$other-font-color;
    font-size:$middle-font-size;
    background-color:#fff;
}

.shop-image.is-circled img{
    border-radius:50%;
}
.shop-image.is-rounded img{
    border-radius:5px;
}

.shop-image.is-circled .shop-image__placeholder,.shop-image.is-circled .shop-image__error{
    border-radius:50%;
}
</style>