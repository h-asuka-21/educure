<template>
    <simple-card-dialog
        ref="dialog"
        max_width="400px"
        :title="$moment(data.start).format('YYYY年M月D日の予約')"
    >
        <v-form
        ref="form"
        >
            <v-row>
                <v-col cols="6">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title>開始</v-list-item-title>
                            <v-list-item-subtitle>{{$moment(data.start).format('H時m分')}}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-col>
                <v-col cols="6">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title>終了</v-list-item-title>
                            <v-list-item-subtitle>{{$moment(data.end).format('H時m分')}}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-col>
                <v-col cols="6">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title>予約数/席数</v-list-item-title>
                            <v-list-item-subtitle>{{data.reserve_count}}/{{data.student_count}}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-col>
                <v-col cols="12" v-if="data.teachers !== undefined">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title>講師</v-list-item-title>
                            <v-list-item-subtitle>{{data.teachers.join(',')}}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-col>
                <v-col cols="6">
                    <text-input
                        type="time"
                        label="受講予定"
                        v-model="data.reserve_start"
                        :disabled="!data.reservable"
                        id="reserve_start"
                        :rules="[
                        v => $validation.required(v,'受講予定'),
                        v => $validation.timeBetween(v,$moment(data.start).format('HH:mm'),$moment(data.end).format('HH:mm')
                    ,'受講予定はスケジュール開始、終了の間に設定してください')]"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <text-input
                        type="time"
                        label="退席予定"
                        v-model="data.reserve_end"
                        id="reserve_end"
                        :disabled="!data.reservable"
                        :rules="[
                        v => $validation.required(v,'退席予定'),
                        v => $validation.timeBetween(v,
                        data.reserve_start
                        ?$moment().set('hour',data.reserve_start.split(':')[0]).set('minute',data.reserve_start.split(':')[1]).format('HH:mm')
                        :$moment(data.start).format('HH:mm'),
                        $moment(data.end).format('HH:mm'),
                        '退席予定は受講予定、終了時刻の間で設定してください')
                    ]"
                    ></text-input>
                </v-col>
                <v-col cols="12" class="d-flex justify-center">
                    <v-btn
                        :dark="data.reservable"
                        :disabled="!data.reservable"
                        :color="$colors.sub"
                        @click="reserve()"
                    >
                        {{data.reservation_id?'予約変更':'予約する'}}
                    </v-btn>
                    <v-btn
                        v-if="data.reservation_id"
                        :dark="data.reservable"
                        :disabled="!data.reservable"
                        :color="$colors.check"
                        @click="reserve(true)"
                        class="ml-2"
                    >
                        キャンセル
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
    </simple-card-dialog>
</template>

<script>
    import SimpleCardDialog from "../Molecules/SimpleCardDialog";
    import TextInput from "../Molecules/Forms/TextInput";

    export default {
        name: "ReserveDialog",
        components: {TextInput, SimpleCardDialog},
        data() {
            return {
                data() {
                    return {
                        data: this.value,
                    }
                }
            }
        },
        props: {
            value: {
                type: Object,
                required: true
            }
        },
        created() {
            if(!this.data.reserve_start){
                this.data.reserve_start = this.$moment(this.data.start).format('HH:mm');
            }
            if(!this.data.reserve_end){
                this.data.reserve_end = this.$moment(this.data.end).format('HH:mm');
            }
        },
        watch: {
            value(val) {
                this.data = val;
                if(!this.data.reserve_start){
                    this.data.reserve_start = this.$moment(this.data.start).format('HH:mm');
                }
                if(!this.data.reserve_end){
                    this.data.reserve_end = this.$moment(this.data.end).format('HH:mm');
                }

            },
            data(val) {
                this.$emit('input', val);
            }
        },
        methods: {
            async reserve(cancel = false) {
                console.log(this.data);
                if(!this.$refs.form.validate()) return;
                try {
                    this.$store.dispatch('dialog/loading');
                    const params = {
                        id: this.data.reservation_id || null,
                        schedule_id: this.data.id,
                        start_time:this.data.reserve_start,
                        end_time:this.data.reserve_end,
                        delete:cancel
                    }
                    const result = await this.$axios.post(this.$utils.getApiUrl(this.$apis.reserve,true), params);
                    console.log(result);
                    this.$utils.success(result);
                    this.$emit('reload');
                    this.$refs.dialog.hide();
                } catch (e) {
                    console.log(e);
                    this.$utils.catchError(e);
                } finally {
                    this.$store.dispatch('dialog/loading',false);
                }

            }
        }
    }
</script>

<style scoped>

</style>
