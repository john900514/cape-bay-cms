<script>
    let et = new Vue({
        el: '#app',
        watch: {

        },
        data: {
            totalAvailableUsers: {!! json_encode($mobile_users) !!},
            currentlyAvailableUsers: {},
            usersToMessage: {},
            selectMode: 'multi',
            fields: {!! json_encode($fields) !!},
            checkAllUsers: true,
            users_selected: 0,
            currentPage: 1,
            perPage: 25
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
                console.log('Showing all available users');
                this.currentlyAvailableUsers = this.totalAvailableUsers;

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
                    this.currentlyAvailableUsers[member]['selected'] = false
                }
                this.users_selected = 0;
                this.usersToMessage = {};
                this.checkAllUsers = true;
            },
        },
        mounted: function () {
            this.getAvailableUsers();
            console.log('Push Notifications Table Interface Mounted.')
        }
    });
</script>
