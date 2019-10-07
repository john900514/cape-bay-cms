<template>
    <div class="sidebar-menu">
        <li>
            <a :href="$parent.anchorCMS.dashboardURL">
                <i class="fa fa-dashboard"></i> <span>{{ $parent.anchorCMS.transDash }}</span>
            </a>
        </li>
        <li v-for="(opt, idx) in options">
            <a :href="$parent.anchorCMS.backpackURL+'/'+opt.route">
                <i :class="opt.icon"></i> <span>{{ opt.name }}</span>
            </a>
        </li>

    </div>
</template>

<script>

    export default {
        name: "SidebarComponent",
        props: ['user'],
        data() {
            return {
                options: {}
            };
        },
        methods: {
            getMenuOptions() {
                let _this = this;
                console.log('Reaching out to Anchor for Sidebar Options...')
                axios
                    .get('/components/sidebar')
                    .then(response => {
                        let data = response.data;
                        console.log('Anchor Response - ', data);
                        _this.options = data;
                    })
            }
        },
        mounted() {
            console.log('Sidebar Loaded', this.user);
            this.getMenuOptions();
        }
    }
</script>

<style scoped>
    .sidebar-menu>li>a>.fa,
    .sidebar-menu>li>a>.fas,
    .sidebar-menu>li>a>.glyphicon,
    .sidebar-menu>li>a>.ion {
        margin-right: 5px;
        width: 20px;
    }

    body[class^='skin-'] .sidebar-menu>li>a {
        border-left: 2px solid transparent;
    }

    .skin-purple .sidebar-menu>li.header {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .content-header {
        padding: 35px 15px 0px 15px;
    }
    .content-header>h1 {
        font-size: 32px;
        font-weight: 200;
    }
    .content-header>.breadcrumb {
        top: 5px;
    }
    .user-panel {
        padding: 20px 10px 20px 10px;
    }

    .btn {
        border: none;
    }

    .btn-default {
        background-color: #f4f4f4;
    }

    body {
        font-size: 15px;
    }

    .box {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }

    .logo-lg b {
        font-weight: 400;
    }

    .content-header>h1>small {
        font-weight: 400;
    }

    .pace .pace-activity {
        display: none;
    }

    .nav-stacked>li.active>a, .nav-stacked>li.active>a:hover {
        background: #f7f7f7;
        border: none;
    }


    .nav-stacked>li.active>a, .nav-stacked>li.active>a:hover {
        background: #f7f7f7;
        border: none;
    }

    #crudTable {
        border: none!important;
    }

    #crudTable_filter input {
        border: none;
    }
</style>
