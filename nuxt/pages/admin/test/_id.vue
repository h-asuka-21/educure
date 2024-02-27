<template>
    <main-container
        title="テスト詳細"
        :tabs="tabs"
    >
        <nuxt-child></nuxt-child>
    </main-container>
</template>

<script>
    import MainContainer from "../../../components/Molecules/MainContainer";
    export default {
        components: {MainContainer},
        data() {
            return {
                tabs:[]
            }
        },
        created() {
            this.$store.dispatch('page/setTabs',this.tabs);
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setBackUrl', null);
            const id = this.$route.params.id;
            this.$store.dispatch('page/setTabs',[
                {name:'テスト詳細',url:'/admin/test/' + id},
                {name:'受講者一覧',url:'/admin/test/'  + id + '/student'},
            ]);
        }
    }
</script>

<style scoped>

</style>
