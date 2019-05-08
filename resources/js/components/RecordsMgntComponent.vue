<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Records Management</div>

                    <div class="card-body">
                        <div id="clientSelect">
                            <select v-model="selectedClient" @change="getRecordRepos()">
                                <option v-for="($client_name, $value) in clients" :value="$value">{{ $client_name }}</option>
                            </select>
                        </div>

                        <div v-if="selectedClient !== '0' && showRepoView === true" id="repoView">
                            <div class="centered-text">
                                <h1>{{ clients[selectedClient] }} Records Repo</h1>
                                <h3>Select a Records Repository to Continue</h3>
                            </div>

                            <div id="reposTable">
                                <ul id="repoList" v-for="(repo, idx) in recordsRepos">
                                    <li><a :href="repo['url']">{{ repo['name'] }}</a></li>
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
        name: "RecordsMgntComponent",
        props: ['clientele'],
        data() {
            return {
                clients: {},
                recordsRepos: [],
                selectedClient: '0',
                showRepoView: false
            };
        },
        computed: {},
        methods: {
            getRecordRepos: function getRecordRepos() {
                this.showRepoView = false;

                if(this.selectedClient !== '0') {
                    console.log('ajax\'ing out to obtain record repos');

                    let _this = this;
                    $.ajax({
                        url: 'records/'+_this.selectedClient,
                        method: 'GET',
                        success: function (data) {
                            if(('success' in data) && data['success'] === true) {
                                _this.recordsRepos = data['repos'];
                                _this.showRepoView = true;
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
            console.log('RecordsMgntComponent module running...');

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
