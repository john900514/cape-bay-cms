<script>
    let et = new Vue({
        el: '#app',
        watch: {
            columnSelect: function (data) {
                if(data === '0') {
                    this.columnSelectText = '';
                }
            },
            columnSelectText: function (data) {
                this.getAvailableUsers();
            }

        },
        data: {
            totalAvailableUsers: {!! json_encode($mobile_users) !!},
            currentlyAvailableUsers: {},
            usersToMessage: [],
            selectMode: 'multi',
            fields: {!! json_encode($fields) !!},
            msgTemplates: {!! json_encode($note_templates) !!},
            checkAllUsers: true,
            users_selected: 0,
            currentPage: 1,
            perPage: 25,
            columnSelect: '0',
            columnSelectText: '',
            msgTemplateSelect: '0',
        },
        computed: {
            rows() {
                return this.currentlyAvailableUsers.length
            }
        },
        methods: {
            getAvailableUsers: function getAvailableUsers() {
                let $results = [];
                // @todo - implement filters into this function
                if(this.columnSelect !== '0') {
                    let tempAvailableUsers = [];
                    for(muser_idx in this.totalAvailableUsers) {
                        if(this.totalAvailableUsers[muser_idx][this.columnSelect].includes(this.columnSelectText)) {
                            tempAvailableUsers.push(this.totalAvailableUsers[muser_idx]);
                        }
                    }
                    this.currentlyAvailableUsers = tempAvailableUsers;
                }
                else {
                    console.log('Showing all available users');
                    this.currentlyAvailableUsers = this.totalAvailableUsers;
                }

                $results = this.currentlyAvailableUsers;
                return $results;
            },
            toggleSelectAll: function toggleSelectAll() {
                if(this.checkAllUsers === true) {
                    this.users_selected = 0;
                    let avails = this.getAvailableUsers();
                    for(member in avails) {
                        let uuid = avails[member]['id'];
                        if(!(uuid in this.usersToMessage)) {
                            this.usersToMessage[uuid] = avails[member]['expo_push_token'];
                        }
                        this.currentlyAvailableUsers[member]['selected'] = true;

                        this.users_selected++;
                    }
                    this.checkAllUsers = false;
                }
                else {
                    this.clearUsers();
                }
            },
            selectUser: function selectMember(uuid, email) {
                if(!(uuid in this.usersToMessage)) {
                    this.usersToMessage[uuid] = email;
                    this.users_selected++;
                }
                else {
                    delete this.usersToMessage[uuid];
                    this.users_selected--;
                }
            },
            clearUsers: function clearMembers() {
                let avails = this.getAvailableUsers();
                for(member in avails) {
                    this.currentlyAvailableUsers[member]['selected'] = false;
                }

                for(omember in this.totalAvailableUsers) {
                    this.totalAvailableUsers[omember]['selected'] = false;
                }
                this.users_selected = 0;
                this.usersToMessage = {};
                this.checkAllUsers = true;
            },
            confirmFireMessages: function confirmFireMessages() {
                let choice = confirm('Are you sure you want to fire out a message to '+ this.users_selected +' user(s)?')

                if(choice) {
                    this.fireMessages();
                }
                else {
                    alert('No Biggie.')
                }
            },
            structureUsersToMsg: function structureUsersToMsg() {
                let results = {};

                for(let elem in this.usersToMessage) {
                    results[elem] = this.usersToMessage[elem];
                }

                return results;
            },
            fireMessages: function fireMessages() {
                let that = this;
                $.ajax({
                    url: 'push/fire',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        template_id: that.msgTemplates[parseInt(that.msgTemplateSelect) - 1]['uuid'],
                        users: that.structureUsersToMsg(),
                    },
                    success: function(data) {
                        if('success' in data) {
                            if(data['success'] === true) {
                                alert('Yay!')
                            }
                            else {
                                alert(data['reason'])
                            }
                        }
                        else {
                            alert('Trouble happened. Try again.')
                        }
                    },
                    error: function (e) {
                        alert('Issue happened trying to clean up our rooms. Try again.')
                    }
                });
            }
        },
        mounted: function () {
            this.getAvailableUsers();
            console.log('Push Notifications Table Interface Mounted.')
        }
    });
</script>
