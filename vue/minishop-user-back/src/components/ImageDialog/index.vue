<template>
<el-dialog :visible="Visible" title="选择图片" :before-close="close" width="590px">
    <div style="margin-bottom:20px">
        <el-upload class="avatar-uploader upload-btn"
            :headers="{'Authorization': 'Bearer ' + $store.getters.token}"
            :action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/image'"
            name="file"
            :before-upload="beforeUpload"
            :show-file-list="false"
            :on-success="successUpload">
            <span class="upload-image-bar">上传图片</span>
        </el-upload>
    </div>
    <div>
        <div v-if="loading" class="loading-placeholder">加载中...</div>
        <div v-if="!loading && images.length === 0" class="loading-placeholder">暂无图片</div>
        <div v-if="!loading && images.length !== 0" style="position:relative">
            <el-checkbox v-for="(image,index) in images" :key="index" :value="selectId.indexOf(image.id) !== -1" @change="selectImage(image)" class="image-checkbox">
                <el-image :src="image.url" style="width:150px;height:150px;" fit="scale-down"></el-image>
            </el-checkbox>
        </div>
        <el-pagination
            style="text-align:center;margin-top:10px"
            :current-page="fetchQuery.page"
            :page-size="fetchQuery.pageSize"
            :total="total"
            @current-change="handleCurrent"
            layout="total, prev, pager, next, jumper">
        </el-pagination>
    </div>
    <div slot="footer" style="text-align:center">
        <el-button @click="close">关闭</el-button>
        <el-button type="primary" @click="confirm">确定</el-button>
    </div>
</el-dialog>
</template>

<script>
import { get_images } from '@//api/image'
export default{
    props:{
        Visible:Boolean,
        Type:String
    },
    data(){
        return{
            images:[],
            loading:false,
            fetchQuery:{
                page:1,
                pageSize:9
            },
            total:0,
            selectId:[],
            selectItem:[]
        }
    },
    methods:{
        getImages(){
            this.loading = true
            get_images(this.fetchQuery).then(r=>{
                this.images = r.data.body.data
                this.total = r.data.body.page.total
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        handleCurrent(pagination){
            this.fetchQuery.page = pagination
            this.getImages()
        },
        beforeUpload(file){
            const isJPGPNG = file.type === 'image/jpeg' || file.type === 'image/png'
            const isLt2M = file.size / 1024 / 1024 < 2

            if (!isJPGPNG) {
                this.$message.error('上传图片只能是 JPG 或 PNG 格式!')
            }
            if (!isLt2M) {
                this.$message.error('上传图片大小不能超过 2MB!')
            }
            return isJPGPNG && isLt2M
        },
        successUpload(){
            this.$message.success('上传成功')
            this.getImages()
        },
        selectImage(image){
            if(this.Type === 'mono'){
                this.selectMono(image)
                return
            }
            image.isCheck = !image.isCheck
            let _index = this.selectId.indexOf(image.id)
            if(image.isCheck && _index === -1){
                this.selectId.push(image.id)
                this.selectItem.push(image)
            }
            if(!image.isCheck && _index !== -1){
                this.selectId.splice(_index,1)
                this.selectItem.splice(_index,1)
            }
        },
        selectMono(image){
            image.isCheck = !image.isCheck
            this.images.map(v=>{
                v.isCheck = false
            })
            this.selectId = []
            this.selectId.push(image.id)
            this.selectItem = []
            this.selectItem.push(image)
        },
        close(){
            this.images = []
            this.fetchQuery.page = 1
            this.$emit('close')
        },
        confirm(){
            if(this.selectId.length === 0){
                this.$message.warning('请先选择图片')
                return
            }
            this.images = []
            this.fetchQuery.page = 1
            this.$emit('confirm',this.selectItem)
        }
    },
    watch:{
        Visible:{
            deep:true,
            handler:function(){
                if(this.Visible){
                    this.selectItem = []
                    this.selectId = []
                    this.getImages()
                }
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.upload-image-bar{
    padding:6px 20px;
    background-color:$main-color;
    color:#fff;
    border-radius:5px;
}
.image-checkbox{
    margin-bottom:20px;
    border:1px solid $line-color;
    height:150px;
}
</style>
<style>
.image-checkbox .el-checkbox__input{
    position:absolute;
    margin-top:5px;
    margin-left:5px;
}
.image-checkbox .el-checkbox__label{
    padding:0;
}
</style>