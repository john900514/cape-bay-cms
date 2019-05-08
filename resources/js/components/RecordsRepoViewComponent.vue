<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Records Management - View Repository</div>

                    <div class="card-body">
                        <div id="repoSelect">
                            <select v-model="selectedRepo" @change="getRepoEntries()">
                                <option v-for="($text, $elemId) in repoData" :value="$elemId">{{ $text }}</option>
                            </select>
                        </div>

                        <div v-if="selectedRepo !== '' && showDataView === true" id="repoView">
                            <!--
                                Steps
                                1. Ajax out to get the datasets, columns and the data
                                2. For each dataset, load a module template
                                    a. Both Club Datasets will return 2 DB Views
                                    b. User records would return 1 DB view, & 1 report view (to show conversion)
                                    c. Promo and Amenities
                            -->
                            <div v-for="(dataz, viewName) in viewData">
                                <database-record-view-component v-if="dataz['type'] === 'DB'" :argsdata="dataz"></database-record-view-component>
                                <data-report-view-component v-else-if="dataz['type'] === 'Report'" :argsdata="dataz"></data-report-view-component>
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
        name: "RecordsRepoViewComponent",
        props: ['resultdata'],
        data() {
            return {
                repoData: {},
                selectedRepo: '',
                showDataView: false,
                viewData: {}
            };
        },
        computed: {},
        methods: {
            getRepoEntries: function getRepoEntries () {
                console.log('Ajax call out to get repo entries')

                this.viewData = {
                    viewOne: {
                        type: 'DB',
                        typeDesc: 'Internal Data',
                        ClubID: 'TR39',
                        address: '2944 Boca Chica Blvd',
                        city: 'Brownsville',
                        state: 'TX',
                        zip: '78521'
                    },
                    viewTwo: {
                        type: 'DB',
                        typeDesc: 'Mobile App Data',
                        ClubID: 'TR39',
                        address: '2944 Boca Chica Blvd, Brownsville, TX 78521'
                    }
                };
                this.showDataView = true;
            }
        },
        mounted: function () {
            console.log('RecordsRepoViewComponent module running...');

            this.repoData = this.resultdata;
        }
    }
</script>

<style scoped>
    #repoSelect {
        text-align: center;
    }

    .centered-text {
        text-align:center;
    }
</style>
