<template>
    <v-card outlined class="fill-height" id="today_reserve"
            :loading="loading"
            :disabled="loading"
    >
        <v-card-title>
            <p>本日の受講状況</p>
            <v-spacer></v-spacer>
            <p class="date_area">{{$moment().format('YYYY年M月D日')}}</p>
        </v-card-title>
        <v-card-text v-if="data !== null">
            <v-row dense>
            </v-row>
            <v-row dense v-if="data.id">
                <v-col cols="12" class="subtitle-1">
                    {{ data.attendance_flg === 1 ? '受講済み' : '受講可能' }}
                </v-col>
            </v-row>
            <v-row dense v-else>
                <v-col>本日の予約はありません</v-col>
            </v-row>
        </v-card-text>
        <student-progress-dialog
            ref="student_progress_dialog"
            v-if="progress_data"
            v-model="progress_data"
            @reload="getData"
        />
    </v-card>
</template>

<script>
import StudentProgressDialog from "~/components/Student/Index/TodayReserve/StudentProgressDialog";

export default {
    name: "TodayReserve",
    components: {StudentProgressDialog},
    data() {
        return {
            data: null,
            report_data: null,
            progress_data: null,
            loading: false
        }
    },
    created() {
        this.getData();
    },
    methods: {
        async getData() {
            try {
                this.loading = true;
                const resp = await this.$axios.get(this.$utils.getApiUrl(this.$apis.today_reserve, true));
                this.data = resp.data;
                this.report_data = {
                    id: this.data.report_id,
                    reservation_id: this.data.id,
                    personal_evaluation: this.data.personal_evaluation || 3,
                    worked: this.data.worked,
                    note: this.data.note,
                    student_id: this.$store.state.student.data.id
                };
                this.progress_data = {
                    reservation_id: this.data.id,
                    progress_data: resp.data.progress
                }
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style lang="scss">
#today_reserve {
    position: relative;
    .date_area{
        font-weight: bold;
        font-size: 1.1rem;
    }

    .v-card__text{
        min-height: 150px;
    }
    .v-card__actions {
        position: absolute;
        bottom: 0;
        width: 100%;
    }
}
</style>
