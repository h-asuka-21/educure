<template>
    <v-row id="rank" dense>
        <v-col class="head" cols="6" v-if="value.rank">
            ランク
        </v-col>
        <v-col cols="6" class="rank_main" v-bind:class="value.rank" v-if="value.rank">
            {{ value.rank }}
        </v-col>
        <v-col class="head" cols="6">総合評価点</v-col>
        <v-col cols="6" class="point total">{{getScoreString(value.total)}}</v-col>
        <v-col cols="6" class="head">担当者評価</v-col>
        <v-col cols="6" class="point">{{getScoreString(value.sales)}}</v-col>
        <v-col cols="6" class="head">理解度</v-col>
        <v-col cols="6" class="point">{{getScoreString(value.comprehension)}}</v-col>
        <v-col cols="6" class="head">思考力</v-col>
        <v-col cols="6" class="point">{{getScoreString(value.think)}}</v-col>
        <v-col cols="6" class="head">報告率</v-col>
        <v-col cols="6" class="point">{{getScoreString(value.report)}}</v-col>
        <v-col cols="6" class="head">進捗率</v-col>
        <v-col cols="6" class="point">{{getScoreString(value.progress)}}</v-col>
    </v-row>
</template>

<script>
export default {
    name: "Rank",
    props: {
        value: {
            required: false,
            default: undefined
        }
    },
    methods:{
        getScoreString(v){
            if(v === null){
                return '未評価'
            }
            return v;
        },
    }
}
</script>

<style scoped lang="scss">
#rank {
    font-size: 1.1rem;
    color: #717A7E;

    .rank_main {
        font-size: 2rem;
        font-weight: bold;

        &.S {
            color: #F44336;
        }

        &.A {
            color: #FF5722;
        }

        &.B {
            color: #009688;
        }

        &.C {
            color: #3F51B5;
        }

        &.D {
            color: #9C27B0;
        }
    }

    .point {
        font-weight: bold;
        font-size: 1.2rem;
        &.total{
            font-size: 1.3rem;
        }
    }

}
</style>
