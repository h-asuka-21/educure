<template>
    <div>
        <v-card
            class="mb-5"
        >
            <v-app-bar
                dark
                color="pink"
            >
                <v-toolbar-title>達成率ランキング</v-toolbar-title>
            </v-app-bar>
            <v-container fluid>
                <v-row dense>
                    <v-col>
                        <v-card color="#385F73">
                            <company-ranking-card
                                order="best"
                                title="ベスト"
                            />
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card color="#385F73">
                            <company-ranking-card
                                order="worst"
                                title="ワースト"
                            />
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
        <v-card
            class="mb-5"
        >
            <v-app-bar
                dark
                color="pink"
            >
                <v-toolbar-title>企業属性別達成数</v-toolbar-title>
            </v-app-bar>
            <v-container fluid>
                <v-row dense>
                    <v-col cols="3">
                        <v-card color="#385F73">
                            <stack-chart
                                :graph_data="company_industry"
                            />
                        </v-card>
                    </v-col>
                    <v-col cols="3">
                        <v-card color="#385F73">
                            <stack-chart
                                :graph_data="company_number_of_employees"
                            />
                        </v-card>
                    </v-col>
                    <v-col cols="3">
                        <v-card color="#385F73">
                            <stack-chart
                                :graph_data="company_year_of_establishment"
                            />
                        </v-card>
                    </v-col>
                    <v-col cols="3">
                        <v-card color="#385F73">
                            <stack-chart
                                :graph_data="company_average_age"
                            />
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
        <v-card
            class="mb-5"
        >
            <v-app-bar
                dark
                color="pink"
            >
                <v-toolbar-title>企業ステータス・受講者属性別達成数</v-toolbar-title>
                <v-row justify="end">
                    <v-col cols="4">
                        <autocomplete-with-api
                            width="300"
                            label="企業"
                            v-model="data.company_id"
                            :clearable="true"
                            :url="$utils.getApiUrl($apis.autocomplete.company,true)"
                            :dense="false"
                            :solo_inverted="true"
                            :hide_details="true"
                        ></autocomplete-with-api>
                    </v-col>
                    <v-col cols="1" class="mt-2">
                        <v-btn
                            color="pink lighten-2"
                            @click="getStudentData(true); status()"
                            dark
                        >
                            検索
                        </v-btn>
                    </v-col>
                </v-row>
            </v-app-bar>
            <v-container fluid>
                <v-row dense>
                    <v-col cols="6">
                        <v-card color="#385F73">
                            <stack-chart
                                :graph_data="by_attendance"
                            />
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-card color="#385F73">
                            <stack-chart
                                :graph_data="by_month"
                            />
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-card color="#385F73">
                            <achievement-chart
                                :graph_data="by_curriculum"
                            />
                        </v-card>
                    </v-col>
                </v-row>
                <v-card
                    outlined
                >
                    <v-toolbar
                        color="cyan"
                        dark
                        flat
                    >
                        <v-toolbar-title>受講者属性</v-toolbar-title>

                        <template v-slot:extension>
                            <v-tabs
                                v-model="model"
                                fixed-tabs
                            >
                                <v-tabs-slider color="yellow"></v-tabs-slider>

                                <v-tab href="#tab-1">受講開始年齢・性別・最終学歴</v-tab>
                                <v-tab href="#tab-2">資格・部活動経験</v-tab>
                            </v-tabs>
                        </template>
                    </v-toolbar>

                    <v-tabs-items v-model="model">
                        <v-tab-item value="tab-1">
                            <v-container fluid>
                                <v-row dense>
                                    <v-col cols="4">
                                        <v-card color="#385F73">
                                            <stack-chart
                                            :graph_data="start_age"
                                            />
                                        </v-card>
                                    </v-col>
                                    <v-col cols="4">
                                        <v-card color="#385F73">
                                            <stack-chart
                                            :graph_data="gender"
                                            />
                                        </v-card>
                                    </v-col>
                                    <v-col cols="4">
                                        <v-card color="#385F73">
                                            <stack-chart
                                            :graph_data="academic_type"
                                            />
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-tab-item>
                        <v-tab-item value="tab-2">
                            <v-container fluid>
                                <v-row dense>
                                    <v-col cols="4">
                                        <v-card color="#385F73">
                                            <stack-chart
                                            :graph_data="qualification_flg"
                                            />
                                        </v-card>
                                    </v-col>
                                    <v-col cols="4">
                                        <v-card color="#385F73">
                                            <stack-chart
                                            :graph_data="club_type"
                                            />
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-tab-item>
                    </v-tabs-items>
                </v-card>
            </v-container>
        </v-card>
    </div>
