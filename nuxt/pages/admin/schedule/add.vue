<template>
    <div class="mx-auto w-50 py-3">
        <schedule-form/>
    </div>
</template>
<script>
    import ScheduleForm from "../../../components/Schedule/ScheduleForm";
    export default {
        components: {
            ScheduleForm
        },
        created() {
            this.$store.dispatch('page/setTitle','スケジュール管理')
            this.$store.dispatch('page/setTabs',[
                {name:'一覧',url:'/admin/schedule'},
                {name:'新規作成',url:'/admin/schedule/add'},
            ]);
        }
    }
</script>
