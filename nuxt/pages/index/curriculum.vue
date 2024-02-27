<template>
    <main-container
        title="カリキュラム"
        :tabs="tabs"
    >
        <nuxt-child></nuxt-child>
    </main-container>
</template>

<script>
    import MainContainer from "../../components/Molecules/MainContainer";
    export default {
        components: {MainContainer},
        data(){
            return{
                tabs:[]
            }
        },
        created(){
            this.$store.dispatch('page/hideTab');
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setBackUrl', null);
            this.$store.dispatch('page/sideBar', true);

        }
    }
</script>

<style scoped>

</style>
