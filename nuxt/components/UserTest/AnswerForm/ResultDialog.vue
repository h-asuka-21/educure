<template>
    <simple-card-dialog
        ref="dialog"
        max-width="400"
        :persistent="true">
        <v-card-text class="pt-2 text-center">
            <p>テストお疲れ様でした。結果は</p>
            <p class="display-1 text--primar">
                {{ questions }} 問中 {{ score }} 問
            </p>
            <p>正解でした</p>
            <v-row class="pt-3" justify="space-around">
                <v-btn :color="$colors.main" @click="ok" dark>
                    確認
                </v-btn>
            </v-row>
        </v-card-text>
    </simple-card-dialog>
</template>

<script>
    import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
    export default {
        name: "ResultDialog",
        components: {SimpleCardDialog},
        props:{
            score: {
                default:0
            },
            questions: {
                type: Number,
            }
        },
        data(){
            return {
                dialog: false
            }
        },
        methods:{
            show(){
                this.$refs.dialog.show();
            },
            ok(){
                this.$emit('confirm');
            }
        }
    }
</script>

<style scoped lang="scss">
.confirm_text{
    text-align: center;
}
</style>
