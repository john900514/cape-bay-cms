<template>
    <div class="info-box-container">
        <div v-if="authorized === true" class="row" v-show="infoBoxData.length > 0">
            <info-box v-for="(box, idx) in infoBoxData" v-bind:key="idx"
                :class="box.class"
                :icon="box.icon"
                :iconbg="box.iconbg"
                :text="box.text"
                :value="box.value"
            ></info-box>
        </div>
        <div v-else>
            <h1> Not Authorized to Access Widget Line Up. </h1>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InfoBoxGridComponent",
        props: ['client'],
        data() {
            return {
                infoBoxData: [],
                authorized: true
            };
        },
        methods: {
            getInfoBoxData() {
                let _this = this;
                axios
                    .get('/users/dashboard/widgets/info-box-grid/'+this.client)
                    .then(response => {
                        let data = response.data;
                        console.log('Anchor Response - ', data);

                        if('success' in data && data['success'] === true) {
                            this.infoBoxData = data['data'];
                        }
                        else {
                            if('reason' in data) {
                                if(data['reason'] === 'Unauthorized') {
                                    this.authorized = false;
                                }
                                console.log(data['reason']);
                            }
                            else {
                                console.log('Unknown error getting info box grid');
                            }
                        }

                        _this.loading = false;
                    })
            },
        },
        mounted() {
            console.log('Info Box Grid Loaded');
            this.getInfoBoxData()
        }
    }
</script>

<style scoped>

    @media screen {
        .row {
            display: flex;
            justify-content: center;
        }
    }

    @media screen and (min-width: 1000px) {
        .row {
            flex-flow: row;
        }
    }

    @media screen and (min-width: 721px) and (max-width: 999px){
        .row {
            flex-flow: row wrap;
        }
    }

    @media screen and (max-width: 720px) {
        .row {
            flex-flow: column;
        }
    }

</style>
