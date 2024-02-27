<template>
    <v-snackbar
        id="app_alert"
        top
        v-model="status"
        :timeout="3000"
        :color="$store.state.alert.color"
    >
        {{$store.state.alert.message}}
    </v-snackbar>
</template>
<script>
    export default {
        name: 'Alert',
        data() {
            return {
                status: false
            }
        },
        computed: {
            enable: function () {
                return this.$store.getters['alert/getEnable'];
            }
        },
        watch: {
            enable (val) {
                this.status = val;
            },
            status(val){
                this.$store.commit('alert/setStatus', val);
            }
        }
    };
</script>
<style lang="scss" scoped>
    #app_alert {
        top: 70px;
        z-index: 9999;
        max-width: 100vw;
        .v-snack__wrapper{
            z-index: 9999!important;
        }
    }
</style>
