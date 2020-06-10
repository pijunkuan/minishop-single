<template>
	<div :id="id" :class="className" :style="{height:Height + 'px'}" />
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
        Values:Object
    },
    data() {
        return {
            chart: null,
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
            this.chart.setOption({
                tooltip:{
                    trigger: 'axis',
                    axisPointer: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    backgroundColor: 'rgba(255,255,255,1)',
                    padding: [5, 10],
                    textStyle: {
                        color: '#409EFF',
                    },
                    extraCssText: 'box-shadow: 0 0 5px rgba(0,0,0,0.3)'
                },
                grid: {
                    top: '12%',
                    bottom: '19%',
                    left: '6%',
                    right: '4%'
                },
                legend:{
                    data: ['昨日', '今日'],
                    left: 'left',
                    top: 'top'
                },
                xAxis: {
                    type: 'category',
                    data: [
                        "0:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","24:00"
                    ],
                    boundaryGap: false,
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        show:false,
                        lineStyle: {
                            color: '#646464'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 10
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        show:false,
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        show:false
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 10
                        }
                    }
                },
                series: [{
                    name: '昨日',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: this.Values.yesterday,
                    itemStyle: {
                        normal: {
                            color: 'rgba(64,158,255,0.4)'
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 2.5
                        }
                    }
                },
                {
                    name: '今日',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: this.Values.today,
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: 'rgba(64,158,255,0.2)'
                            }, {
                                offset: 1,
                                color: 'rgba(64,158,255,0.05)'
                            }], false)
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: 'rgba(64,158,255,1)'
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 2.5
                        }
                    }
                },]
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