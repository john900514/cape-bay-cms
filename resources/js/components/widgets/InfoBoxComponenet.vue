<template>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon" :class="iconbg"><i :class="icon"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ text }}</span>
                <span class="info-box-number" v-html="value"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</template>

<script>
    export default {
        name: "InfoBoxComponenet",
        props: ['icon','iconbg','text','value'],
    }
</script>

<style scoped>

</style>
