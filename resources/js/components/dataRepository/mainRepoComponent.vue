<template>
    <div class="box-body">
        <div id="clientSelect">
            <select v-model="selectedClient" @change="getAvailableClientDataStores()">
                <option v-for="($client_name, $value) in clients" :value="$value">{{ $client_name }}</option>
            </select>
        </div>

        <div v-if="selectedClient !== '0'">
            <div class="centered-text">
                <h1>Now Managing {{ clients[selectedClient] }} Data</h1>
                <h3>Select a Data Store to Continue</h3>
            </div>

            <div id="reposTable" v-if="showDataStores === true">
                <ul id="repoList" v-for="(report, idx) in dataStores">
                    <li><a :href="report['url']">{{ report['name'] }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "mainRepoComponent",
        props: ['clientele'],
        data() {
            return {
                clients: {},
                selectedClient: '0',
                dataStores: {},
                showDataStores: false
            };
        },
        computed: {},
        methods: {
            getAvailableClientDataStores: function getAvailableClientDataStore() {
                if(this.selectedClient !== '0') {
                    console.log('ajax\'ing out to obtain client data stores');

                    let _this = this;
                    $.ajax({
                        url: 'repo/'+_this.selectedClient,
                        method: 'GET',
                        success: function (data) {
                            if(('success' in data) && data['success'] === true) {
                                _this.dataStores = data.data_stores;
                                _this.showDataStores = true;
                            }
                            else {
                                if('reason' in data) {
                                    alert('Failed. Response returned - ' + data['reason'])
                                } else {
                                    alert('Unknown response from the server. try again.')
                                }
                            }
                            console.log(data)
                        },
                        error: function (e) {
                            alert('oops');
                            console.log(e);
                        }
                    });
                }
                else {
                    this.showDataStores = false;
                }
            }
        },
        mounted: function () {
            console.log('mainRepoComponent module running...');

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
