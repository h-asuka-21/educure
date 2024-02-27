<template>
    <main-container
        title="受講者管理"
        :tabs="tabs"
    >
        <nuxt-child></nuxt-child>
    </main-container>
</template>

<script>
    import MainContainer from "../../components/Molecules/MainContainer";

    export default {
        components: {MainContainer},
        data() {
            return {
                tabs: []
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', '受講者一覧');
            this.$store.dispatch('page/hideTab');
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setTabs', this.tabs);
        },
    }
</script>

<style scoped>

</style>
