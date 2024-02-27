<template>
    <main-container
        title="管理者"
        :tabs="tabs"
    >
        <nuxt-child></nuxt-child>
    </main-container>
</template>

<script>
    import MainContainer from "@/components/Molecules/MainContainer";
    export default {
        components: {MainContainer},
        data(){
            return{
                tabs:[
                    {name:'管理者一覧',url:'/admin/user'},
                    {name:'管理者登録',url:'/admin/user/add'},
                ]
            }
        },
        created() {
            this.$store.dispatch('page/showTab');
            this.$store.dispatch('page/setTabs',this.tabs);
            this.$store.dispatch('page/hideBackButton');
        }
    }
</script>

<style scoped>

</style>
