<template>
<div>
    <div class="certify-body">
        <el-button size="small" @click="$router.go(-1)">返回</el-button>
        <div class="certify-desc">提交网站认证后，可使用更多收款方式、短信等功能，并自动开启代收服务。</div>
        <div class="certify-tip">个人认证审核周期为1-3个工作日，企业认证审核周期为3-7个工作日，如审核不通过，需重新提交认证申请。</div>
        <div v-if="certify.status && certify.status === 'failed'" class="certify-status-tip">
            审核不通过，请修改认证资料。
        </div>
        <div v-if="certify.status && certify.status === 'pending' && !isEdit" class="certify-status-tip">
            审核已提交，请等待审核结果。
        </div>
        <div v-if="!loading && !certify.status" class="certify-status-tip">
            暂未实名认证，请填写下列信息进行认证。
        </div>
        <div v-if="certify.name" style="margin-bottom:20px">
            <el-button size="mini" type="primary" @click="isEdit = true">修改</el-button>
            <el-button size="mini" v-if="isEdit" @click="isEdit = false">取消</el-button>
        </div>
        <div class="certify-form" v-if="!isEdit">
            <div>
                <div class="form-title">认证类型</div>
                <div class="form-content">{{ certify.type_value }}</div>
            </div>
            <div v-if="certify.type === 'person'">
                <div class="form-title">真实姓名</div>
                <div class="form-content">{{ certify.name }}</div>
            </div>
            <div>
                <div class="form-title">手机号码</div>
                <div class="form-content">{{ certify.mobile }}</div>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">座机号码</div>
                <div class="form-content">{{ certify.telephone }}</div>
            </div>
            <div>
                <div class="form-title">{{ certify.type === 'company' ? '企业' : '身份证' }}地址</div>
                <div class="form-content">{{ certify.address }}</div>
            </div>
            <div v-if="certify.type === 'person'">
                <div class="form-title">身份证号码</div>
                <div class="form-content">{{ certify.id_no }}</div>
            </div>
            <div v-if="certify.type === 'person'">
                <div class="form-title">手持证件</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.images[0]">
                    <div slot="placeholder" class="image-error"><i class="iconfont icontupian1"></i></div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">企业名称</div>
                <div class="form-content">{{ certify.company_name }}</div>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">统一社会信用代码/企业注册号</div>
                <div class="form-content">{{ certify.id_no }}</div>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">企业法人姓名</div>
                <div class="form-content">{{ certify.name }}</div>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">营业执照</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.images[0]">
                    <div slot="placeholder" class="image-error"><i class="iconfont icontupian1"></i></div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">法人身份证（正面）</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.images[1]">
                    <div slot="placeholder" class="image-error"><i class="iconfont icontupian1"></i></div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">法人身份证（反面）</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.images[2]">
                    <div slot="placeholder" class="image-error"><i class="iconfont icontupian1"></i></div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
            </div>
        </div>
        <div class="certify-form" v-if="isEdit">
            <div>
                <div class="form-title">认证类型</div>
                <el-radio border v-model="certify.type" label="person" @change="changeType('person')">个人认证</el-radio>
                <el-radio border v-model="certify.type" label="company" @change="changeType('company')">企业认证</el-radio>
            </div>
            <div v-if="certify.type === 'person'">
                <div class="form-title">真实姓名</div>
                <el-input v-model="certify.name"></el-input>
            </div>
            <div>
                <div class="form-title">手机号码</div>
                <el-input v-model="certify.mobile"></el-input>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">座机号码</div>
                <el-input v-model="certify.telephone"></el-input>
            </div>
            <div>
                <div class="form-title">{{ certify.type === 'company' ? '企业' : '身份证' }}地址</div>
                <el-input v-model="certify.address"></el-input>
            </div>
            <div v-if="certify.type === 'person'">
                <div class="form-title">身份证号码</div>
                <el-input v-model="certify.id_no"></el-input>
            </div>
            <div v-if="certify.type === 'person'">
                <div class="form-title">手持证件</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.image_0">
                    <div slot="placeholder" class="image-error">加载中...</div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
                <el-upload
                    name="file"
                    :headers="{'Authorization': 'Bearer ' + $store.getters.token}"
                    :action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/shop/authenticate/image'"
                    :before-upload="beforeUpload"
                    :show-file-list="false"
                    :on-success="successUploadPic">
                    <el-button type="text">上传照片</el-button>
                </el-upload>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">企业名称</div>
                <el-input v-model="certify.company_name"></el-input>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">统一社会信用代码/企业注册号</div>
                <el-input v-model="certify.id_no"></el-input>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">企业法人姓名</div>
                <el-input v-model="certify.name"></el-input>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">营业执照</div>
                {{ certify.images[0] }}
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.image_0">
                    <div slot="placeholder" class="image-error">加载中...</div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
                <el-upload
                    name="file"
                    :headers="{'Authorization': 'Bearer ' + $store.getters.token}"
                    :action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/shop/authenticate/image'"
                    :before-upload="beforeUpload"
                    :show-file-list="false"
                    :on-success="successUploadPass">
                    <el-button type="text">上传照片</el-button>
                </el-upload>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">法人身份证（正面）</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.image_1">
                    <div slot="placeholder" class="image-error">加载中...</div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
                <el-upload
                    name="file"
                    :headers="{'Authorization': 'Bearer ' + $store.getters.token}"
                    :action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/shop/authenticate/image'"
                    :before-upload="beforeUpload"
                    :show-file-list="false"
                    :on-success="successUploadCover">
                    <el-button type="text">上传照片</el-button>
                </el-upload>
            </div>
            <div v-if="certify.type === 'company'">
                <div class="form-title">法人身份证（反面）</div>
                <el-image style="width:300px;height:200px;border-radius:5px;border:1px solid #eee" fit="scale-down" :src="certify.image_2">
                    <div slot="placeholder" class="image-error">加载中...</div>
                    <div slot="error" class="image-error"><i class="iconfont icontupian1"></i></div>
                </el-image>
                <el-upload
                    name="file"
                    :headers="{'Authorization': 'Bearer ' + $store.getters.token}"
                    :action="'//' + $store.getters.shop_url + '.' + $store.getters.shop_host + '/api/center/shop/authenticate/image'"
                    :before-upload="beforeUpload"
                    :show-file-list="false"
                    :on-success="successUploadBack">
                    <el-button type="text">上传照片</el-button>
                </el-upload>
            </div>
            <div>
                <el-checkbox v-model="contrast_confirm" style="margin-bottom:15px">
                    我已阅读并同意
                </el-checkbox>
                <span @click="contrastShow = true" class="contrast-btn">代收服务协议</span>
            </div>
        </div>
        <div style="margin-bottom:20px" v-if="isEdit">
            <el-button type="primary" @click="confirm" :loading="btnLoading">提交认证</el-button>
        </div>
    </div>
    <el-dialog :visible="contrastShow" title="代收服务协议" center @close="contrastShow = false">
        <div>{{ contrast }}</div>
        <div slot="footer">
            <el-button @click="contrastShow = false" type="primary" size="small">关闭</el-button>
        </div>
    </el-dialog>
