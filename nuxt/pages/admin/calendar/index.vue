<template>
    <div class="fill-height">
        <monthly-calendar-and-events
            v-model="events"
            @get_date="getEvents"
            @click_event="clickEvent"
            @click_date="clickDate"
            ref="calender"
            :event-height="105"
        >
            <template v-slot:default="{event}">
                <div class="event_title ml-1 mt-1">{{ event.name }}</div>
                <div class="event_time ml-1">{{ $moment(event.start).format('HH:mm') }} -
                    {{ $moment(event.end).format('HH:mm') }}
                </div>
                <div class="student_count ml-1">席数:{{ event.student_count }}</div>
                <div class="reserve_count ml-1">予約:{{ event.reserve_count }}</div>
                <div v-if="event.attendance_count > 0" class="ml-1 attendance_count">受講:{{ event.attendance_count }}
                </div>
            </template>
        </monthly-calendar-and-events>
        <schedule-dialog
            :date="target"
            ref="dialog"
            @reload="getEvents(date)"
            :item_data="target_event"
        ></schedule-dialog>
    </div>
</template>

<script>
import MonthlyCalendarAndEvents from "../../../components/Molecules/MonthlyCalendarAndEvents";
import ScheduleDialog from "~/components/Schedule/ScheduleDialog";

export default {
    name: "index",
    components: {ScheduleDialog, MonthlyCalendarAndEvents},
    data() {
        return {
            events: [],
            data: {},
            date: '',
            target:'',
            target_event: undefined
        }
    },
    created() {
        this.$store.dispatch('page/setBackUrl', '/admin/calendar');
    },
    methods: {
        async getEvents(date) {
            try {
                if(date !== ''){
                    this.date = date;
                } else {
                    date = this.date;
                }
                this.$store.dispatch('loader/showSub');
                const url = this.$utils.getApiUrl(this.$apis.calendar, true);
                const result = await this.$axios.get(url, {params: {date: date}})
                this.events = result.data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.$store.dispatch('loader/hideSub');
            }
        },
        clickEvent(event) {
            this.company_ids = [];
            this.target = event.day.date;
            this.target_event = event.event;
            this.$refs.dialog.$refs.dialog.show();
        },
        clickDate(d) {
            let show = true;
            this.events.map(v => {
                if (this.$moment(v.start).isSame(this.$moment(d.date), 'date')) {
                    show = false;
                    return;
                }
            });
            if(show){
                console.log(d.date);
                this.target = d.date
                this.$refs.dialog.$refs.dialog.show()
            }
        }
    }
}
</script>

<style scoped lang="scss">
</style>
