<template>
    <nuxt-child/>
</template>

<script>
    export default {
        data(){
            const id = this.$route.params.id
            return{
                tabs:[
                    {name:'詳細',url:'/admin/schedule/' + id},
                    {name:'予約者一覧',url:'/admin/schedule/'  + id + '/student'},
                ]
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', 'スケジュール詳細');
            this.$store.dispatch('page/setTabs',this.tabs);
            this.$store.dispatch('page/showBackButton');
            this.$store.dispatch('page/showTab');
        }
    }
</script>

<style scoped>

</style>