</div>
</template>

<script>
import { get_certify, update_certify, create_certify } from '@/api/certify'
import { get_contrast } from '@/api/backservice'
export default{
    data(){
        return{
            loading:false,
            certify:{
                type:'person',
                address:'',
                id_no:'',
                mobile:'',
                name:'',
                company_name:'',
                status:'',
                telephone:'',
                images:[]
            },
            ori_type:'',
            ori_info:{
                type:'',
                address:'',
                id_no:'',
                mobile:'',
                name:'',
                company_name:'',
                status:'',
                telephone:'',
                images:[]
            },
            contrast_confirm:true,
            isEdit:false,
            btnLoading:false,
            contrastShow:false,
            contrast:''
        }
    },
    created(){
        this.getInfo()
    },
    methods:{
        getInfo(){
            this.loading = true
            get_certify().then(r=>{
                get_contrast().then(res=>{
                    this.contrast = res.data
                })
                if(r.data.body){
                    r.data.body['image_0'] = ''
                    r.data.body['image_1'] = ''
                    r.data.body['image_2'] = ''
                    if(r.data.body.images.length !== 0){
                        r.data.body.images.map((v,n)=>{
                            r.data.body['image_' + n] = v
                        })
                    }
                    this.ori_type = r.data.body.type
                    this.certify = r.data.body
                    this.ori_info = r.data.body
                    if(r.data.body.status === 'failed') this.isEdit = true
                        else this.isEdit = false
                }else{
                    this.isEdit = true
                    this.certify['image_0'] = ''
                    this.certify['image_1'] = ''
                    this.certify['image_2'] = ''
                    this.ori_type['image_0'] = ''
                    this.ori_type['image_1'] = ''
                    this.ori_type['image_2'] = ''
                }
                this.loading = false
            }).catch(()=>{
                this.loading = false
            })
        },
        changeType(type){
            if(type !== this.ori_type){
                this.certify = {
                    type:type,
                    address:'',
                    id_no:'',
                    mobile:'',
                    name:'',
                    company_name:'',
                    status:'',
                    telephone:'',
                    images:[],
                }
                if(type === 'company') this.certify.images = ['','','']
                    else this.certify.images = ['']
            }else{
                this.certify.type = type
                this.certify.address = this.ori_info.address
                this.certify.id_no = this.ori_info.id_no
                this.certify.mobile = this.ori_info.mobile
                this.certify.name = this.ori_info.name
                this.certify.company_name = this.ori_info.company_name
                this.certify.status = this.ori_info.status
                this.certify.telephone = this.ori_info.telephone
                this.certify.images = this.ori_info.images
                this.$set(this.certify,'image_0',this.ori_info.image_0)
                this.$set(this.certify,'image_1',this.ori_info.image_1)
                this.$set(this.certify,'image_2',this.ori_info.image_2)
            }
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
        successUploadPic(file){
            this.certify.images[0] = file.body
            this.certify.image_0 = file.body
            this.ori_info.images[0] = file.body
            this.ori_info.image_0 = file.body
        },
        successUploadPass(file){
            this.certify.images[0] = file.body
            this.certify.image_0 = file.body
            this.ori_info.images[0] = file.body
            this.ori_info.image_0 = file.body
        },
        successUploadCover(file){
            this.certify.images[1] = file.body
            this.certify.image_1 = file.body
            this.ori_info.images[1] = file.body
            this.ori_info.image_1 = file.body
        },
        successUploadBack(file){
            this.certify.images[2] = file.body
            this.certify.image_2 = file.body
            this.ori_info.images[2] = file.body
            this.ori_info.image_2 = file.body
        },
        confirm(){
            if(!this.contrast_confirm){
                this.$message.warning('请阅读并同意')
                return
            }
            this.btnLoading = true
            if(!this.certify.status) this.createCertify()
                else this.updateCertify()
        },
        createCertify(){
            create_certify(this.certify).then(()=>{
                this.btnLoading = false
                this.isEdit = false
                this.$emit('认证申请已成功提交，请等待审核结果')
                this.getInfo()
            }).catch(()=>{
                this.btnLoading = false
            })
        },
        updateCertify(){
            update_certify(this.certify).then(()=>{
                this.btnLoading = false
                this.isEdit = false
                this.$emit('认证申请已修改，请等待审核结果')
                this.getInfo()
            }).catch(()=>{
                this.btnLoading = false
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/style/base.scss';
.certify-body{
    padding:10px;
    background-color:#fff;
    border-radius:5px;
}
.certify-desc{
    margin:25px 0 10px;
    color:$main-font-color;
    font-size:15px;
}
.certify-tip{
    color:$other-font-color * 0.95;
    margin-bottom:20px;
    padding-bottom:25px;
    border-bottom:1px solid $line-color * 0.95;
}

.certify-status-tip{
    color:$error-color;
    margin:10px 0 20px;
}

.certify-form{
    padding:10px;
    background-color:$light-back-sub;
    border-radius:5px;
    border:1px solid $line-color * 0.95;
    margin-bottom:20px;
}
.certify-form>div{
    margin:10px 0 20px;
}
.certify-form .form-content{
    color:$main-font-color;
    font-weight:500;
}
.certify-form .form-title{
    margin-bottom:5px;
    color:$sub-font-color;
}
.image-error{
    line-height:200px;
    text-align:center;
    background-color:#fff;
}
.image-error i{
    font-size:40px;
    color:$line-color;
}

.contrast-btn{
    color:$success-color;
    font-weight:500;
    cursor:pointer;
    margin-left:5px;
}
.contrast-btn:hover{
    text-decoration:underline;
}
</style>
<style>
.certify-form label{
    background-color:#fff;
}
</style>