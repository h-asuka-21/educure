<template>
    <confirm-dialog
        ref="start_confirm"
        @confirm="$emit('confirm')"
        @ng="$emit('ng')"
        :persistent="true"
    >
        <p class="title" v-if="data.test">{{ data.test.name }}</p>
        <p class="subtitle-1" v-if="data.test">制限時間: {{ data.test.test_time }}分</p>
        <p class="subtitle-1 mb-2" v-if="data.questions">問題数: {{ data.questions.length }}問</p>
        <div class="pt-2 caption">※開始するとタイマーがスタートします。</div>
        <div class="pt-2 caption">※テスト画面を離脱しても、タイマーのカウントが継続されます。</div>
        <div class="pt-2 caption">※時間内であれば回答画面に戻り回答を続けることができます。</div>
        <div class="pt-2 caption">※回答途中でも制限時間で自動的に回答が送信されます。</div>
        <div class="pt-4 text-center justify-center">テストを開始してよろしいですか？</div>
    </confirm-dialog>
</template>

<script>
import ConfirmDialog from "@/components/Molecules/ConfirmDialog";
export default {
    name: "StartConfirm",
    components: {ConfirmDialog},
    props:{
        data: {
            type: Object,
            required: true
        },
    },
    methods:{
        show(){
            this.$refs.start_confirm.show();
        },
        hide(){
            this.$refs.start_confirm.hide();
        }

    }
}
</script>

<style scoped>

</style>
