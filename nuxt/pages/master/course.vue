<template>
    <main-container
        title="コース管理"
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
                    {name:'一覧',url:'/master/course'},
                    {name:'新規作成',url:'/master/course/add'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
