<template>
    <main-container
        title="進捗・受講一覧"
        :tabs="[]"
    >
        <v-col cols="12">
            <student-progress-list/>
        </v-col>
    </main-container>

</template>

<script>
import MainContainer from "~/components/Molecules/MainContainer";
import StudentProgressList from "~/components/Progress/StudentProgressList";
export default {
    components: {StudentProgressList, MainContainer},
    created() {
        this.$store.dispatch('page/hideTab');
    }
}
</script>

<style scoped>

</style>
