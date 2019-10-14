<template>
    <div class="table-top">
        <div class="inner-table-top">
            <div class="control-panel">
                <div class="inner-control-panel">
                    <div class="halfsies">
                        <div class="half panel-actual">
                            <div class="templates-select" :class="(selectedTemplate !== 0) ? 'quick-auto' : ''">
                                <v-select
                                    :items="messageTemplates"
                                    label="Message Templates"
                                    solo
                                    v-model="selectedTemplate"
                                ></v-select>
                            </div>
                            <div class="custom-msg">
                                <v-textarea
                                    v-show="selectedTemplate === 0"
                                    outlined
                                    name="input-7-4"
                                    label="Custom Message"
                                    :value="customMessage"
                                    v-model="customMessage"
                                ></v-textarea>
                            </div>
                        </div>

                        <div class="half controller-actual">
                            <h1> Control Panel</h1>
                            <div class="button-wrapper">
                                <v-btn depressed :disabled="(selected.length === 0) && (selectedTemplate === '')" color="primary" @click="fireMessage()">Send Msgs</v-btn>
                            </div>

                            <div class="button-wrapper">
                                <v-btn depressed @click="resetGrid()" color="error">Reset</v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-actual-section">
            <div class="table-actual-wrapper">
                <div class="outer-table-actual">
                    <v-card>
                        <v-card-title>
                            Mobile App Users
                            <div class="flex-grow-1"></div>
                            <v-text-field
                                v-model="search"
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-card-title>
                        <v-data-table
                            v-model="selected"
                            :headers="headers"
                            :items="usersBill"
                            :search="search"
                            show-select
                            item-key="name"
                            :items-per-page="itemsPerPage"
                            class="elevation-2"
                            :footer-props="footerProps"
                        >
                        </v-data-table>
                    </v-card>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // We will manage the users here
    export default {
        name: "MobilePushNotesTableComponent",
        props: ['users'],
        watch: {
            users(pkg) {
                this.sortUsers(pkg)
            },
            selected(pkg) {
                if(pkg.length === 0) {
                    this.$parent.stepData[2].value = '---';
                }
                else {
                    this.$parent.stepData[2].value = pkg.length + ' Selected'
                }

            },
            selectedTemplate(num) {
                console.log('Num - '+num);
            }
        },
        data() {
            return {
                search: '',
                selected: [],
                headers: [
                    { text: 'Member ID', align: 'left', value: 'name', sortable: true},
                    { text: 'First Name', sortable: false, value: 'fname'},
                    { text: 'Last Name', value: 'lname' },
                    { text: 'Email', value: 'email' },
                    { text: 'Home Club', value: 'home_club_id' },
                    { text: 'Last Login', value: 'last_login'}
                ],
                usersBill: [],
                selectedTemplate: '',
                messageTemplates: [],
                customMessage: '',
                itemsPerPage: 200,
                footerProps: {
                    'items-per-page-options': [10,100,200,500,1000,-1]
                },
                pagination: {
                    rowsPerPage: 200,
                    sortBy: 'memberno'
                },
            };
        },
        methods: {
            sortUsers(users) {
                let res = [];
                for(let idx in users) {
                    let props = {
                        value: false,
                        name: users[idx].member_id,
                        fname: users[idx].first_name,
                        lname: users[idx].last_name,
                        email: users[idx].email,
                        'home_club_id': users[idx].primary_location_name,
                        //barcode: users[idx].barcode,
                        'last_login': users[idx].last_login,
                        'push_type': 'mobile',
                        'push_token': users[idx].expo_push_token
                    };
                    res.push(props);
                }

                this.usersBill = res;
                this.messageTemplates = this.getTemplates();
            },
            resetGrid() {
                this.selected = [];
                this.customMessage = '';
                this.selectedTemplate = '';
                this.search ='';
                console.log('Grid Reset Was Toggled.');
            },
            getTemplates() {
                let results = [
                    {text: 'Custom (One-Time) Message', value: 0}
                ];
                // @todo - ping the server for templates;

                return results;
            },
            fireMessage() {
                if(this.selected.length > 0) {
                    if(this.customMessage !== '') {
                        let choice = confirm('Are you sure you want to send this message to the '+this.selected.length+' selected users?');
                        if(choice) {
                            let _this = this;
                            console.log('Sending Notification Requests to Anchor...');
                            // @todo - support template messages along with custom
                            let data = {
                                users: this.selected,
                                message: this.customMessage,
                                clientId: this.$parent.clients[this.$parent.selectedClient]
                            };
                            axios({
                                method: 'POST',
                                url: '/users/push-notes/fire',
                                data: data
                            }).then(response => {
                                    let data = response.data;
                                    console.log('Anchor Response - ', data);
                                    if('success' in data && data['success'] === true) {
                                        alert('Messages Successfully Fired!')
                                    }
                                    else {
                                        alert('Could not fire out messages. Please try Again')
                                    }

                                    _this.loading = false;
                                })
                                .catch(function (error) {
                                    console.log(error);
                                    alert('SERVER ERROR - Could not fire out messages. Please try Again')
                                    _this.loading = false;
                                });
                        }
                        else {
                            alert('No Problem. Maybe later.');
                        }
                    }
                    else {
                        alert('Type a message firs!');
                    }
                }
                else {
                    alert('Select some users first!');
                }

            }

        },
        mounted() {
            this.sortUsers(this.users);
            console.log('Table is mounted')
        }
    }
</script>

<style scoped>
    .inner-table-top {
        margin: 2em 0;
        border: 1px solid transparent;
        box-shadow: 1px 1px 10px 1px #ccc;
        border-radius: 0.5em;
    }

    .control-panel {
        margin: 0 1.5em;
        padding: 1em 0;
    }

    .halfsies {
        display: flex;
        flex-flow: row;
    }

    .half {
        width: 50%;
    }

    .controller-actual {
        margin: auto;
    }

    .button-wrapper {
        margin: 1em;
    }

    .quick-auto {
        margin: 10% 0;
    }
</style>
