<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Reports</div>

                    <div class="card-body">
                        <div id="clientSelect">
                            <select v-model="selectedClient" @change="getAvailableClientReports()">
                                <option v-for="($client_name, $value) in clients" :value="$value">{{ $client_name }}</option>
                            </select>
                        </div>

                        <div v-if="selectedClient !== '0' && showReportView === true" id="reportView">
                            <div class="centered-text">
                                <h1>{{ clients[selectedClient] }} Reporting Console</h1>
                                <h3>Select a Report to Continue</h3>
                            </div>

                            <div id="reposTable">
                                <ul id="repoList" v-for="(report, idx) in clientReports">
                                    <li><a :href="report['url']">{{ report['name'] }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "reportingViewComponent",
        props: ['clientele'],
        data() {
            return {
                clients: {},
                selectedClient: '0',
                showReportView: false,
                clientReports: []
            };
        },
        computed: {},
        methods: {
            getAvailableClientReports: function getAvailableClientReports() {
                if(this.selectedClient !== '0') {
                    console.log('ajax\'ing out to obtain client reports');

                    let _this = this;
                    $.ajax({
                        url: 'reports/'+_this.selectedClient,
                        method: 'GET',
                        success: function (data) {
                            if(('success' in data) && data['success'] === true) {
                                _this.clientReports = data['reports'];
                                _this.showReportView = true;
                            }
                            else {
                                if('reason' in data) {
                                    alert('Failed. Response returned - ' + data['reason'])
                                } else {
                                    alert('Unknown response from the server. try again.')
                                }
                                _this.selectedClient = '0'
                            }
                            console.log(data)
                        },
                        error: function (e) {
                            alert('oops');
                            console.log(e);
                            _this.selectedClient = '0'
                        }
                    });
                }
            }
        },
        mounted: function () {
            console.log('ReportingViewComponent module running...');

            this.clients = this.clientele;
        }
    }
</script>

<style scoped>
    #clientSelect {
        text-align: center;
    }

    .centered-text {
        text-align:center;
    }
</style>
