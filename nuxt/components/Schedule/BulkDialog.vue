<template>
    <simple-card-dialog
        ref="dialog"
        :title="$moment(this.month).format('YYYY年M月') + 'のスケジュール一括登録'"
    >
        <schedule-form
            @hide="hide"
            :admin="admin"
            bulk
            :month="month"
        >
        </schedule-form>
    </simple-card-dialog>
</template>

<script>
export default {
    name: "BulkDialog",
    data() {
        return {
            month: null
        }
    },
    props: {
        admin: {
            type: Boolean,
            default: false
        }
    },
    methods:{
        show(month){
            this.month = month;
            this.$refs.dialog.show();
        },
        hide(){
            this.$emit('reload');
            this.$refs.dialog.hide();
        }
    }
}
</script>

<style scoped>
</style>
