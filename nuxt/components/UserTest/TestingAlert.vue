<template>
    <div>
        <v-scale-transition>
            <v-speed-dial class="testing_alert" bottom right v-if="show && data">
                <template v-slot:activator>
                    <v-hover v-slot:default="{ hover }">
                        <v-fade-transition>
                            <v-card
                                dark
                                :color="color"
                                @click="$router.push(test_url)"
                            >
                                <v-card-title class="py-1 px-2">
                                    <v-icon>mdi-pencil</v-icon>
                                    {{title}}
                                </v-card-title>
                                <v-expand-transition>
                                    <div v-if="hover && time >= 0 && time !== null">
                                        <v-card-subtitle>{{data.test.name}}<br>
                                            <v-icon x-small>mdi-timer</v-icon>{{ getTimeString(time)}}
                                        </v-card-subtitle>
                                    </div>
                                </v-expand-transition>
                            </v-card>
                        </v-fade-transition>
                    </v-hover>
                    <v-fade-transition>
                        <v-card
                            v-if=""
                            dark
                            color=""
                        >
                        </v-card>
                    </v-fade-transition>
                </template>
            </v-speed-dial>
        </v-scale-transition>
        <result-dialog
            v-if="data"
            ref="result"
            :score="score"
            :questions="data.questions.length"
            @confirm="finish"/>
    </div>
</template>

<script>
import ResultDialog from "@/components/UserTest/AnswerForm/ResultDialog";
export default {
    name: "TestingAlert",
    components: {ResultDialog},
    data() {
        return {
            data: null,
            test_url: null,
            start_time: null,
            time: null,
            time_interval: {},
            score: 0,
            show: false,
            color:'blue darken-2',
            title:'テストに戻る'
        }
    },
    created() {
        this.test_url = this.$store.state.test.test_url;
        if(this.$route.path.indexOf('/test') >= 0){
            this.show = false;
            return;
        }
        if(this.$store.state.test.data){
            this.data = this.$store.state.test.data;
            if(!this.data.questions){
                this.data.questions = {}
            }
            if(!this.data.test){
                this.data.test = {};
            }
        }
        this.start_time = this.$store.state.test.start_time;
        if (this.data && this.data.test.test_time) {
            this.time = (this.data.test.test_time * 60) - this.$moment().diff(this.$moment(this.start_time), 'seconds');
        } else {
            // 時間がないとき
            this.show = false;
            return;
        }
        this.show = true;
    },
    watch: {
        "$route"(v) {
            if(v.path.indexOf('/test') >= 0){
                this.show = false;
            }
        },
    },
    beforeDestroy() {
        clearInterval(this.time_interval)
    },
    mounted() {
        if (this.time !== null) {
            this.time_interval = setInterval(() => {
                if (this.time <= 0) {
                    this.time = null;
                    this.color = 'orange darken-1'
                    this.title  ='テスト終了'
                    clearInterval(this.time_interval)
                    this.submit()
                    return
                }
                this.time--
            }, 1000);
        }
    },
    methods: {
        getTimeString(time) {
            if (time <= 0) {
                return '終了';
            }
            const min = Math.floor(time / 60);
            const sec = time % 60
            return `${min}分${sec}秒`;
        },
        async submit(){
            if(!this.show){
                return;
            }
            try {
                const url = this.$utils.getApiUrl(this.$apis.score, true, true) + '/' + this.$store.state.test.score_id;
                const result = await this.$axios.put(url, this.data);
                console.log(result);
                this.score = await result.data.score;
                await this.$refs.result.show();
                this.$store.dispatch('test/clear');
                this.show = false;
            } catch (e) {
                this.$utils.catchError(e);
                this.end = false;
            } finally {
                clearInterval(this.time_interval);
            }
        },
        finish(){
            this.data = null;
            this.$refs.result.$refs.dialog.hide();
        }
    }
}
</script>

<style scoped>
.testing_alert {
    position: fixed;
}
</style>
