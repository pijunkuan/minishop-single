<template>
<div :id="id" :class="className" :style="{height:Height + 'px'}" style="width:100%;" />
</template>

<script>
import echarts from 'echarts'
export default {
    props: {
        className: {
            type: String,
            default: 'chart'
        },
        id: {
            type: String,
            default: 'chart'
        },
        Show:Boolean,
        Height:Number,
        Value:{
            type:Number,
            default:0
        }
    },
    data() {
        return {
            chart: null,
            waterData:[0.2,0.2,0.2],
            serieName:''
        }
    },
    mounted() {
        if(this.Show){
            this.initChart()
        }
    },
    methods: {
        initChart() {
            this.chart = echarts.init(this.$el)
            this.setOptions()
        },
        setOptions(){
            var placeHolderStyle = {
                    normal: {
                        label: {
                            show: false
                        },
                        labelLine: {
                            show: false
                        },
                        color: "rgba(0,0,0,0)",
                        borderWidth: 0
                    },
                    emphasis: {
                        color: "rgba(0,0,0,0)",
                        borderWidth: 0
                    }
            }

            var dataStyle = {
                    normal: {
                        formatter: '{c}%',
                        position: 'center',
                        show: true,
                        textStyle: {
                            fontSize: '20',
                            fontWeight: 'normal',
                            color: '#34374E'
                        }
                    }
            }

            this.chart.setOption({
                title: [{
                    // text: (this.value * 100).toFixed(2) + '{a|%}',
                    text: "已使用",
                    left:"48%",
                    top:"70%",
                    textAlign:'center',
                    textStyle: {
                        fontSize: 15,
                        fontFamily: 'Microsoft Yahei',
                        fontWeight: 'normal',
                        color: '#409EFF',
                        textAlign:'center',
                    },
                }],
                series: [
                    {
                        type: 'pie',
                        hoverAnimation: false, //鼠标经过的特效
                        radius: ['100%', '80%'],
                        startAngle: 225,
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        label: {
                            normal: {
                                position: 'center'
                            }
                        },
                        data: [{
                                value: 100,
                                itemStyle: {
                                    normal: {
                                        color: '#E1E8EE'
                                    }
                                },
                            }, {
                                value: 35,
                                itemStyle: placeHolderStyle,
                            },

                        ]
                    },
                    //上层环形配置
                    {
                        type: 'pie',
                        hoverAnimation: false, //鼠标经过的特效
                        radius: ['100%', '80%'],
                        startAngle: 225,
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        label: {
                            normal: {
                                position: 'center'
                            }
                        },
                        data: [{
                                value: this.Value,
                                itemStyle: {
                                    normal: {
                                        color: '#E6A23C'
                                    }
                                },
                                label: dataStyle,
                            }, {
                                value: 35,
                                itemStyle: placeHolderStyle,
                            },

                        ]
                    },
                ]
            })
        }
    },
    watch:{
        Show:{
            deep:true,
            immediate:true,
            handler:function(){
                if(this.Show) this.initChart()
            }
        }
    }
}
</script>
