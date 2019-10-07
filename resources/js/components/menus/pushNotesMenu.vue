<template>
    <div id="pushNotesMenuContainer">
        <div id="pushNotesMenuInnerContainer">
            <div id="wizardSection">
                <wizard
                    steps="3"
                    :currentstep="getCurrentStep"
                    :data="getStepData"
                ></wizard>
            </div>

            <div id="contentSection">
                <div class="page-content">
                    <div class="inner-page-content">
                        <grand-select
                            v-if="step !== 3"
                            :items="getItems"
                            :label="getLabel"
                            :loading="loading"
                            :loadingmsg="getLoadingMessage"
                        ></grand-select>

                        <push-mobile-table v-if="(step === 3) && (tableType === 'mobile')"
                            :users="getUserList"
                        ></push-mobile-table>
                        <push-wallet-table v-if="(step === 3) && (tableType === 'wallet')"
                            :users="getUserList"
                        ></push-wallet-table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "pushNotesMenu",
        watch: {
            selected(val) {
                console.log('Item was selected '+ val);
                if(this.step === 1) {
                    this.stepData[0].value = this.dropDowns.clients[val].text;
                    this.selectedClient = val;
                    this.step = 2;
                    this.loading = true;
                    this.getPlatforms();
                }
                else if(this.step === 2) {
                    if(this.selected !== '') {
                        this.stepData[1].value = this.dropDowns.platforms[val].text;
                        this.selectedPlatform = val;
                        this.step = 2;
                        this.loading = true;
                        this.getUsers()
                    }

                }
                else {

                }
            }
        },
        computed: {
            getCurrentStep() {
                return this.step;
            },
            getStepData() {
                return this.stepData;
            },
            getItems() {
                let results = '';
                switch(this.step) {
                    case 1:
                        results = this.dropDowns.clients;
                        break;

                    case 2:
                        results = this.dropDowns.platforms;
                        break;

                    default:
                        results = []
                }

                console.log(results);
                return results;
            },
            getLabel() {
                let results = '';
                switch(this.step) {
                    case 1:
                        results = 'Select a Client';
                        break;

                    case 2:
                        results = 'Select A Platform';
                        break;

                    default:
                }

                return results;
            },
            getLoadingMessage() {
                let $results = '';
                if(this.step === 1) {
                    $results = 'Hang tight, grabbing the list of Clients!'
                }
                else {
                    $results = 'One sec, getting those platforms!'
                }
                return $results;
            },
            getUserList() {
                if(this.users !== []) {
                    return this.users;
                }
                else {
                    return [];
                }
            },
        },
        data() {
            return {
                step: 1,
                stepData: [
                    {
                        title: 'Client',
                        icon: 'fad fa-anchor',
                        value: '---'
                    },
                    {
                        title: 'Platform',
                        icon: 'fad fa-mobile',
                        value: '---'
                    },
                    {
                        title: 'Users',
                        icon: 'fad fa-users',
                        value: '---'
                    },
                ],
                loading: true,
                loadingMessage: '',
                selected: '',
                selectedClient: '',
                selectedPlatform: '',
                clients: [],
                platforms: [],
                dropDowns: {
                    clients: [],
                    platforms: [],
                },
                users: [],
                tableType: '',
            };
        },
        methods: {
            getClients() {
                let _this = this;
                console.log('Reaching out to Anchor for Sidebar Options...')
                axios
                    .get('/components/clients/push-notes')
                    .then(response => {
                        let data = response.data;
                        console.log('Anchor Response - ', data);
                        if('select' in data) {
                            _this.dropDowns.clients = data.select;
                            _this.clients = data.link;
                        }
                        else {

                        }

                        _this.loading = false;
                    })
            },
            getPlatforms() {
                let _this = this;
                console.log('Reaching out to Anchor for Platform Options...')
                axios
                    .get('/components/push-notes/platforms/'+this.clients[this.selectedClient])
                    .then(response => {
                        let data = response.data;
                        console.log('Anchor Response - ', data);
                        if('select' in data) {
                            _this.dropDowns.platforms = data.select;
                            _this.platforms = data.link;
                            _this.selected = '';
                        }
                        else {

                        }

                        _this.loading = false;
                    })
            },
            getUsers() {
                let _this = this;
                console.log('Reaching out to Anchor for Users...!')
                axios
                    .get('/users/push-notes/'+this.clients[this.selectedClient]+'/'+this.platforms[this.selectedPlatform])
                    .then(response => {
                        let data = response.data;
                        console.log('Anchor Response - ', data);
                        _this.step = 3;
                        _this.users = data.users;
                        _this.tableType = data.note;

                        _this.loading = false;
                    })
            },
        },
        mounted() {
            console.log('Push Notes Menu Loaded');
            this.getClients();
        }
    }
</script>

<style scoped>
    @media screen {
        #pushNotesMenuContainer {
            width: 100%;
        }

        #pushNotesMenuInnerContainer {
            margin: 3em 5%;

            display: flex;
            flex-flow: column;
            text-align: center;
        }


    }

    @media screen and (max-width: 767px){}

    @media screen and (min-width: 768px) and (max-width: 1199px){}

    @media screen and (min-width: 1200px){}
</style>
