<template>
  <div class="upload-container">
    <el-button :style="{background:color,borderColor:color}" icon="el-icon-upload" size="mini" type="primary" @click="toSelect">
      选择图片
    </el-button>
    <el-dialog :visible.sync="dialogVisible" title="选择图片" width="510px">
      <ul v-loading="loading" class="editor-slide-images">
        <li v-for="(item,index) in images" :key="index" @click="toggleImage(item)" :class="{'is-active':selected_id.indexOf(item.id) !== -1}">
          <el-image style="width:148px;height:148px" :src="item.url"></el-image>
        </li>
      </ul>
      <el-upload
        :multiple="true"
        :file-list="fileList"
        :show-file-list="true"
        :on-remove="handleRemove"
        :on-success="handleSuccess"
        :before-upload="beforeUpload"
        :headers="{'Authorization': 'Bearer ' + $store.getters.token}"
        class="editor-slide-upload"
        :action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/image'"
        name="file"
        list-type="picture-card"
      >
        <el-button size="small" type="primary">
          点击上传
        </el-button>
      </el-upload>
      <el-pagination
        style="text-align:center"
        :current-page="query.page"
        :page-size="query.pageSize"
        :total="total"
        layout="total, prev, pager, next, jumper"
        @current-change="handleCurrentChange">
      </el-pagination>
      <div slot="footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleSubmit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { get_images } from '@/api/image'
export default {
  name: 'EditorSlideUpload',
  props: {
    color: {
      type: String,
      default: '#1890ff'
    }
  },
  data() {
    return {
      dialogVisible: false,
      listObj: {},
      fileList: [],
      loading:false,
      images:[],
      query:{
        page:1,
        pageSize:16
      },
      total:0,
      selected:[],
      selected_id:[]
    }
  },
  methods: {
    toSelect(){
      this.dialogVisible = true
      this.getImages()
    },
    getImages(){
      this.loading = true
      this.images = []
      this.selected_id = []
      this.selected = []
      this.total = 0
      get_images(this.query).then(r=>{
        this.images = r.data.body.data
        this.total = r.data.body.page.total
        this.loading = false
      }).catch(()=>{
        this.loading = false
      })
    },
    handleCurrentChange(pagination){
      this.query.page = pagination
      this.getImages()
    },
    checkAllSuccess() {
      return Object.keys(this.listObj).every(item => this.listObj[item].hasSuccess)
    },
    handleSubmit() {
      const arr = Object.keys(this.listObj).map(v => this.listObj[v])
      this.selected.map(v=>{
        arr.push(v)
      })
      if (!this.checkAllSuccess()) {
        this.$message('Please wait for all images to be uploaded successfully. If there is a network problem, please refresh the page and upload again!')
        return
      }
      this.$emit('successCBK', arr)
      this.listObj = {}
      this.fileList = []
      this.dialogVisible = false
    },
    toggleImage(item){
      let _item = {
        url:item.url,
        hasSuccess:true
      }
      const _index = this.selected_id.indexOf(item.id)
      if(_index === -1){
        this.selected.push(_item)
        this.selected_id.push(item.id)
      }else{
        this.selected.splice(_index,1)
        this.selected_id.splice(_index,1)
      }
    },
    handleSuccess(response, file) {
      const uid = file.uid
      const objKeyArr = Object.keys(this.listObj)
      for (let i = 0, len = objKeyArr.length; i < len; i++) {
        if (this.listObj[objKeyArr[i]].uid === uid) {
          this.listObj[objKeyArr[i]].url = response.body.url
          this.listObj[objKeyArr[i]].hasSuccess = true
          return
        }
      }
    },
    handleRemove(file) {
      const uid = file.uid
      const objKeyArr = Object.keys(this.listObj)
      for (let i = 0, len = objKeyArr.length; i < len; i++) {
        if (this.listObj[objKeyArr[i]].uid === uid) {
          delete this.listObj[objKeyArr[i]]
          return
        }
      }
    },
    beforeUpload(file) {
      const _self = this
      const _URL = window.URL || window.webkitURL
      const fileName = file.uid
      const isJPG = (file.type === 'image/jpeg' || file.type === 'image/png')
      const isLt2M = file.size / 1024 / 1024 < 2
      if (!isJPG) {
        this.$message.error('上传图片只能是 JPG 或 PNG 格式!')
        return isJPG
      }
      if (!isLt2M) {
        this.$message.error('上传图片大小不能超过 2MB!')
        return isLt2M
      }
      this.listObj[fileName] = {}
      return new Promise((resolve) => {
        const img = new Image()
        img.src = _URL.createObjectURL(file)
        img.onload = function() {
          _self.listObj[fileName] = { hasSuccess: false, uid: file.uid, width: this.width, height: this.height }
        }
        resolve(true)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.editor-slide-images{
  margin:0;
  padding:0;
  list-style:none;
}
.editor-slide-images li{
  display:inline-block;
  vertical-align:middle;
  border:1px dotted #eee;
  border-radius:6px;
  margin:5px 5px 5px 0;
  cursor:pointer;
}
.editor-slide-images li img{
  border-radius:6px;
}
.editor-slide-images li.is-active{
  border-color:$main-color;
}
.editor-slide-upload {
  margin-bottom: 20px;
}
  .el-upload--picture-card {
    width: 100%;
  }
</style>