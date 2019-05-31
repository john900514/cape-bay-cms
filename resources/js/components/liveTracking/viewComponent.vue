<template>
    <div class="box-body">
        <div id="cardBodyContainer">
            <h1 class="tracking-value">{{ trackedVal }}</h1>

            <button id="soundButton" @click.prevent="playSound(soundfile)" style="display:none;"></button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "viewComponent",
        props: ['trackerimport', 'soundfile'],
        data() {
            return {
                tracker: {},
                trackedVal: 0
            };
        },
        computed: {},
        methods: {
            goBack: function goBack() {
                window.history.back();
            },
            loadData: function loadData () {
                $.get(this.tracker['ping_url'], function (response) {
                    if(response.number > this.trackedVal && this.trackedVal !== 0)
                    {
                        $('#soundButton').click();
                    }

                    this.trackedVal = response.number;
                }.bind(this));
            },
            playSound: function playSound(url) {
                let audio = new Audio(url);
                audio.play();
            }
        },
        mounted: function () {
            console.log('viewComponent module running...');

            this.tracker = this.trackerimport;
            console.log(this.trackerimport);

            this.loadData();

            setInterval(function () {
                this.loadData();
            }.bind(this), 3000);
        }
    }
</script>

<style scoped>
    .tracking-value {
        text-align: center;
        font-size: 10em;
    }
</style>
