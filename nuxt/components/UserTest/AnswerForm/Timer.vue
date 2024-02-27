<template>
    <div id="timer">
        <v-progress-circular
            :rotate="-90"
            :size="100"
            :width="15"
            :value="progress"
            :color="getColor(progress)"
        >
            {{ getTimeString(time) }}
        </v-progress-circular>
    </div>
</template>

<script>
export default {
    props: {
        test_time: {
            type: Number,
        },
        max:{
            type:Number,
        }
    },
    data() {
        return {
            time: this.test_time,
            progress:0,
            timeout: 0,
            paused: true,
            show_button : true,
            show_dialog : false,
            interval: {},
            time_interval: {}
        };
    },

    beforeDestroy () {
        clearInterval(this.interval)
        clearInterval(this.time_interval)
    },
    mounted() {
        const timeout = (this.max * 60) / 100;
        console.log(this.test_time);
        console.log(this.max * 60);
        this.progress = (1 - (this.test_time / (this.max * 60))) * 100;
        console.log(this.progress);
        this.interval = setInterval(() => {
            if (this.progress >= 100) {
                return (this.progress = 100);
            }
            this.progress += 1
            console.log(this.progress);
        }, (timeout * 1000) );
        this.time_interval = setInterval(() => {
            if (this.time === 0) {
                return (this.time = 0)
            }
            this.time--
        }, 1000);
    },
    watch: {
        time(v){
            if(v <=0){
                this.$store.dispatch('alert/error', {message:'時間切れです。回答を終了します。',center:true})
                this.$emit('end')
            }
        }
    },
    methods: {
        getTimeString(time) {
            const min = Math.floor(time / 60);
            const sec = time % 60;
            return `${min}分${sec}秒`;
        },
        getColor(time){
            if(time < 10){
                return 'teal';
            }
            if(time < 20){
                return 'green';
            }
            if(time < 30){
                return 'light-green';
            }
            if(time < 40){
                return 'lime accent-4'
            }
            if(time < 50){
                return 'yellow accent-4'
            }
            if(time < 60){
                return 'amber accent-4'
            }
            if(time < 70){
                return 'orange accent-2'
            }
            if(time < 80){
                return 'deep-orange'
            }
            if(time < 90){
                return 'deep-orange accent-2'
            }
            if(time < 100){
                return 'deep-orange accent-4'
            }
            return 'grey darken-1';
        }
    }
}

</script>
<style lang="scss">
#timer {
    position: fixed;
    bottom: 10px;
    right: 10px;
    z-index: 1;
    background-color: #FFF;
    border-radius: 50%;

}
</style>
