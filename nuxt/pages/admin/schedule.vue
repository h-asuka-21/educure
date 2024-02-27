<template>
    <main-container
        title="スケジュール管理"
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
                tabs:[
                    {name:'一覧',url:'/admin/schedule'},
                    {name:'新規作成',url:'/admin/schedule/add'},
                ]
            }
        },
        created(){
            this.$store.dispatch('page/setTabs',this.tabs);
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setBackUrl', null);
        }
    }
</script>

<style scoped>

</style>
