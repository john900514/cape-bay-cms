<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <button type="button" @click="goBack()"><i class="fal fa-arrow-square-left"></i></button>
                        Live Tracking
                    </div>

                    <div class="card-body">
                        <div id="clientSelect">
                            <select v-model="selectedClient" @change="getAvailableClientTrackers()">
                                <option v-for="($client_name, $value) in clients" :value="$value">{{ $client_name }}</option>
                            </select>
                        </div>

                        <div v-if="selectedClient !== '0' && showTrackerView === true" id="trackerView">
                            <div class="centered-text">
                                <h1>{{ clients[selectedClient] }} Tracking Console</h1>
                                <h3>Select a Tracker to Continue</h3>
                            </div>

                            <div id="reposTable">
                                <ul id="repoList" v-for="(tracker, idx) in clientTrackers">
                                    <li><a :href="tracker['url']">{{ tracker['name'] }}</a></li>
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
        name: "dashComponent",
        props: ['clientele'],
        data() {
            return {
                clients: {},
                selectedClient: '0',
                showTrackerView: false,
                clientTrackers: []
            };
        },
        computed: {},
        methods: {
            goBack: function goBack() {
                window.history.back();
            },
            getAvailableClientTrackers: function getAvailableClientTrackers() {
                if(this.selectedClient !== '0') {
                    console.log('ajax\'ing out to obtain client trackers');

                    let _this = this;
                    $.ajax({
                        url: 'live-tracking/'+_this.selectedClient,
                        method: 'GET',
                        success: function (data) {
                            if(('success' in data) && data['success'] === true) {
                                _this.clientTrackers = data['trackers'];
                                _this.showTrackerView = true;
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
            console.log('LiveTracking DashboardComponent module running...');

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
