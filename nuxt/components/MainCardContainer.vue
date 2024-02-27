<template>
    <v-container class="main-container fill-height justify-center d-block mt-10 "
    >
        <v-card raised id="main-card">
            <v-card :color="$colors.main" dark class="text-center d-inline-block ml-5 mt-n6">
                <v-card-title class="font-weight-bold">{{this.$route.name}}</v-card-title>
            </v-card>
            <tabs v-if="pages_data!==undefined" :items="pages_data"></tabs>
            <router-view></router-view>
        </v-card>
    </v-container>
</template>
<script>
    import Tabs from "./Molecules/Tabs";
    export default {
        components: {
            Tabs
        },
        props: ['pages_data'],
        methods:{
            // isWorker(){
            //     return this.$store.default.state.type === 'worker';
            // }
        }
    }
</script>
<!--<style lang="scss">-->
    <!--@import "resources/sass/variables";-->
    <!--.main-container{-->
        <!--width:100%;-->
        <!--max-width:90vw;-->
        <!--min-width: 80vw;-->
        <!--@include mq(sm){-->
            <!--max-width: 100vw;-->
        <!--}-->
        <!--#main-card{-->
            <!--min-height: 78vh!important;-->
        <!--}-->
        <!--&.worker_container {-->
            <!--padding-bottom: 34px !important;-->
        <!--}-->
    <!--}-->
<!--</style>-->