</template>

<script>
import CompanyRankingCard from "@/components/Statistics/CompanyRankingCard";
import AchievementChart from "@/components/Statistics/AchievementChart";
import StackChart from "@/components/Statistics/StackChart";
export default {
    name: "index",
    components: {StackChart, AchievementChart, CompanyRankingCard},
    data() {
        return {
            model: 'tab-1',
            by_attendance: {
                title: "受講日数",
                labels: ["-5日", "6-10日", "11-15日", "16-20日", "21-25日", "26-30日", "31-35日", "36-40日", "41-45日", "46-50日", "51-55日", "56-60日", "61-65日", "66-70日", "71日-" ],
                achievement_data: [],
                retire_data: [],
                xlabel: '受講日数'
            },
            by_month: {
                title: "月別",
                labels: [],
                achievement_data: [],
                retire_data: [],
                xlabel: '月'
            },
            by_curriculum: {
                title: "カリキュラム 辞退者数",
                labels: [],
                label: "辞退者数",
                color: "#F44336",
                data: [],
                xlabel: 'カリキュラム'
            },
            start_age: {
                title: "受講開始年齢",
                labels: ["18-20歳", "21-22歳", "23-24歳", "25-26歳", "27-28歳", "29-30歳", "30歳以降", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '年齢'
            },
            gender: {
                title: "性別",
                labels: ["男性", "女性", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '性別'
            },
            academic_type: {
                title: "最終学歴",
                labels: ["中卒", "高卒", "専門卒", "短大卒(文系)", "短大卒(理系)", "大卒(文系)", "大卒(理系)", "大院卒(文系)", "大院卒(理系)", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '最終学歴'
            },
            birthplace: {
                title: "出身地",
                labels: ["北海道", "東北", "関東", "中部", "近畿", "中国", "四国", "九州", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '出身地'
            },
            working_history: {
                title: "社会人歴",
                labels: ["0", "〜2", "〜4", "〜6", "〜8", "〜10", "11〜", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '年数'
            },
            former_job_type: {
                title: "前職（職種）",
                labels: ["通信販売業", "金融／保険業", "通信業", "流通／小売業", "運輸業", "旅行業", "電力／ガス／水道", "製造業", "サービス業", "医療／福祉", "建設／不動産業", "放送／出版／マスコミ", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '職種'
            },
            former_job_status: {
                title: "前職（雇用形態）",
                labels: ["正社員", "パート・アルバイト", "契約社員", "派遣社員", "個人事業主", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '雇用形態'
            },
            change_job_count: {
                title: "転職回数",
                labels: ["0", "1", "2", "3", "4", "5〜", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '回数'
            },
            national_qualification_flg: {
                title: "国家資格",
                labels: ["有", "無"],
                achievement_data: [],
                retire_data: [],
                xlabel: '国家資格'
            },
            qualification_flg: {
                title: "資格",
                labels: ["有", "無"],
                achievement_data: [],
                retire_data: [],
                xlabel: '資格'
            },
            club_type: {
                title: "部活動経験",
                labels: ["無", "運動部", "文化部"],
                achievement_data: [],
                retire_data: [],
                xlabel: '部活動経験'
            },
            company_industry: {
                title: "職種",
                labels: ["通信販売業", "金融／保険業", "通信業", "流通／小売業", "運輸業", "旅行業", "電力／ガス／水道", "製造業", "サービス業", "医療／福祉", "建設／不動産業", "放送／出版／マスコミ", "未回答"],
                achievement_data: [],
                retire_data: [],
                xlabel: '職種'
            },
            company_number_of_employees: {
                title: "従業員数",
                labels: ["-10", "-30", "-50", "-100", "-300", "-500", "-1000", "1001-", "未回答" ],
                achievement_data: [],
                retire_data: [],
                xlabel: '人数'
            },
            company_year_of_establishment: {
                title: "設立年数",
                labels: ["-3", "-5", "-10", "-20", "-30", "-50", "51-", "未回答" ],
                achievement_data: [],
                retire_data: [],
                xlabel: '年数'
            },
            company_average_age: {
                title: "平均年齢",
                labels: ["-25才", "26-30才", "31-35才", "36-40才", "41-45才", "46才-", "未回答" ],
                achievement_data: [],
                retire_data: [],
                xlabel: '年齢'
            },
            data: {},
        }
    },
    created() {
        this.$store.dispatch('page/sideBar', true);
        this.getStudentData();
        this.getCompanyData();
    },
    methods: {
        async getStudentData(search = false) {
            try {
                this.resetData();
                let params = {};
                if (search) {
                    params = this.data;
                }
                const result = await this.$axios.get(this.$utils.getApiUrl(this.$apis.student_statistics, true), {params: params});
                console.log(result);
                this.by_attendance.achievement_data = result.data.by_attendance.achievement_data;
                this.by_attendance.retire_data = result.data.by_attendance.retire_data;
                this.by_month.labels = result.data.by_month.months;
                this.by_month.achievement_data = result.data.by_month.achievement_data;
                this.by_month.retire_data = result.data.by_month.retire_data;
                this.by_curriculum.labels = result.data.by_curriculum.labels;
                this.by_curriculum.data = result.data.by_curriculum.retire_data;
                this.start_age.achievement_data = result.data.start_age.achievement_data;
                this.start_age.retire_data = result.data.start_age.retire_data;
                this.gender.achievement_data = result.data.gender.achievement_data;
                this.gender.retire_data = result.data.gender.retire_data;
                this.academic_type.achievement_data = result.data.academic_type.achievement_data;
                this.academic_type.retire_data = result.data.academic_type.retire_data;
                this.birthplace.achievement_data = result.data.birthplace.achievement_data;
                this.birthplace.retire_data = result.data.birthplace.retire_data;
                this.working_history.achievement_data = result.data.working_history.achievement_data;
                this.working_history.retire_data = result.data.working_history.retire_data;
                this.former_job_type.achievement_data = result.data.former_job_type.achievement_data;
                this.former_job_type.retire_data = result.data.former_job_type.retire_data;
                this.former_job_status.achievement_data = result.data.former_job_status.achievement_data;
                this.former_job_status.retire_data = result.data.former_job_status.retire_data;
                this.change_job_count.achievement_data = result.data.change_job_count.achievement_data;
                this.change_job_count.retire_data = result.data.change_job_count.retire_data;
                this.national_qualification_flg.achievement_data = result.data.national_qualification_flg.achievement_data;
                this.national_qualification_flg.retire_data = result.data.national_qualification_flg.retire_data;
                this.qualification_flg.achievement_data = result.data.qualification_flg.achievement_data;
                this.qualification_flg.retire_data = result.data.qualification_flg.retire_data;
                this.club_type.achievement_data = result.data.club_type.achievement_data;
                this.club_type.retire_data = result.data.club_type.retire_data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        resetData() {
            this.by_attendance.achievement_data = [];
            this.by_attendance.retire_data = [];
            this.by_month.achievement_data = [];
            this.by_month.retire_data = [];
            this.by_month.labels = [];
            this.by_curriculum.data = [];
            this.by_curriculum.labels = [];
            this.start_age.achievement_data = [];
            this.start_age.retire_data = [];
            this.gender.achievement_data = [];
            this.gender.retire_data = [];
            this.academic_type.achievement_data = [];
            this.academic_type.retire_data = [];
            this.birthplace.achievement_data = [];
            this.birthplace.retire_data = [];
            this.working_history.achievement_data = [];
            this.working_history.retire_data = [];
            this.former_job_type.achievement_data = [];
            this.former_job_type.retire_data = [];
            this.former_job_status.achievement_data = [];
            this.former_job_status.retire_data = [];
            this.change_job_count.achievement_data = [];
            this.change_job_count.retire_data = [];
            this.national_qualification_flg.achievement_data = [];
            this.national_qualification_flg.retire_data = [];
            this.qualification_flg.achievement_data = [];
            this.qualification_flg.retire_data = [];
            this.club_type.achievement_data = [];
            this.club_type.retire_data = [];
        },
        status() {
            let params = {};
            params = this.data;
            this.$refs.status.getData(params);
        },
        async getCompanyData() {
            try {
                const result = await this.$axios.get(this.$utils.getApiUrl(this.$apis.company_statistics, true));
                this.company_industry.achievement_data = result.data.company_industry.achievement_data;
                this.company_industry.retire_data = result.data.company_industry.retire_data;
                this.company_number_of_employees.achievement_data = result.data.company_number_of_employees.achievement_data;
                this.company_number_of_employees.retire_data = result.data.company_number_of_employees.retire_data;
                this.company_year_of_establishment.achievement_data = result.data.company_year_of_establishment.achievement_data;
                this.company_year_of_establishment.retire_data = result.data.company_year_of_establishment.retire_data;
                this.company_average_age.achievement_data = result.data.company_average_age.achievement_data;
                this.company_average_age.retire_data = result.data.company_average_age.retire_data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
    },
}
</script>

<style scoped>

</style>
