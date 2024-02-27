<template>
    <div>
        <div>
            <v-scale-transition>
                <v-card outlined v-if="data.test && started && !end">
                    <v-card-title>
                        {{ data.test.name }}
                        <v-spacer>
                        </v-spacer>
                        全{{ data.questions.length }}問
                    </v-card-title>
                    <v-card-subtitle>
                        制限時間 {{ data.test.test_time }}分
                    </v-card-subtitle>
                </v-card>
            </v-scale-transition>
            <v-scale-transition>
                <timer
                    ref="timer"
                    v-if="time && started && !end"
                    class="mb-4"
                    :test_time="time"
                    :max="this.data.test.test_time"
                    @end="save"
                ></timer>
            </v-scale-transition>
            <v-scale-transition>
                <div class="mb-2" v-if="data.test && started&& !end">
                    <questions
                        :questions="data.questions"
                    ></questions>
                </div>
            </v-scale-transition>
            <v-scale-transition>
                <v-row justify="center" class="mb-2" v-if="data.test && started&& !end">
                    <v-btn
                        color="info"
                        dark
                        id="save"
                        @click="save">
                        回答する
                    </v-btn>
                </v-row>
            </v-scale-transition>
            <result-dialog
                v-if="data.test"
                ref="result"
                :score="score"
                :questions="data.questions.length"
                @confirm="finish"/>
        </div>
        <start-confirm
            ref="start_confirm"
            @confirm="preSave()"
            @ng="$router.back()"
            :data="data"
        />
    </div>
</template>

<script>
import Questions from "./AnswerForm/Questions";
import Timer from "./AnswerForm/Timer";
import ResultDialog from "./AnswerForm/ResultDialog";
import ConfirmDialog from "@/components/Molecules/ConfirmDialog";
import StartConfirm from "@/components/UserTest/AnswerForm/StartConfirm";

export default {
    name: "AnswerForm",
    components: {StartConfirm, ConfirmDialog, Questions, Timer, ResultDialog},
    props: {
        loading: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        show: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            data: {},
            started: this.show,
            score: 0,
            questions: 0,
            end: false,
            score_id: null,
            start_time: null,
            cookie_opts: {
                path: '/',
                // 30分
                maxAge: 60 * 30
            },
            time: undefined
        }
    },
    watch: {
        value(val) {
            this.data = val;
        },
    },
    created() {
        // クッキーからスコアIDを取得
        this.score_id = this.$store.state.test.score_id;
        this.start_time = this.$store.state.test.start_time;
        if(this.$store.state.test.data){
            this.data = JSON.parse(JSON.stringify(this.$store.state.test.data));
        } else {
            this.data = {};
        }
        this.getDetail();
    },
    methods: {
        async getDetail() {
            try {
                this.$store.dispatch('loader/showSub');
                const is_continue = await this.isContinue();
                if (is_continue) {
                    // 超過していなければコンティニューとする。
                    const now = this.$moment();
                    this.time = (this.data.test.test_time * 60) - now.diff(this.$moment(this.start_time), 'seconds');
                    this.started = true;
                } else {
                    // テスト開始時
                    const url = this.$utils.getApiUrl(this.$apis.test, true);
                    const res = await this.$axios.get(url);
                    this.data = res.data;
                    this.time = this.data.test.test_time * 60;
                    this.$refs.start_confirm.show();
                }
            } catch (e) {
                this.$store.dispatch('test/clear');
                this.$utils.catchError(e);
                this.$emit('error');
            } finally {
                this.$store.dispatch('loader/hideSub');
            }
        },
        // テスト開始時の回答データの作成
        async preSave() {
            try {
                this.$store.dispatch('loader/showSub');
                const result = await this.$axios.post(this.$utils.getApiUrl('/student' + this.$apis.score, false, true), this.data);
                this.start(result);
            } catch (e) {
                this.$store.dispatch('test/clear');
                this.$utils.catchError(e);
                location.reload();
            } finally {
                this.$store.dispatch('loader/hideSub');
            }
        },
        // テスト終了時のPOST
        async save() {
            this.$store.dispatch('loader/showSub');
            this.end = true;
            this.data = this.$store.state.test.data;
            try {
                const url = this.$utils.getApiUrl(this.$apis.score, true, true) + '/' + this.score_id;
                const result = await this.$axios.put(url, this.data);
                this.score = result.data.score;
                this.$refs.result.show();
                this.$store.dispatch('test/clear');
            } catch (e) {
                this.$utils.catchError(e);
                // エラー時は回答データなどをステートから削除せずに、終わる
                this.end = false;
            } finally {
                this.$store.dispatch('loader/hideSub');
            }
        },
        start(ret) {
            // 回答IDをdataとクッキーにセット
            this.score_id = ret.data.id;
            this.$store.dispatch('test/score_id', this.score_id);
            // テスト開始時刻をセット
            this.start_time = this.$moment(ret.data.created_at).format('YYYY-MM-DD HH:mm:ss');
            // テストデータをstoreにセット
            this.$store.dispatch('test/data', this.data);
            this.$store.dispatch('test/test_url', this.$route.path);
            this.$store.dispatch('test/start_time',this.start_time );
            this.$utils.success({data: {message: '試験開始！'}});
            this.$emit('start');
            this.started = true;
        },
        finish() {
            this.$emit('finish');
            this.$router.push('/')
        },
        async isContinue() {
            if (!this.score_id || !this.start_time || !this.data.test) {
                // スコアIDと開始時間が設定されていない場合
                return false;
            }
            if(this.$moment().diff(this.$moment(this.start_time), 'minute') > this.data.test.test_time){
                // テスト時間を超過していた場合回答をPOSTしてテスト終了。
                await this.save();
                return false;
            }
            return true;
        },
    }
};
</script>

<style scoped>

</style>
