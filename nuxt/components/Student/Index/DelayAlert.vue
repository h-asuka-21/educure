<template>
    <v-scale-transition origin="center center">
        <v-col
            v-if="data !== null && data.id !== undefined"
        >
            <alert-card outlined
                    color="deep-orange"
                    dark
                    class="fill-height"
                    action_text="マイページ"
                    @click="$router.push('/mypage')"
            >
                <v-card-title>
                    <v-icon class="pr-2">
                        mdi-emoticon-confused-outline
                    </v-icon>
                    進捗が遅れています</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            現在進行中のステップの進捗が{{data.date_count - data.target_days}}日遅れています。<br>
                            マイページで進捗を確認してください。
                        </v-col>
                    </v-row>
                </v-card-text>
            </alert-card>

        </v-col>
    </v-scale-transition>
</template>

<script>
import AlertCard from "@/components/Atoms/AlertCard";
export default {
    name: "DelayAlert",
    components: {AlertCard},
    computed:{
        data(){
            return this.$store.getters["home/getDelayAlert"];
        }
    },
}
</script>

<style scoped>
    .col{
        color: #fff;
    }
</style>
