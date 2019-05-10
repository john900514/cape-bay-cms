<template>
    <div class="container">
        <div class="row justify-content-center">
            <div>
                <div class="card">
                    <div class="card-header"><b>{{ reportData['name'] }}</b> Report</div>

                    <div class="card-body">
                        <div id="cardBodyContainer">
                            <div class="some-other-feature"></div>

                            <div id="reportTableWrapper">
                                <b-table
                                    v-if="('results' in reportData) && ('0' in reportData['results'])"
                                    striped
                                    hover
                                    :items="reportData['results']"
                                    :fields="reportData['fields']">
                                </b-table>
                            </div>

                            <div class="some-other-feature"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "reportingReportComponent",
        props: ['resultdata'],
        data() {
            return {
                reportData: {},
                fields: []
            };
        },
        computed: {},
        methods: {
            validColumn: function validColumn(col) {
                let result

                switch(col)
                {
                    case 'plan_info':
                    case 'created_at':
                    case 'updated_at':
                    case 'deleted_at':
                    case 'uuid':
                        result = false;
                        break;

                    default:
                        result = true;
                }
                return result;
            }
        },
        mounted: function () {
            console.log('ReportingReportComponent module running...');

            this.reportData = this.resultdata;
        }
    }
</script>

<style scoped>
    #reportTableWrapper {
        max-height: 35em;
        overflow-y: scroll;
    }
</style>
