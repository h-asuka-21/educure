<template>
    <div>
        <p class="title" id="v-step-0">ダッシュボード</p>
        <p id="v-step-4"></p>
        <v-row dense>
            <v-col cols="12">
                <v-row id="alert_area" dense>
                    <delay-alert/>
                    <has-test/>
                </v-row>
            </v-col>
            <v-col cols="6">
                <today-reserve id="v-step-2"/>
            </v-col>
            <v-col cols="6">
                <get-task-files/>
            </v-col>
            <v-col cols="12">
                <progress-chart/>
            </v-col>
        </v-row>
        <tour/>
    </div>
</template>

<script>
    import TodayReserve from "@/components/Student/Index/TodayReserve";
    import GetTaskFiles from "@/components/Student/Index/GetTaskFiles";
    import ProgressChart from "@/components/Student/Detail/ProgressChart";
    import HasTest from "@/components/Student/Index/HasTest";
    import DelayAlert from "@/components/Student/Index/DelayAlert";
    import Tour from "@/components/Molecules/Tour"
    export default {
        name: "index",
        components: {Tour, DelayAlert, HasTest, ProgressChart, GetTaskFiles, TodayReserve},
        data() {
            return {
                show: true
            }
        },
        created() {
            this.$store.dispatch('page/sideBar', true);
        }
    }
</script>

<style scoped>

</style>
