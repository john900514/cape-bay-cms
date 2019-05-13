<template>
    <div class="container">
        <div class="row justify-content-center">
            <div>
                <div class="card">
                    <div class="card-header"><button type="button" @click="goBack()"><i class="fal fa-arrow-square-left"></i></button> <b>{{ reportData['name'] }}</b> Report</div>

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

                            <div class="some-other-feature">
                                <div id="pdfView">
                                    <button type="button" @click="exportPDF()"><i class="fas fa-file-pdf"></i></button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    let pdfMake = require('pdfmake/build/pdfmake.js');
    let pdfFonts = require('pdfmake/build/vfs_fonts.js');
    pdfMake.vfs = pdfFonts.pdfMake.vfs;

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
            },
            goBack: function goBack() {
                window.history.back();
            },
            exportPDF: function exportPDF() {
                let docDefinition = {
                    content: [
                        {
                            table : {
                                headerRows: 1,
                                body: []
                            }
                        }
                    ]
                };

                let field = [];
                for(let derpy = 0; derpy < this.reportData['fields'].length; derpy++) {
                    field.push(this.reportData['fields'][derpy]['key'])
                }

                docDefinition.content[0].table.body.push(field);
                for(let i=0; i < this.reportData['results'].length; i++) {
                    docDefinition.content[0].table.body.push(Object.values(this.reportData['results'][i]));
                }
                pdfMake.createPdf(docDefinition).download('PO.pdf');
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
