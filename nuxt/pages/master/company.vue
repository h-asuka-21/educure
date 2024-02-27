<template>
    <main-container
        title="企業"
        :tabs="tabs"
        @new="$refs.child.add()"
    >
        <nuxt-child ref="child"/>
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
        }
    }
</script>

<style scoped>

</style>
