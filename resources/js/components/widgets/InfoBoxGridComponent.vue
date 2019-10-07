<template>
    <div class="row">
        <info-box
            class="info-box2"
            icon="ion ion-ios-gear-outline"
            iconbg="bg-aqua"
            text="CPU-Traffic"
            value="20<small>%</small>"
        ></info-box>

        <info-box
            icon="ion ion-ios-gear-outline"
            iconbg="bg-red"
            text="CPU-Traffic"
            value="20<small>%</small>"
        ></info-box>

        <div class="clearfix visible-sm-block"></div>

        <info-box
            icon="ion ion-ios-gear-outline"
            iconbg="bg-green"
            text="CPU-Traffic"
            value="20<small>%</small>"
        ></info-box>

        <info-box
            icon="ion ion-ios-gear-outline"
            iconbg="bg-orange"
            text="CPU-Traffic"
            value="20<small>%</small>"
        ></info-box>
    </div>
</template>

<script>
    export default {
        name: "InfoBoxGridComponent"
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
