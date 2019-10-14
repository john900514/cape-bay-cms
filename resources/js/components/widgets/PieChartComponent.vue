<template>
    <div class="chart-responsive">
        <canvas id="pieChart" height="155" width="308" style="width: 308px; height: 155px;"></canvas>
    </div>
    <!-- ./chart-responsive -->
</template>


<script>
    import Chart from 'chart.js'
    export default {
        name: "PieChartComponent",
        props: ['labels', 'dataset', 'bgColor'],
        methods: {
            renderPieChart() {
                let _this = this;
                let pieChartCanvas = $('#pieChart');
                let data = {
                    datasets: [{
                        data: _this.dataset,//[700,500,400,600,300,100],
                        "backgroundColor": _this.bgColor,//["#f56954","#00a65a","#f39c12",'#00c0ef','#3c8dbc', '#d2d6de']

                    }],
                    labels: _this.labels//['Chrome', 'IE', 'FireFox', 'Safari', 'Opera', 'Navigator']
                    /*
                     misc: {
                            "type":"doughnut",
                            "data":{
                                "labels": ["Red","Blue","Yellow"],
                                "datasets":[
                                    {
                                        "label":"My First Dataset",
                                        "data":[300,50,100],
                                        "backgroundColor": ["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"]
                                    }]
                            }
                        }
                     {
                        highlight: '#f56954',

                    },
                    {

                        highlight: '#00a65a',
                        label    : 'IE'
                    },
                    {

                        highlight: '#f39c12',

                    },
                    {

                        highlight: '#00c0ef',
                        label    : 'Safari'
                    },
                    {

                        highlight: '#3c8dbc',
                        label    : 'Opera'
                    },
                    {

                        highlight: '#d2d6de',
                        label    : 'Navigator'
                    }
                     */
                };
                let options     = {
                    cutoutPercentage: 50

                };

                let myDoughnutChart = new Chart(pieChartCanvas, {
                    type: 'doughnut',
                    data: data,
                    options: options
                });

                // Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                //pieChart.Doughnut(PieData, pieOptions);
                // -----------------
            }
        },
        mounted() {
            this.renderPieChart();
            console.log('Pie Chart Loaded')
        }
    }
</script>

<style scoped>
    .chartjs-size-monitor-expand {
        display:none !important;
    }
</style>
