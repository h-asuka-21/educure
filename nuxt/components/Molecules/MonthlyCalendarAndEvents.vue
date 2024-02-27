<template>
    <div id="calendar_and_nav">
        <v-row class="nav" justify="space-between">
            <v-btn
                icon
                @click="changeMonth()"
            >
                <v-icon>mdi-skip-previous-outline</v-icon>
            </v-btn>
            <v-menu
                ref="menu"
                v-model="dp"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        text
                        @click="dp=!dp"
                    >
                        {{ current.format('YYYY年M月') }}
                    </v-btn>
                </template>
                <v-date-picker v-model="date_string" type="month"></v-date-picker>
            </v-menu>
            <v-btn
                icon
                @click="changeMonth(true)"
            >
                <v-icon>mdi-skip-next-outline</v-icon>
            </v-btn>
        </v-row>
        <v-calendar
            id="calendar"
            type="month"
            :start="current.startOf('month').format('YYYY-MM-DD')"
            :events="events"
            :event-color="v => v.color"
            :event-height="eventHeight"
            @click:event="v => $emit('click_event',v)"
            @click:date="v => $emit('click_date',v)"
            @click:more="v => $emit('click_more',v)"
        >
            <template
                v-slot:event="{event}"
            >
                <slot v-bind:event="event"/>
            </template>
        </v-calendar>
    </div>
</template>

<script>
export default {
    name: "MonthlyCalendarAndEvents",
    data() {
        return {
            current: null,
            date_string: '',
            dp: false,
            events: this.value
        }
    },
    props: {
        value: {
            default: []
        },
        eventHeight: {
            default: 60,
            type: Number
        }
    },
    created() {
        this.current = this.$moment();
        this.date_string = this.current.format('YYYY-MM-DD');
        this.$emit('get_date', this.date_string)
    },
    watch: {
        date_string(val) {
            this.current = this.$moment(val);
        },
        value(val) {
            this.events = val;
        }
    },
    methods: {
        changeMonth(add = false) {
            const new_month = this.$moment(this.current.format('YYYY-MM-DD'));
            if (add) {
                new_month.add(1, 'month')
            } else {
                new_month.subtract(1, 'month');
            }
            this.current = new_month;
            this.date_string = new_month.format('YYYY-MM-DD');
            this.$emit('get_date', this.date_string)
        },
    }
}
</script>

<style lang="scss">
#calendar_and_nav {
    .nav {
        padding: 0 10px;
    }

    #calendar {
        min-height: 600px;
    }
}

#calendar {
    .event_title {
        font-size: 0.8rem;
    }

    .reserved {
        font-size: 0.65rem;
        line-height: 0.67rem;
    }

    .event_time {

    }

    .v-calendar-weekly__week {
        min-height: 140px;
    }
}
</style>
