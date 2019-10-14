<template>
    <div class="box box-primary" v-show="hasData">
        <div class="box-header with-border">
            <h3 class="box-title">{{chartName}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
                    <pie-chart v-if="hasData === true"
                        :labels="chartKeys"
                        :dataset="chartData"
                        :bg-color="bgColors"
                    ></pie-chart>
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                        <li v-if="0 in chartKeys"><i class="fa fa-circle-o text-red"></i> {{chartKeys[0]}}</li>
                        <li v-if="1 in chartKeys"><i class="fa fa-circle-o text-green"></i> {{chartKeys[1]}}</li>
                        <li v-if="2 in chartKeys"><i class="fa fa-circle-o text-yellow"></i> {{chartKeys[2]}}</li>
                        <li v-if="3 in chartKeys"><i class="fa fa-circle-o text-aqua"></i> {{chartKeys[3]}}</li>
                        <li v-if="4 in chartKeys"><i class="fa fa-circle-o text-light-blue"></i> {{chartKeys[4]}}</li>
                        <li v-if="5 in chartKeys"><i class="fa fa-circle-o text-gray"></i> {{chartKeys[5]}}</li>
                        <li v-if="6 in chartKeys"><i class="fa fa-circle-o text-blue"></i> {{chartKeys[6]}}</li>
                        <li v-if="7 in chartKeys"><i class="fa fa-circle-o text-purple"></i> {{chartKeys[7]}}</li>
                    </ul>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding" v-if="hasOtherStats">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="#">United States of America
                    <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                </li>
                <li><a href="#">China
                    <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> 0%</span></a></li>
            </ul>
        </div>
        <!-- /.footer -->
    </div>
</template>

<script>
    export default {
        name: "PieChartWidgetComponent",
        props: ['client'],
        data() {
            return {
                hasData: false,
                chartName: '',
                chartKeys: [],
                bgColors: [],
                chartData: [],
                hasOtherStats: false
            };
        },
        methods: {
            getPieChartData() {
                let _this = this;
                axios
                    .get('/users/dashboard/widgets/pie-chart/'+this.client)
                    .then(response => {
                        let data = response.data;
                        console.log('Anchor Response - ', data);

                        if('success' in data && data['success'] === true) {
                            // @todo - parse through the data and set the vars
                            _this.chartName = data.data.name;
                            _this.chartKeys = data.data.labels;
                            _this.chartData = data.data.dataset;
                            _this.bgColors = data.data.backgroundColor;
                            _this.hasData = true;
                        }
                        else {
                            if('reason' in data) {
                                console.log(data['reason']);
                            }
                            else {
                                console.log('Unknown error getting info box grid');
                            }
                        }

                        _this.loading = false;
                    })
            }
        },
        mounted() {
            this.getPieChartData();
            console.log('Pie Chart Widget Mounted')
        }
    }
</script>

<style scoped>

</style>
