<template>
    <main-container
        title="不足評価管理"
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
                    {name:'一覧',url:'/master/evaluation'},
                ]
            }
        },
    }
</script>

<style scoped>

</style>
