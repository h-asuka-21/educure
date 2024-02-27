<template>
    <simple-card-dialog
        ref="dialog"
        :title="title"
    >
        <schedule-form
            :create_flg="create_flg"
            :target_date="date"
            @hide="hide"
            :admin="admin"
            :company_ids="company_ids"
            v-model="item_data"
        >
        </schedule-form>
    </simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "~/components/Molecules/SimpleCardDialog";
import ScheduleForm from "~/components/Schedule/ScheduleForm";

export default {
    name: "ScheduleDialog",
    components: {ScheduleForm, SimpleCardDialog},
    data() {
        return {
            title: ''
        }
    },
    props: {
        date: {
            type: String,
            required: true
        },
        admin: {
            type: Boolean,
            default: false
        },
        company_ids: {
            type: Array,
            default: undefined
        },
        item_data: {
            type: Object,
            default: undefined
        },
        create_flg: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        hide() {
            this.$emit('reload')
            this.$refs.dialog.hide();
        }
    }
}
</script>

<style scoped>

</style>
