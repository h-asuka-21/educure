<template>
    <v-form
        v-bind="fillForm(is_editable)"
        ref="form"
        lazy-validation
    >
        <v-row v-if="value" dense>
            <v-col cols="12">
                <v-card outlined>
                    <v-card-subtitle class="subtitle-1">スケジュール情報</v-card-subtitle>
                    <v-card-text>
                        <v-row dense>
                            <v-col v-if="admin" class="font-weight-bold" cols="3">企業</v-col>
                            <v-col v-if="admin" cols="3">{{value.company_name}}</v-col>
                            <v-col class="font-weight-bold" cols="3">予約数</v-col>
                            <v-col cols="3">{{value.reserve_count}}</v-col>
                            <v-col v-if="admin" class="font-weight-bold" cols="3">講師</v-col>
                            <v-col v-if="admin" cols="9">{{value.teachers.join(',')}}</v-col>
                            <v-col cols="12" v-if="value.reserve_count > 0">
                                <v-row dense>
                                    <v-col class="font-weight-bold" cols="12">予約者</v-col>
                                    <v-col cols="12">
                                        <v-row dense>
                                            <v-col v-for="(student,key) in value.reserved_students" :key="key">
                                                <span :class="isAttendance(student)">
                                                    {{student.name}}<br>{{$moment('2020-01-01 '+ student.start_time).format('HH:mm')}}〜{{$moment('2020-01-01 '+ student.end_time).format('HH:mm')}}
                                                </span>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row dense v-if="bulk">
            <v-col cols="12" class="caption" v-if="">※指定月のスケジュールを一括で登録します。<br>{{admin?'既に同企業・同日に登録されている場合、スキップされます。':'' }}</v-col>
            <v-col cols="12" class="subtitle-1">曜日指定</v-col>
            <v-col v-for="(week,key) in weeks" :key="key">
                <v-checkbox v-model="form_data.weeks[key]" :label="week"/>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col cols="6">
                <text-input
                    id="name"
                    label="スケジュール名"
                    v-model="form_data.name"
                    :rules="[v => $validation.required(v,'スケジュール名')]"
                ></text-input>
            </v-col>
            <v-col cols="6" v-if="!bulk">
                <date
                    :disabled="target_date !== undefined"
                    id="date"
                    label="日付"
                    v-model="form_data.date"
                    :rules="[v => $validation.required(v,'日付')]"
                ></date>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col cols="6">
                <text-input
                    id="start_time"
                    label="開始時間"
                    v-model="form_data.start_time"
                    type="time"
                    :rules="[v => $validation.required(v,'開始時間')]"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    id="end_time"
                    label="終了時間"
                    v-model="form_data.end_time"
                    type="time"
                    :rules="[v => $validation.required(v,'終了時間')]"
                ></text-input>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col cols="6">
                <text-input
                    id="available_limit"
                    label="予約可能人数"
                    v-model="form_data.available_limit"
                    :rules="[v => $validation.required(v,'予約可能人数')]"
                ></text-input>
            </v-col>
            <v-col cols="6"
            >
                <multi-autocomplete-with-api
                    v-if="show_user"
                    id="user"
                    label="講師"
                    v-model="form_data.user"
                    :param="{company_id:form_data.company_id}"
                    :url="$utils.getApiUrl($apis.autocomplete.schedule,true)"
                    :rules="[v => $validation.required(v,'講師')]"
                ></multi-autocomplete-with-api>
                <v-row
                    v-else
                    dense
                >
                    <v-col cols="8">
                        <multi-autocomplete-with-api
                            :disabled="form_data.all"
                            id="company"
                            label="企業"
                            v-model="form_data.companies"
                            :url="$utils.getApiUrl($apis.autocomplete.company,true)"
                            :rules="[v => $validation.required(v,'企業')]"
                            :item-disabled="company_ids"
                        ></multi-autocomplete-with-api>
                    </v-col>
                    <v-col cols="4">
                        <v-checkbox class="mt-0" dense label="全企業を選択" v-model="form_data.all"/>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-row class="justify-center">
            <v-btn
                :dark="deletable"
                :disabled="!deletable"
                :color="$colors.main"
                @click="sendForm(false)"
            >
                {{ button_label }}
            </v-btn>
            <delete-button
                v-if="is_editable && deletable"
                @delete="sendForm(true)"
                :message="delete_confirm_message"
            >
            </delete-button>
        </v-row>
    </v-form>
