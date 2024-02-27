<template>
    <div class="fill-height">
        <v-btn dark id="bulk_add" :color="$colors.main" @click="showBulk">一括登録</v-btn>
        <monthly-calendar-and-events
            v-model="events"
            @get_date="getEvents"
            @click_event="clickEvent"
            @click_date="clickDate"
            ref="calender"
            :event-height="admin?70:105"
        >
            <template v-if="admin" v-slot:default="{event}">
                <div class="company_name ml-1 mt-1">{{ event.company_name }}</div>
                <div class="event_title ml-1 mt-1">{{ event.name }}</div>
                <div class="event_time ml-1">{{ $moment(event.start).format('HH:mm') }} -
                    {{ $moment(event.end).format('HH:mm') }}
                </div>
            </template>
            <template v-else v-slot:default="{event}">
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
            :admin="admin"
            @reload="getEvents(date)"
            :company_ids="company_ids"
            :item_data="target_event"
            :create_flg="create_flg"/>
        <bulk-dialog
            @reload="getEvents(date)"
            ref="bulk"
            :admin="admin"/>
    </div>
</template>

<script>
import MonthlyCalendarAndEvents from "~/components/Molecules/MonthlyCalendarAndEvents";
import ScheduleDialog from "~/components/Schedule/ScheduleDialog";

export default {
    name: "ScheduleCalendar",
    components: {ScheduleDialog, MonthlyCalendarAndEvents},
    data() {
        return {
            events: [],
            data: {},
            date: '',
            target:'',
            target_event: undefined,
            company_ids: [],
            create_flg: false
        }
    },
    props:{
        admin:{
            type: Boolean,
            default:false
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
                console.log(this.events);
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
            this.create_flg = false;
            this.$refs.dialog.$refs.dialog.show();
        },
        clickDate(d) {
            let show = true;
            this.company_ids = [];
            this.target_event = undefined;
            if(this.admin){
                this.events.map(v => {
                    if (this.$moment(v.start).isSame(this.$moment(d.date), 'date')) {
                        this.company_ids.push(v.company_id);
                    }
                });
                if(this.events.length !== 0 && (this.events.length === this.company_ids.length)){
                    show = false;
                }
            } else {
                this.events.map(v => {
                    if (this.$moment(v.start).isSame(this.$moment(d.date), 'date')) {
                        show = false;
                        return;
                    }
                });
            }
            if(show){
                this.create_flg = true;
                this.target = d.date;
                console.log(d.date);
                this.$refs.dialog.$refs.dialog.show()
            }
        },
        showBulk(){
            this.$refs.bulk.show(this.date);
        }
    }
}
</script>
<style lang="scss" scoped>
#bulk_add {
    position: absolute;
    top: 38px;
    right: 56px;
}
</style>
