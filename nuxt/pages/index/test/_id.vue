<template>
    <answer-form
        v-model="data"
        :loading="loading"
        @error="$router.push('/')"
        @start="started = true"
        @finish="started = false"
    >
    </answer-form>
</template>

<script>
import AnswerForm from "../../../components/UserTest/AnswerForm";
import Questions from "../../../components/UserTest/AnswerForm/Questions";

export default {
    components: {AnswerForm, Questions},
    data() {
        return {
            data: {
                test: {},
                questions: [],
            },
            loading: true,
            started: false
        }
    },
    created() {
        this.$store.dispatch('page/setTitle', '');
        this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/sideBar', false);
    },
    methods:{
        confirmSave(event) {
            event.returnValue = "テストを開始後にページ遷移すると、再テストはできません。よろしいですか？";
        },
    }
}
</script>

<style scoped>

</style>
