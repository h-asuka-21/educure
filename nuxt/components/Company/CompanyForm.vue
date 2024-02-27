<template>
    <v-form
        v-bind="fillForm(is_editable)"
        ref="form"
        lazy-validation
    >
        <v-row dense>
            <v-col cols="6">
                <text-input
                    id="name"
                    label="企業名"
                    v-model="form_data.name"
                    :rules="[v => $validation.required(v,'企業名')]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    id="company_code"
                    label="企業コード"
                    v-model="form_data.company_code"
                    :rules="[v => $validation.required(v,'企業コード')]"
                    :disabled="is_editable"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col cols="6">
                <select-input
                    data_test='industry'
                    id="industry"
                    label="業種"
                    v-model="form_data.industry"
                    :items="$selects.industry"
                ></select-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    type="number"
                    id="number_of_employees"
                    label="従業員数"
                    v-model="form_data.number_of_employees"
                ></text-input>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col cols="6">
                <text-input
                    type="number"
                    id="year_of_establishment"
                    label="創立年数"
                    v-model="form_data.year_of_establishment"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    type="number"
                    id="average_age"
                    label="平均年齢"
                    v-model="form_data.average_age"
                ></text-input>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <multi-autocomplete-with-api
                    id="course"
                    label="コースを選択"
                    v-model="form_data.courses"
                    :url="$utils.getApiUrl($apis.autocomplete.course,true, true)"
                    :rules="[v => $validation.required(v,'コース')]"
                    hint="必須"
                    :persistent-hint="true"
                ></multi-autocomplete-with-api>
            </v-col>
        </v-row>
        <v-row class="justify-center">
            <v-btn
                dark
                :color="$colors.main"
                @click="sendForm(false)"
            >
                {{ button_label }}
            </v-btn>
            <delete-button
                v-if="is_editable"
                @delete="sendForm(true)"
                :message="delete_confirm_message"
            >
            </delete-button>
            <confirm-dialog
                ref="create_confirm"
                message="企業に紐づく管理用ユーザーを同時に作成してもよろしいでしょうか？<p class='overline mt-1' style='line-height:1rem'>このユーザーは管理者用として作成されます。<br>契約企業担当者にはアカウントを渡さないようにしてください。</p>"
                @confirm="create(true)"
                @ng="create(false)"
            >
            </confirm-dialog>
        </v-row>
    </v-form>
</template>
<script>
import DeleteButton from "../Molecules/DeleteButton";
import TextInput from "../Molecules/Forms/TextInput";
import SelectInput from "../Molecules/Forms/SelectInput";
import ConfirmDialog from "@/components/Molecules/ConfirmDialog";
import MultiAutocompleteWithApi from "~/components/Molecules/Forms/MultiAutocompleteWithApi";

export default {
    components: {
        MultiAutocompleteWithApi,
        ConfirmDialog,
        TextInput,
        SelectInput,
        DeleteButton
    },
    data() {
        return {
            form_data: {
                id: null,
                name: '',
                company_code: '',
                industry: '',
                number_of_employees: '',
                year_of_establishment: '',
                average_age: '',
                courses: [],
            },
            button_label: '登録',
            delete_confirm_message: '<p>この企業を削除してもよろしいですか？</p>',
            is_editable: false,

        }
    },
    created() {
        if (this.$route.params.id) {
            this.is_editable = true;
            this.getData();
        } else {
            this.$emit('loading', false);
        }
    },
    methods: {
        async getData() {
            try{
                this.$emit('loading', true);
                const resp = await this.$axios.get(this.$utils.getApiUrl(this.$apis.company,true))
                this.form_data = resp.data;
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.$emit('loading', false);
            }
        },
        fillForm(is_editable) {
            if (is_editable) {
                this.button_label = '更新';
            }
        },
        sendForm(delete_flg = false) {
            if (delete_flg) {
                this.delete();
                return;
            }
            if (this.$refs.form.validate()) {
                if (this.form_data.id === null) {
                    this.$refs.create_confirm.show();
                } else {
                    this.update();
                }
            }
        },
        create(create_user = false) {
            this.$store.dispatch('loader/showSub');
            this.form_data.create_user = create_user;
            const url = '/admin/company';
            this.$axios.post(url, this.form_data)
                .then(response => {
                    this.$utils.success(response);
                    this.$store.dispatch('loader/hideSub');
                    this.$emit('reload');
                })
                .catch(error => {
                    this.$store.dispatch('loader/hideSub');
                    this.$utils.catchError(error);
                });
        },
        update() {
            this.$store.dispatch('loader/showSub');
            const url = '/admin' + this.$apis.company + '/' + this.form_data.id;
            this.loading = true;
            this.$axios.put(url, this.form_data).then(resp => {
                this.$utils.success(resp);
                this.$store.dispatch('loader/hideSub');
                this.$emit('reload');
            }).catch(err => {
                this.$store.dispatch('loader/hideSub');
                this.$utils.catchError(err);
            }).finally(r => {
                this.$store.dispatch('loader/hideSub');
            });

        },
        delete() {
            this.$store.dispatch('loader/showSub');
            const url = '/admin' + this.$apis.company + '/' + this.form_data.id;
            this.loading = true;
            this.$axios.delete(url, this.form_data).then(resp => {
                this.$utils.success(resp);
            }).catch(err => {
                this.$utils.catchError(err);
            }).finally(r => {
                this.$store.dispatch('loader/hideSub');
            });

        },
    }
}
</script>
