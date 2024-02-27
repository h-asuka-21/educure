<template>
    <v-card outlined class="fill-height">
        <div>
            <v-row dense>
                <v-col cols="12">
                    <div>
                        <v-container fluid>
                            <v-row dense justify="space-between" id="search">
                                <v-col cols="4">
                                    <select-input
                                        label="当日受講のみ"
                                        v-model="data.attendance_reserve_flg"
                                        :items="$selects.attendance_reserve_flg"
                                    ></select-input>
                                </v-col>
                                <v-col cols="4">
                                    <text-input
                                        label="氏名"
                                        v-model="data.name"
                                    >
                                    </text-input>
                                </v-col>
                                <v-col cols="4">
                                    <v-row class="form_to" dense>
                                        <date
                                            label="表示期間"
                                            v-model="start"
                                        ></date>
                                        <p class="pt-2">
                                            〜
                                        </p>
                                        <date v-model="end"></date>
                                    </v-row>
                                </v-col>
                                <v-col :cols="$utils.isAdmin() ? '3' : '4'">
                                    <select-input
                                        label="カリキュラム状況"
                                        v-model="data.after_graduation_flg"
                                        :items="$selects.after_graduation_flg"
                                        :clearable="true"
                                    ></select-input>
                                </v-col>
                                <v-col :cols="$utils.isAdmin() ? '3' : '4'">
                                    <autocomplete-with-api
                                        label="現在のカリキュラム"
                                        v-model="data.curriculum_id"
                                        :clearable="true"
                                        :url="curriculum_url"
                                    ></autocomplete-with-api>
                                </v-col>
                                <v-col :cols="$utils.isAdmin() ? '3' : '4'">
                                    <month
                                        label="開始月"
                                        v-model="data.start_month"
                                        :clearable="true"
                                    ></month>
                                </v-col>
                                <v-col cols="3" v-if="$utils.isAdmin()">
                                    <autocomplete-with-api
                                        label="企業"
                                        v-model="data.company_id"
                                        :clearable="true"
                                        :url="
                                            $utils.getApiUrl(
                                                $apis.autocomplete.company,
                                                true
                                            )
                                        "
                                    ></autocomplete-with-api>
                                </v-col>
                            </v-row>
                            <v-row justify="center">
                                <v-btn
                                    @click="getStudentProgresses(true, true)"
                                    :color="$colors.main"
                                    dark
                                >
                                    検索
                                </v-btn>
                            </v-row>
                        </v-container>
                    </div>
                </v-col>
            </v-row>
        </div>
        <div id="student_reports">
            <div>
                <div :class="loading ? 'loading' : ''">
                    <!-- 大枠 -->

                    <div class="headers">
                        <!-- 一行目 （108行目まで）-->
                        <div class="student attendance_flg"></div>
                        <div class="student start_date">開始日</div>
                        <div class="student name">
                            氏名{{
                                $utils.isAdmin() && !company_search
                                    ? "(企業名)"
                                    : ""
                            }}
                        </div>
                        <div class="student attendance_count">受講数</div>
                        <div
                            class="date reservations"
                            v-for="n of date_diff"
                            :key="n"
                            v-html="getDateHtml(n)"
                            :class="{
                                'red-border-header': isToday(
                                    $moment(start)
                                        .add(n - 1, 'day')
                                        .format('YYYY-MM-DD')
                                )
                            }"
                        ></div>
                    </div>

                    <v-skeleton-loader
                        tile
                        :style="loaderWidth"
                        type="image"
                        :loading="true"
                        v-if="loading"
                    >
                    </v-skeleton-loader>
                    <!-- 生徒毎の表示箇所itemをv-forで繰り返し表示している。（170行目）まで -->
                    <div
                        v-else
                        class="items"
                        v-for="(student, key) in students"
                        :key="key"
                    >
                        <div class="student attendance_flg">
                            <div v-if="!student.default_attendance">-</div>
                            <div v-else>受講済み</div>
                        </div>
                        <div class="student start_date">
                            {{
                                $moment(student.start_date).format(
                                    "YYYY年M月D日"
                                )
                            }}
                        </div>
                        <div class="student name">
                            <v-btn
                                text
                                color="light-blue"
                                @click="
                                    $utils.openNewTab(
                                        $utils.getHomeUrl() +
                                            '/student/' +
                                            student.id
                                    )
                                "
                            >
                                {{ student.name }}
                                {{
                                    $utils.isAdmin() && !company_search
                                        ? "(" + student.company_name + ")"
                                        : ""
                                }}
                            </v-btn>
                        </div>
                        <div class="student attendance_count">
                            {{ student.attendance_count }}
                        </div>
                        <reservations
                            v-for="n of date_diff"
                            :key="n"
                            :student="student"
                            :date="
                                $moment(start)
                                    .add(n - 1, 'day')
                                    .format('YYYY-MM-DD')
                            "
                            :class="{
                                'red-border': isToday(
                                    $moment(start)
                                        .add(n - 1, 'day')
                                        .format('YYYY-MM-DD')
                                )
                            }"
                        ></reservations>
                    </div>
                    <!-- ここまで -->
                </div>
                <div
                    v-if="showInfinite"
                    id="infinite_loader"
                    :style="loaderWidth"
                >
                    <infinite-loading
                        ref="infinite_loader"
                        @infinite="infiniteHandler"
                        spinner="spiral"
                    >
                        <div slot="spinner">
                            <v-skeleton-loader
                                height="30"
                                tile
                                type="image"
                                :loading="true"
                            />
                        </div>
                    </infinite-loading>
                </div>
            </div>
        </div>
    </v-card>
