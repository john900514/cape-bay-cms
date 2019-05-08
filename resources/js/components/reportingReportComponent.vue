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
                                <table v-if="('results' in reportData) && ('0' in reportData['results'])">
                                    <thead>
                                    <th v-for="(colVal, colName) in reportData['results'][0]" v-if="validColumn(colName) === true">{{ colName }}</th>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(rowski, idx) in reportData['results']">
                                        <td v-for="(v, c) in rowski" v-if="validColumn(c) === true">{{ c === 'id' ? parseInt(idx + 1) : v }}</td>
                                    </tr>
                                    </tbody>
                                </table>
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
                reportData: {}
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
