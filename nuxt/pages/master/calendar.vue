<template>
    <main-container
        title="カレンダー"
        :tabs="[]"
    >
        <schedule-calendar :admin="true"/>
    </main-container>
</template>

<script>
    import MainContainer from "../../components/Molecules/MainContainer";
    import ScheduleCalendar from "@/components/Schedule/ScheduleCalendar";
    export default {
        components: {ScheduleCalendar, MainContainer},
        data(){
            return{

            }
        },
        created() {
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/hideTab');
            this.$store.dispatch('page/setTitle', 'カレンダー');
        }
    };
</script>
