<template>
    <div class="grand-select-container">
        <div class="grand-select-wrapper">
            <div class="grand-select">
                <v-select
                    :items="selectData"
                    :label="getLabel"
                    solo
                    :loading="loading"
                    v-model="$parent.selected"
                ></v-select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "grandSelectComponent",
        props: ['items', 'label', 'loading', 'loadingmsg'],
        watch: {
            items(payload) {
                console.log('Updating Dropdown data');
                this.selectData = payload;
            }
        },
        data() {
            return {
                selectData: [],
                choice: ''
            };
        },
        computed: {
            getLabel() {
                if(this.loading) {
                    return this.loadingmsg;
                }
                else {
                    return this.label;
                }
            }
        },
        methods: {

        }
    }
</script>

<style scoped>
    @media screen {
        .grand-select-container {
            width: 100%;
            height: 100%;
        }

        .grand-select-wrapper {
            text-align: center;
        }
    }

    @media screen and (max-width: 767px){
        .grand-select-wrapper {
            margin: 10% 5%;
        }
    }

    @media screen and (min-width: 768px) and (max-width: 1199px){
        .grand-select-wrapper {
            margin: 10% 10%;
        }
    }

    @media screen and (min-width: 1200px){
        .grand-select-wrapper {
            margin: 10% 15%;
        }
    }

</style>
