<template>
    <simple-card-dialog
        :title="data.name"
        ref="dialog"
    >
        <v-simple-table>
            <thead>
            <tr>
                <th></th>
                <th>かかった日数</th>
                <th>目標日数</th>
                <th>進捗率</th>
                <th>最終学習日</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(step,key) in data.steps" :key="key">
                <td>{{ step.name }}</td>
                <td>{{ step.date_count }}</td>
                <td>{{ step.target_days }}</td>
                <td>{{ step.latest_progress_status }}</td>
                <td>{{ step.latest_date ? $moment(step.latest_date).format('YYYY年M月D日') : '' }}</td>
            </tr>
            </tbody>
        </v-simple-table>
    </simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
export default {
    name: "StepDetailDialog",
    components: {SimpleCardDialog},
    props:{
        data:{
            type:Object,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
