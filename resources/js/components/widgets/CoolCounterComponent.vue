<template>
    <div class="widget-container">
        <div class="widget-wrapper">
            <div class="iCountUp">
                <ICountUp
                    :delay="delay"
                    :endVal="counted_value"
                    :options="options"
                    @ready="onReady"
                />
            </div>

            <div class="name-section">
                {{ widget_name }}
            </div>
        </div>
    </div>
</template>

<script>
    import ICountUp from 'vue-countup-v2';

    export default {
        name: "CoolCounterComponent",
        props: ['value', 'name'],
        components: {
            ICountUp
        },
        data() {
            return {
                delay: 0,
                counted_value: 0,
                widget_name: 'default',
                options: {
                    useEasing: true,
                    useGrouping: true,
                    separator: ',',
                    decimal: '.',
                    prefix: '',
                    suffix: ''
                }
            };
        },
        computed: {},
        methods: {
            onReady: function(instance, CountUp) {
                console.log('Counting up!');
                const that = this;
                instance.update(that.counted_value);
            }
        },
        mounted: function () {
            if(this.name !== '' || this.name !== undefined) {
                this.widget_name = this.name;
            }

            if(this.value !== '' || this.value !== undefined) {
                this.counted_value = parseInt(this.value);
            }

            console.log('CoolCounterComponent Running For '+ this.widget_name + ' Widget.');

        }
    }
</script>

<style scoped>
    .iCountUp {
        margin: 0;
        color: #4d63bc;
    }

    @media screen and (min-width: 721px) {
        .iCountUp {
            font-size: 12em;
        }
    }

    @media screen and (max-width: 720px) {
        .iCountUp {
            font-size: 9em;
        }
    }

    .widget-container {
        margin: 0 3em;
        border: 2px solid black;
        text-align: center;
        padding: 0 2em;
    }
</style>