</template>

<script>
import Date from "~/components/Molecules/Forms/Date";
import SelectInput from "~/components/Molecules/Forms/SelectInput";
import TextInput from "~/components/Molecules/Forms/TextInput";
import Month from "~/components/Molecules/Forms/Month";
import Reservations from "~/components/Progress/ProgressDialog/Reservations";
import AutocompleteWithApi from "~/components/Molecules/Forms/AutocompleteWithApi";

export default {
    name: "StudentProgressList",
    components: {
        AutocompleteWithApi,
        Reservations,
        Month,
        TextInput,
        SelectInput,
        Date
    },
    created() {
        this.offset = 0;
        this.setFromTo();
        this.getStudentProgresses(true, true);
        if (this.$utils.isUser()) {
            this.curriculum_url = this.$utils.getApiUrl(
                this.$apis.autocomplete.curriculum_group,
                true
            );
        } else if (this.$utils.isAdmin()) {
            this.curriculum_url = this.$utils.getApiUrl(
                this.$apis.autocomplete.curriculum,
                true
            );
        }
    },
    mounted() {
        // ページが読み込まれた際に実行される。
        this.setWidth();
        this.checkCurrentDate();
    },
    data() {
        return {
            data: {
                attendance_reserve_flg: null,
                name: null,
                after_graduation_flg: 0,
                start_month: null
            },
            students: [],
            start: null,
            end: null,
            date_diff: 0,
            loading: false,
            item: {},
            attendances: {},
            company_search: false,
            offset: 0,
            showInfinite: false,
            loaderWidth: "",
            infiniteLoaderWidth: "",
            curriculum_url: "",
            today: ""
        };
    },
    watch: {
        date_diff() {
            this.setWidth();
        }
    },
    methods: {
        setFromTo() {
            const start = this.$moment().subtract(1, "week");
            const end = this.$moment();
            this.date_diff = end.diff(start, "days") + 1;
            this.start = start.format("YYYY-MM-DD");
            this.end = end.format("YYYY-MM-DD");
        },
        isToday(date) {
            const today = this.$moment().format("YYYY-MM-DD");
            return today === date; // dateが本日の日付ならtrue、そうでなければfalseを返す
        },
        getDateHtml(n) {
            const add = n - 1;
            const day = this.$moment(this.start).add("day", add);
            let result = '<p class="week_' + day.format("d") + '">';
            if (n === 1 || day.date() === 1) {
                result += day.format("YYYY年M月") + "<br>";
            }
            result +=
                "" +
                day.format("D日") +
                "(" +
                this.$utils.getWeekStr(day) +
                ")</span></p>";
            return result;
        },
        setWidth() {
            const width = 90 * 4 + 130 * this.date_diff;
            this.loaderWidth = "width:" + width + "px;";
        },
        async getStudentProgresses(search = false, reset = false) {
            try {
                let params = {};
                if (search) {
                    this.setDateDiff();
                    params = this.data;
                }
                if (reset) {
                    this.offset = 0;
                    this.showInfinite = false;
                }
                params.offset = this.offset;
                this.company_search = false;
                if (this.data.company_id) {
                    this.company_search = true;
                }
                params.start = this.start;
                params.end = this.end;
                if (this.offset === 0) {
                    this.loading = true;
                }
                const result = await this.$axios(
                    this.$utils.getApiUrl(
                        this.$apis.progress_students,
                        true,
                        true
                    ),
                    { params: params }
                );
                console.log(result);
                if (this.offset === 0) {
                    // 初期表示または検索実行時
                    this.students = result.data;
                    this.showInfinite = true;
                } else {
                    result.data.map(item => {
                        this.students.push(item);
                    });
                    if (this.$refs.infinite_loader) {
                        this.$refs.infinite_loader.stateChanger.loaded();
                    }
                }
                this.showInfinite = result.data.length >= 15;
                if (params.curriculum_id) {
                    this.showInfinite = false;
                }
            } catch (e) {
                this.students = [];
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        setDateDiff() {
            const start = this.$moment(this.start);
            const end = this.$moment(this.end);
            this.date_diff = end.diff(start, "days") + 1;
        },
        infiniteHandler($state) {
            this.offset++;
            this.getStudentProgresses(true);
        },
        checkCurrentDate() {
            // 現在日時を取得している。
            const currentDate = this.$moment;
        }
    }
};
</script>

<style lang="scss">
.form_to {
    flex-wrap: nowrap;
}
.red-border-header {
    border-top: 2px solid red !important;
    border-left: 2px solid red !important;
    border-right: 2px solid red !important;
}
.red-border {
    border-left: 2px solid red !important;
    border-right: 2px solid red !important;
}
#search {
    .v-text-field__details {
        display: none !important;
    }
}
#student_reports {
    padding: 10px;
    > div {
        overflow: scroll;
        max-height: 59vh;
        background-color: #fff;
        padding: 0;
        border-bottom: 1px solid #9e9e9e;
        border-right: 1px solid #9e9e9e;
        > div {
            display: table;
            &.loading {
                display: block !important;
            }
            > div {
                display: table-row;
                &.v-skeleton-loader {
                    display: block;
                    height: 90px;
                    width: 100%;
                    > div {
                        display: block;
                        min-width: 100%;
                    }
                }

                &:nth-child(2) {
                    > div {
                        border-top: none;
                    }
                }
                > div {
                    display: table-cell;
                    min-width: 90px;
                    max-width: 90px;
                    min-height: 100px;
                    vertical-align: middle;
                    text-align: center;
                    border-top: 1px solid #9e9e9e;
                    border-left: 1px solid #9e9e9e;
                    &.student {
                        position: sticky;
                        z-index: 4 !important;
                        &.attendance_flg {
                            left: 0;
                        }

                        &.start_date {
                            left: 90.05px;
                            font-size: 0.7rem;
                        }

                        &.name {
                            font-size: 0.8rem;
                            left: 180.05px;
                            > button {
                                width: 100%;
                                min-width: 90px;
                                max-width: 90px;
                                height: 89px;
                                min-height: 100%;
                                display: block;
                                padding: 0;
                                > span {
                                    height: 100%;
                                    display: inline;
                                    white-space: initial;
                                    word-break: break-all;
                                    font-size: 0.6rem;
                                    overflow: hidden;
                                    padding-bottom: 7px;
                                }
                            }
                        }

                        &.attendance_count {
                            left: 270.05px;
                        }
                    }
                }
                &.headers {
                    div {
                        z-index: 3;
                        position: sticky;
                        top: 0;
                        background-color: #fff;
                        border-bottom: 1px solid #9e9e9e;
                        height: 40px !important;
                        font-size: 0.8rem;

                        &.student {
                            background-color: #00bcd4;
                            color: #fff;
                            &.attendance_flg {
                                .v-btn {
                                    max-height: 20px;
                                    padding: 0 5px;
                                    > span {
                                        font-size: 0.8rem;
                                    }
                                }
                            }
                            &:nth-child(4n) {
                                border-right: 1px solid #9e9e9e;
                            }
                        }
                        &.date {
                            &:first-of-type {
                                border-left: none !important;
                            }
                            p {
                                margin-bottom: 0 !important;
                                &.week_0 {
                                    color: #ff5722;
                                }
                            }
                        }
                    }
                }
                &.items {
                    > div {
                        &.student {
                            z-index: 2 !important;
                            background-color: #fff;
                            &:nth-child(4n) {
                                border-right: 1px solid #9e9e9e;
                            }
                            &.attendance_flg {
                                min-height: 100px;
                                justify-content: center;
                                align-items: center;
                                > .v-input {
                                    padding-left: 3.3vw;
                                }
                                > div {
                                    font-size: 0.8rem;
                                }
                            }
                        }
                    }
                }
                &.items,
                &.headers {
                    text-align: center;
                    vertical-align: middle;
                    width: 100px;

                    min-width: 100px;
                    background-color: #fff;
                }
            }
        }
        #infinite_loader {
            border-top: 1px solid #9e9e9e;
            display: block;
            > .infinite-loading-container {
                max-height: 100px;
                display: block;
                > .infinite-status-prompt {
                    height: 30px;
                    max-height: 30px;
                    min-height: 30px;
                    width: 100%;
                    min-width: 100%;
                    display: block;
                    border-top: none;
                }
            }
        }
    }
}
</style>
