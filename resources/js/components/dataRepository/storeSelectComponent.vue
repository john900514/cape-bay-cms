<template>
    <div class="box-body">
        <div id="clubSelect">
            <h1> Select a Fitness Club</h1>
            <select v-model="selectedClub" @change="getAvailablePromoCodes()">
                <option v-for="($club_name, $club_id) in clubs.dropdown" :value="$club_id">{{ $club_name }}</option>
            </select>
        </div>

        <div v-if="selectedClub !== '0' && showPromoCodesDropDown === true">
            <div class="centered-text">
                <h2>Select a Promo Code</h2>
                <select v-model="selectedPromo" @change="redirectToPromoAmenity()">
                    <option value="0" selected>Select a Promo Code</option>
                    <option v-for="($desc, $code) in promocodes" :value="$code+'/'+selectedClub">{{ $code }}</option>
                </select>
            </div>
            <!--
            <div id="reposTable" v-if="showDataStores === true">
                <ul id="repoList" v-for="(report, idx) in dataStores">
                    <li><a :href="report['url']">{{ report['name'] }}</a></li>
                </ul>
            </div>
            -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "storeSelectComponent",
        props: ['fitnessclubs'],
        data() {
            return {
                clubs: {},
                selectedClub: '0',
                promocodes: {},
                selectedPromo: '0',
                showPromoCodesDropDown: false
            };
        },
        computed: {},
        methods: {
            getAvailablePromoCodes: function getAvailablePromoCodes() {
                if(this.selectedClub !== '0') {
                    //console.log('ajax\'ing out to obtain store promo codes');
                    this.promocodes = {};
                    let temp_codes = this.clubs.clubs[this.selectedClub]['promo_codes'];
                    for(let idx in temp_codes) {
                        if(!(temp_codes[idx]['PromoCode'] in this.promocodes)) {
                            this.promocodes[temp_codes[idx]['PromoCode']] = temp_codes[idx]['Description'];
                        }
                    }

                    this.showPromoCodesDropDown = true;

                }
                else {
                    this.promocodes = {};
                    this.showPromoCodesDropDown = false;
                }
            },
            redirectToPromoAmenity: function redirectToAmenity () {
                window.location.href = document.URL+'/view?amen='+this.selectedPromo;
            }
        },
        mounted: function () {
            console.log('storeSelectComponent module running...');

            this.clubs = this.fitnessclubs;
        }
    }
</script>

<style scoped>
    #clubSelect {
        text-align: center;
    }

    .centered-text {
        text-align:center;
    }
</style>
