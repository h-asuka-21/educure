<template>
    <main-container
        title="日別予約者一覧"
        :tabs="[]"
    >
        <nuxt-child/>
    </main-container>

</template>

<script>
import MainContainer from "~/components/Molecules/MainContainer";
export default {
    components: {MainContainer},
    created() {
        this.$store.dispatch('page/hideTab');
    }
}
</script>

<style scoped>

</style>
