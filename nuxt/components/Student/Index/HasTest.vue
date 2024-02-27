<template>
    <v-scale-transition origin="center center">
        <v-col
            v-if="data!== null && data.id !== undefined"
        >
            <alert-card
                color="cyan"
                action_text="テストを受ける"
                @click="$utils.openNewTab('/test/' + data.id);"
            >
                <v-card-title>
                    <v-icon class="pr-2">
                        mdi-pencil
                    </v-icon>
                    実施可能なテストがあります
                </v-card-title>
                <v-card-text id="test_data">
                    <v-row dense>
                        <v-col cols="12" class="heading-6">{{$selects.test_type[data.test_type].text}}</v-col>
                        <v-col cols="4">テスト名</v-col>
                        <v-col cols="8">{{data.name}}</v-col>
                        <v-col cols="4">制限時間</v-col>
                        <v-col cols="8">{{data.test_time}}分</v-col>
                    </v-row>
                </v-card-text>
            </alert-card>
        </v-col>
    </v-scale-transition>
</template>

<script>
import AlertCard from "@/components/Atoms/AlertCard";
export default {
    name: "HasTest",
    components: {AlertCard},
    data(){
        return{
            data: null,
        }
    },
    created() {
        this.getData();
    },
    methods:{
        async getData() {
            try {
                const resp = await this.$axios.get(this.$utils.getApiUrl(this.$apis.has_test, true, true));
                this.data = resp.data;
            } catch (e) {
                this.$utils.catchError(e);
            }
        }
    }
}
</script>

<style scoped lang="scss">
#test_data{
    .col {
        &.heading-6{
            font-size: 1.3rem;
            font-weight: bold;
        }
        color: #fff;
    }
}
</style>
