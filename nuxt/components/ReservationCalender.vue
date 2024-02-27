<template>
    <div class="fill-height">
        <monthly-calendar-and-events
            v-model="events"
            @get_date="getEvents"
            @click_event="showDialog"
            ref="calender"
        >
            <template v-slot:default="{event}">
                <div class="event_title ml-1 mt-1">{{getTitle(event)}}</div>
                <div class="ml-1 mt-1 reserved" v-if="event.reservation_id">
                    <div>予約時間</div>
                    <div>{{event.reserve_start ? $moment().set('hour',event.reserve_start.split(':')[0]).set('minute',event.reserve_start.split(':')[1]).format('HH:mm'): ''}}
                        - {{event.reserve_end ? $moment().set('hour',event.reserve_end.split(':')[0]).set('minute',event.reserve_end.split(':')[1]).format('HH:mm'):''}}</div>
                </div>
                <div v-else class="event_time ml-1">{{$moment(event.start).format('HH:mm')}} - {{$moment(event.end).format('HH:mm')}}</div>
            </template>
        </monthly-calendar-and-events>
        <reserve-dialog
            @reload="getEvents($refs.calender.date_string)"
            ref="dialog"
            v-model="data"
        ></reserve-dialog>
    </div>
</template>

<script>
import MonthlyCalendarAndEvents from "@/components/Molecules/MonthlyCalendarAndEvents";
import ReserveDialog from "@/components/Schedule/ReserveDialog";
export default {
    name: "ReservationCalendar",
    components: {ReserveDialog, MonthlyCalendarAndEvents},
    data(){
        return {
            events:[],
            data: {},
            date:''
        }
    },
    methods:{
        setDate(date){
            this.date = date;
        },
        async getEvents(date){
            this.$store.dispatch('loader/showSub');
            try{
                this.$emit('loading',true);
                const url = this.$utils.getApiUrl(this.$apis.schedule, true);
                const result = await this.$axios.get(url,{params:{date:date}})
                this.events = result.data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.$emit('loading',false);
                this.$store.dispatch('loader/hideSub');
            }
        },
        showDialog(event){
            console.log(event.event);
            this.data = event.event;
            this.$refs.dialog.$refs.dialog.show();
        },
        check(event){
            console.log(event);
        },
        getTitle(event){
            if(event.reservation_id){
                if(event.attendance_flg){
                    return '受講済み';
                }
                return '予約済み';
            }
            return event.name;
        }
    }
}
</script>

<style scoped lang="scss">
</style>
