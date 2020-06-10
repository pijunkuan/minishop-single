<template>
    <el-dialog :visible="Visible" title="选择地区" :before-close="close" width="600px">
        <div v-for="(place,index) in places" :key="index" class="place-list">
            <div>
                <el-checkbox v-model="selected_group" :label="place.group" @change="changeGroup(place,$event)"></el-checkbox>
            </div>
            <div>
                <el-checkbox v-model="selected_area" v-for="(item,index) in place.area" :key="'a'+index" :label="item" @change="changeArea(place,$event)"></el-checkbox>
            </div>
        </div>
        <div slot="footer" style="text-align:center">
            <el-button @click="close">关闭</el-button>
            <el-button type="primary" @click="confirm">确定</el-button>
        </div>
    </el-dialog>
</template>

<script>
export default{
    props:{
        Visible:Boolean,
        selected:Array
    },
    data(){
        return{
            places:[
                {group:'华东', area:["上海市","江苏省","浙江省","安徽省","江西省"]},
                {group:'华北', area:["北京市","天津市","河北省","山西省","山东省","内蒙古自治区"]},
                {group:'华中', area:["河南省","湖北省","湖南省"]},
                {group:'华南', area:["广东省","广西壮族自治区","福建省","海南省"]},
                {group:'东北', area:["辽宁省","吉林省","黑龙江省"]},
                {group:'西北', area:["陕西省","甘肃省","青海省","宁夏回族自治区","新疆维吾尔自治区"]},
                {group:'西南', area:["重庆市","四川省","贵州省","云南省","西藏自治区"]},
                {group:'港澳台', area:["香港特别行政区","澳门特别行政区","台湾"]},
                {group:'海外', area:["海外"]},
            ],
            selected_group:[],
            selected_area:[]
        }
    },
    methods:{
        close(){
            this.selected_group = []
            this.selected_area = []
            this.$emit('close')
        },
        confirm(){
            this.$emit('confirm',this.selected_area)
        },
        changeGroup(group,val){
            if(val){
                group.area.map(v=>{
                    this.selected_area.push(v)
                })
            }else{
                group.area.map(v=>{
                    this.selected_area.splice(this.selected_area.indexOf(v),1)
                })
            }
        },
        changeArea(group,val){
            if(!val){
                if(this.selected_group.indexOf(group.group) !== -1) this.selected_group.splice(this.selected_group.indexOf(group.group),1)
            }else{
                if(group.area.filter((val)=>{ return this.selected_area.indexOf(val) === -1 }).length === 0) this.selected_group.push(group.group)
            }
        }
    },
    watch:{
        Visible:{
            deep:true,
            handler:function(){
                if(this.Visible){
                    this.selected_group = []
                    this.selected_area = []
                    this.places.map(v=>{
                        if(v.area.filter((val)=>{ return this.selected.indexOf(val) === -1}).length === 0) this.selected_group.push(v.group)
                    })
                    this.selected.map(v=>{
                        this.selected_area.push(v)
                    })
                }
            }
        }
    }
}
</script>

<style scoped>
.place-list{
    margin-bottom:10px;
}
.place-list>div{
    display:inline-block;
    vertical-align:top;
}
.place-list>div:first-child{
    width:80px;
}
.place-list>div:last-child{
    width:calc(100% - 80px);
}
.place-list>div:last-child .el-checkbox{
    width:120px;
    margin-bottom:5px;
}
</style>