</template>
<script>
import TextInput from "../Molecules/Forms/TextInput";
import SelectInput from "../Molecules/Forms/SelectInput";
import Date from "../Molecules/Forms/Date";
import MultiAutocompleteWithApi from "../Molecules/Forms/MultiAutocompleteWithApi";
import DeleteButton from "../Molecules/DeleteButton";

export default {
    components: {
        TextInput,
        SelectInput,
        Date,
        MultiAutocompleteWithApi,
        DeleteButton
    },
    data() {
        return {
            form_data: {
                id: null,
                company_id: '',
                name: '勉強会',
                date: '',
                start_time: '13:00',
                end_time: '20:00',
                available_limit: 50,
                user: [],
                all: true,
                weeks: [true,true,true,true,true,true,true]
            },
            button_label: '登録',
            delete_confirm_message: '<p>このスケジュールを削除してもよろしいですか？</p>',
            is_editable: false,
            show_user: true,
            deletable: true,
            company_id:undefined,
            weeks:['日','月','火','水','木','金','土']
        }
    },
    props: {
        create_flg: {
            type: Boolean,
            default: false
        },
        target_date: {
            type: String,
            default: undefined
        },
        admin:{
            type:Boolean,
            default:false
        },
        company_ids:{
            type:Array,
            default: undefined
        },
        value:{
            type:Object,
            default:undefined
        },
        bulk:{
            type:Boolean,
            default:false
        },
        month:{
            type:String,
            default: undefined
        }
    },
    created() {
        this.setFromValue(this.value);
        if(this.form_data.date !== undefined  && this.form_data.date !== null ){
            const date = this.$moment(this.form_data.date);
            const now = this.$moment();
            if(date.isBefore(now)){
                this.deletable = false;
            }
        }
    },
    watch: {
        target_date(val) {
            this.form_data.date = val;
        }
    },
    mounted() {

    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('loader/showSub');
                const url = this.$utils.getApiUrl(this.$apis.schedule, true);
                const resp = await this.$axios.get(url)
                this.form_data = resp.data;
                console.log(this.form_data);
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.$store.dispatch('loader/hideSub');
            }

        },
        fillForm(is_editable) {
            if (is_editable) {
                this.button_label = '更新';
            }
        },
        async sendForm(delete_flg = false) {
            try {
                let ret = null;
                this.$store.dispatch('loader/showSub')
                if (delete_flg) {
                    // 削除
                    ret = await this.$axios.delete(this.getUrl(true))
                } else if (this.$refs.form.validate()) {
                    if(this.bulk){
                        // 一括登録時
                        this.form_data.month = this.month;
                        ret = await this.$axios.post(this.$utils.getApiUrl(this.$apis.schedule_bulk, true, true), this.form_data)
                        this.$utils.success(ret);
                    } else {
                        if (this.form_data.id === null) {
                            // 新規
                            ret = await this.$axios.post(this.getUrl(), this.form_data)
                            this.$utils.success(ret);
                        } else {
                            // 更新
                            ret = await this.$axios.put(this.getUrl(true), this.form_data)
                        }
                    }
                }
                if(ret){
                    this.$utils.success(ret);
                    this.$store.dispatch('loader/hideSub')
                    this.$emit('hide');
                }
            }catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.$store.dispatch('loader/hideSub')
            }
        },
        getUrl(update = false) {
            let url = this.$utils.getApiUrl(this.$apis.schedule, true, true);
            if(update){
                url += '/' + this.form_data.id;
            }
            return url;

        },
        setFromValue(value){
            this.form_data.date = this.target_date;
            if(!value){
                // 新規作成時
                if(this.admin){
                    // 管理者の場合企業選択のオートコンプリートを表示
                    this.show_user = false;
                } else {
                    this.form_data.company_id = this.$store.state.user.data.company_id;
                }
                return;
            }
            if(this.admin){
                this.company_id = value.company_id;
            }
            this.is_editable = true;
            this.form_data = {
                id:value.id,
                name: value.name,
                start_time: this.$moment(value.start).format('HH:mm'),
                end_time:this.$moment(value.end).format('HH:mm'),
                available_limit:value.student_count,
                user:value.teacher_ids,
                date:this.target_date
            };
            //企業IDの設定（管理者側はpropsから取得）
            if(this.admin){
                this.form_data.company_id = value.company_id;
            } else {
                this.form_data.company_id = this.$store.state.user.data.company_id;
            }
        },
        isAttendance(student){
            let result = ''
            if(!this.deletable && student.attendance_flg === 0){
                return 'grey--text text--lighten-1';
            }
            if(student.attendance_flg === 1){
                result +=' font-weight-bold black--text'
            }
            return result;
        }
    }
}
</script>
