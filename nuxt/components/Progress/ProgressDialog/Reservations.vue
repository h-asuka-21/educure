<template>
    <div
        :class="'reservations ' + status_class"
        @click="$emit('click', student, reservation, date)"
    >
        <div>
            <p class="attendance">{{ getAttendanceStatus() }}</p>
            <p class="report_status">{{ getReportStatus() }}</p>
            <div
                class="progresses"
                v-if="
                    reservation.progresses !== undefined &&
                        reservation.progresses.length > 0
                "
            >
                <p
                    v-for="i in 2"
                    :key="i"
                    v-if="reservation.progresses[i - 1] !== undefined"
                    :class="
                        stepColor(reservation.progresses[i - 1].application_flg)
                    "
                >
                    {{ reservation.progresses[i - 1].name }}
                    <span
                        v-if="
                            reservation.progresses[i - 1].progress_status ===
                                3 &&
                                reservation.progresses[i - 1]
                                    .application_flg === 0
                        "
                        >完</span
                    >
                </p>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <p
                            v-if="reservation.progresses.length > 2"
                            v-bind="attrs"
                            v-on="on"
                            class="overline"
                        >
                            もっと見る
                        </p>
                    </template>
                    <div>
                        <p
                            class="mb-0 overline"
                            v-for="(item, key) in reservation.progresses"
                            :key="key"
                            :class="stepColor(item.application_flg)"
                        >
                            {{ item.name }}
                            <span v-if="item.progress_status === 3">完</span>
                        </p>
                    </div>
                </v-tooltip>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Reservations",
    props: {
        student: {
            type: Object,
            required: true
        },
        date: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            data: {},
            reservation: {},
            progresses: [],
            status_class: ""
        };
    },
    created() {
        this.setData();
    },
    methods: {
        setData() {
            this.student.reservations.map(v => {
                if (v.date === this.date) {
                    this.reservation = v;
                }
            });
            if (this.reservation.id !== undefined) {
                this.status_class = "reserved";
            }
        },
        getProgressString(v) {
            console.log(v);
            return "";
        },
        getAttendanceStatus() {
            if (this.reservation.id === undefined) {
                return "";
            } else if (this.reservation.attendance_flg === 1) {
                return "受講";
            }
            return "";
        },
        getReportStatus() {
            if (this.reservation.id === undefined) {
                return "";
            } else if (
                this.reservation.attendance_flg === 1 &&
                this.reservation.reports.length === 0
            ) {
                return "日報未提出";
            }
            return "";
        },
        isShow(v, a) {
            console.log(v);
        },
        stepColor(application_flg) {
            if (application_flg === 1) {
                return "application";
            }
        }
    }
};
</script>
<style lang="scss">
.headers {
    > .reservations {
        background-color: #fff !important;
        vertical-align: middle;
        text-align: center;
    }
}

.reservations {
    width: 130px;
    min-width: 130px !important;
    max-width: 130px !important;
    background-color: #eeeeee !important;
    vertical-align: top !important;

    &.reserved {
        background-color: #fff !important;
    }

    &:hover {
        background-color: #f0f4c3 !important;
    }

    p {
        color: #757575;
        font-size: 0.7rem;
        text-align: left;
        margin-bottom: 0;
        padding-left: 2px;
        padding-top: 4px;
        font-weight: bold;

        &.report_status {
            color: #00bcd4;
        }
        &.overline {
            line-height: 10px;
        }

        &.application {
            color: #ff0000 !important;
        }
    }

    .progresses {
        p {
            padding-top: 1px;
            display: flex;
            justify-content: space-between;
            padding-right: 10px;
        }
    }
}
</style>
