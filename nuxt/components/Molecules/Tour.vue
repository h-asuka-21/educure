<template>
    <div>
        <v-tour name="myTour" :steps="steps" :callbacks="callbacks" :options="myOptions">
            <template slot-scope="tour">
                <transition name="fade">
                    <v-step
                        v-if="tour.currentStep === index"
                        v-for="(step, index) of tour.steps"
                        :key="index"
                        :step="step"
                        :previous-step="tour.previousStep"
                        :next-step="tour.nextStep"
                        :stop="tour.stop"
                        :isFirst="tour.isFirst"
                        :isLast="tour.isLast"
                        :labels="tour.labels"
                    >
                    </v-step>
                </transition>
            </template>
        </v-tour>
    </div>
</template>

<script>
export default {
    name: 'my-tour',
    data () {
        return {
            myOptions: {
                useKeyboardNavigation: false,
                labels: {
                    buttonSkip: 'チュートリアル終了',
                    buttonPrevious: '戻る',
                    buttonNext: '次へ',
                    buttonStop: '終了'
                }
            },
            steps: [
                {
                    target: '#v-step-0',
                    header: {
                        title: 'educureへようこそ'
                    },
                    content: `チュートリアルを開始します。<br>システム利用の初期設定、受講手順を説明します。<br>案内に従って操作して下さい。<br>全部で3stepあります。<br><br>※このチュートリアルは初回ログイン時もしくはプロフィール未入力項目がある方に表示されます。`,
                    params: {
                        placement: 'auto'
                    },
                },
                {
                    target: '#v-step-1',
                    header: {
                        title: '【step.1】プロフィール更新'
                    },
                    content: '左のサイドバーを開いて<br>下のご自身の名前をクリックすると<br>編集画面が表示されます。<br>1.情報を入力<br>2.パスワード変更<br>を行ってください。',
                    params: {
                        placement: 'right',
                    },
                },
                {
                    target: '#v-step-2',
                    header: {
                        title: '【step.2】受講'
                    },
                    content: '受講開始、進捗入力、日報の提出はこちらからお願いします。',
                    params: {
                        placement: 'auto'
                    },
                },
                {
                    target: '#v-step-3',
                    header: {
                        title: '【step.3】課題のダウンロード'
                    },
                    content: '各カリキュラムで使用する課題をダウンロードできます。',
                    params: {
                        placement: 'auto'
                    },
                },
                {
                    target: '#v-step-4',
                    header: {
                        title: 'チュートリアル終了'
                    },
                    content: '以上でチュートリアルを終了します。',
                    params: {
                        placement: 'auto'
                    },
                }
            ],
            callbacks: {
                onPreviousStep: this.myCustomPreviousStepCallback,
                onNextStep: this.myCustomNextStepCallback
            }
        }
    },
    mounted: function () {
        if (this.inputAttributeCheck()) {
            this.$tours['myTour'].start()
            // A dynamically added onStop callback
            this.callbacks.onStop = () => {
                document.querySelector('#v-step-0').scrollIntoView({behavior: 'smooth'})
            }
        }
    },
    methods: {
        inputAttributeCheck() {
            if (this.$store.state.student.data.birthday === null ||
                this.$store.state.student.data.academic_type === null ||
                this.$store.state.student.data.birthplace === null) {
                return true;
            }
            return false;
        },
        nextStep () {
            this.$tours['myTour'].nextStep()
        },
        showLastStep () {
            this.$tours['myTour'].currentStep = this.steps.length - 1
        },
        myCustomPreviousStepCallback (currentStep) {
            console.log('[Vue Tour] A custom previousStep callback has been called on step ' + (currentStep + 1))
        },
        myCustomNextStepCallback (currentStep) {
            console.log('[Vue Tour] A custom nextStep callback has been called on step ' + (currentStep + 1))

            if (currentStep === 1) {
                console.log('[Vue Tour] A custom nextStep callback has been called from step 2 to step 3')
            }
        }
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}

.v-step {
    background: #4DB6AC; /* #ffc107, #35495e */
    max-width: 500px;
}

</style>
