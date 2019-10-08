<template>
    <div id="dashMenu">
        <div id="innerDashWrapper">
            <div id="widgetRow">
                <div class="widget-row">
                    <info-box-grid
                        client="default"
                    ></info-box-grid>
                </div>
            </div>

            <div id="infoRow">
                <div id="rightWidget">
                    <div id="innerRightWidget">
                        <pie-chart-widget></pie-chart-widget>
                    </div>
                </div>

                <div class="info-row">
                    <div id="leftWidget">
                        <div id="innerLeftWidget">
                            <recent-added-list></recent-added-list>
                        </div>
                    </div>

                    <div id="middleWidget">
                        <div id="innerMiddleWidget">
                            <metered-info-grid></metered-info-grid>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "dashboardMenu",
        data() {
            return {

            };
        },
        mounted() {
            console.log('Dashboard Loaded')
        }
    }
</script>

<style scoped>
    @media screen {
        #dashMenu {
            width: 100%;
        }

        #innerDashWrapper {
            padding: 1em 0;
            margin: 0 auto;

            display: flex;
            flex-flow: column;
        }
    }

    @media screen and (min-width: 1000px) {
        .info-row {
            display: flex;
            flex-flow: row wrap;
        }

        #leftWidget {
            width: 50%;
        }

        #innerLeftWidget {
            margin: 1em;
        }

        #middleWidget {
            width: 50%;
        }

        #innerMiddleWidget {
            margin: 1em;
        }

        #rightWidget {
            width: 100%;
        }

        #innerRightWidget {
            margin: 1em;
        }
    }

    @media screen and (min-width: 721px) and (max-width: 999px){

        .info-row {
            display: flex;
            flex-flow: row wrap;
        }

        #leftWidget {
            width: 50%;
        }

        #innerLeftWidget {
            margin: 1em;
        }

        #middleWidget {
            width: 50%;
        }

        #innerMiddleWidget {
            margin: 1em;
        }

        #rightWidget {
            width: 100%;
        }

        #innerRightWidget {
            margin: 1em;
        }
    }

    @media screen and (max-width: 720px) {

    }

</style>
