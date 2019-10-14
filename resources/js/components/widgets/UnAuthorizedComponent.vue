<template>

</template>

<script>
    export default {
        name: "UnAuthorizedComponent",
        props: ['errorCode'],
        mounted() {
            console.log('Preparing to redirect');
            window.location.href = '/error/'+this.errorCode;
        }
    }
</script>

<style scoped>

</style>
