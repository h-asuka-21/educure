<template>
    <v-form ref="form" lazy-validation>
        <v-container>
            <v-row dense>
                <v-col cols="12">
                    <v-card outlined class="mb-3" v-if="student_flg">
                        <v-card-text class="px-2 py-1">
                            <v-row dense>
                                <v-col cols="6">
                                    <v-row dense>
                                        <v-col
                                            cols="12"
                                            class="overline red--text"
                                            >修正者</v-col
                                        >
                                        <v-col cols="12" class="red--text">{{
                                            data.name
                                        }}</v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6">
                                    <v-row dense>
                                        <v-col cols="12" class="overline"
                                            >コース</v-col
                                        >
                                        <v-col cols="12">{{
                                            data.course_name
                                        }}</v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6">
                                    <v-row dense>
                                        <v-col cols="12" class="overline"
                                            >開始日</v-col
                                        >
                                        <v-col cols="12">{{
                                            $moment(data.start_date).format(
                                                "YYYY年M月D日"
                                            )
                                        }}</v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6" v-if="data.end_date">
                                    <v-row dense>
                                        <v-col cols="12" class="overline"
                                            >終了日</v-col
                                        >
                                        <v-col cols="12">{{
                                            $moment(data.end_date).format(
                                                "YYYY年M月D日"
                                            )
                                        }}</v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6">
                                    <v-row dense>
                                        <v-col cols="12" class="overline"
                                            >ステータス</v-col
                                        >
                                        <v-col cols="12">
                                            {{
                                                $selects.after_graduation_flg[
                                                    data.after_graduation_flg
                                                ].text
                                            }}
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="6">
                    <text-input
                        id="name"
                        label="氏名"
                        v-model="data.name"
                        :rules="[
                            v => $validation.required(v, '氏名'),
                            $validation.name
                        ]"
                        hint="必須"
                        :persistent-hint="true"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <text-input
                        id="name_kana"
                        label="氏名（カナ）"
                        v-model="data.name_kana"
                        :rules="[
                            v => $validation.required(v, '氏名（カナ）'),
                            $validation.kana
                        ]"
                        hint="必須"
                        :persistent-hint="true"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <text-input
                        id="email"
                        label="メールアドレス"
                        v-model="data.email"
                        :rules="[
                            v => $validation.required(v, 'メールアドレス'),
                            $validation.email
                        ]"
                        hint="必須"
                        :persistent-hint="true"
                    ></text-input>
                </v-col>
                <v-col cols="6" v-if="data.id === undefined">
                    <text-input
                        id="new_password"
                        type="password"
                        label="パスワード"
                        v-model="data.password"
                        :rules="[
                            v => $validation.required(v, 'パスワード'),
                            $validation.password
                        ]"
                        hint="必須"
                        :persistent-hint="true"
                    ></text-input>
                </v-col>
                <v-col cols="6" v-if="!student_flg">
                    <autocomplete-with-api
                        id="course_id"
                        label="コース"
                        v-model="data.course_id"
                        :url="course_group_url"
                        :rules="[v => $validation.required(v, 'コース')]"
                        hint="必須"
                        :persistent-hint="true"
                    ></autocomplete-with-api>
                </v-col>
                <v-col cols="6" v-if="!student_flg">
                    <date
                        id="start_date"
                        label="開始日"
                        v-model="data.start_date"
                        :clearable="true"
                        :rules="[v => $validation.required(v, '開始日')]"
                        hint="必須"
                        :persistent-hint="true"
                    ></date>
                </v-col>
                <v-col
                    cols="6"
                    v-if="!student_flg && data.after_graduation_flg"
                >
                    <date
                        id="end_date"
                        label="終了日"
                        v-model="data.end_date"
                        :clearable="true"
                        :rules="[
                            v =>
                                $validation.required(
                                    v,
                                    'ステータスが受講中ではない場合終了日'
                                )
                        ]"
                        hint="必須"
                        :persistent-hint="true"
                    ></date>
                </v-col>
                <v-col cols="6" v-if="!student_flg">
                    <select-input
                        :disabled="!data.id"
                        label="ステータス"
                        id="after_graduation_flg"
                        v-model="data.after_graduation_flg"
                        :items="$selects.after_graduation_flg"
                        :rules="[v => $validation.required(v, 'ステータス')]"
                        hint="必須"
                        :persistent-hint="true"
                    ></select-input>
                </v-col>

                <v-col cols="12" class="mb-3">
                    <password-change v-model="data" v-if="change_password" />
                </v-col>
            </v-row>
            <v-row class="justify-center">
                <v-btn dark :color="$colors.main" @click="sendForm(false)">{{
                    data.id === undefined ? "登録" : "更新"
                }}</v-btn>
                <delete-button
                    v-if="data.id && !student_flg && !admin_flg"
                    @delete="sendForm(true)"
                    message="<p>この受講者を削除してもよろしいですか？</p>"
                >
                </delete-button>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import TextInput from "../Molecules/Forms/TextInput";
import AutocompleteWithApi from "../Molecules/Forms/AutocompleteWithApi";
import Date from "../Molecules/Forms/Date";
import SelectInput from "../Molecules/Forms/SelectInput";
import PasswordChange from "@/components/Molecules/PasswordChange";
import DeleteButton from "@/components/Molecules/DeleteButton";

export default {
    name: "StudentForm",
    components: {
        DeleteButton,
        PasswordChange,
        SelectInput,
        Date,
        AutocompleteWithApi,
        TextInput
    },
    props: {
        value: {
            type: Object,
            required: true
        },
        change_password: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            data: this.value,
            student_flg: false,
            admin_flg: false,
            course_group_url: this.$utils.getApiUrl(
                this.$apis.autocomplete.course_group_student,
                true
            )
        };
    },
    watch: {
        value(v) {
            this.data = v;
            if (!this.data.after_graduation_flg) {
                this.data.after_graduation_flg = 0;
            }
        },
        data(v) {
            this.$emit("input", v);
        }
    },
    created() {
        if (this.$utils.getThemeClass() === "student") {
            this.student_flg = true;
        }
        if (this.$utils.getThemeClass() === "admin") {
            this.admin_flg = true;
            this.course_group_url = this.$utils.getApiUrl(
                this.$apis.autocomplete.course_group,
                true,
                true
            );
        }
        if (this.$utils.getThemeClass() === "master") {
            if (this.$route.path.match("/company")) {
                this.course_group_url = this.$utils.getApiUrl(
                    this.$apis.autocomplete.course_group,
                    true
                );
            }
        }
        if (!this.data.after_graduation_flg) {
            this.data.after_graduation_flg = 0;
        }
    },
    methods: {
        async sendForm(delete_flg = false) {
            await this.$store.dispatch("loader/showSub");
            await this.$store.dispatch("dialog/loading", true);
            try {
                let url = this.$utils.getApiUrl(this.$apis.student, true, true);
                let result = false;
                if (delete_flg) {
                    result = await this.$axios.delete(url + "/" + this.data.id);
                    this.$utils.success(result);
                    this.$emit("hide");
                } else if (this.$refs.form.validate()) {
                    if (!this.data.id) {
                        result = await this.$axios.post(url, this.data);
                    } else {
                        if (this.$utils.isStudent()) {
                            result = await this.$axios.put(url, this.data);
                        } else {
                            result = await this.$axios.put(
                                url + "/" + this.data.id,
                                this.data
                            );
                        }
                    }
                    if (this.data.password_change && this.$utils.isStudent()) {
                        this.$utils.success({
                            data: {
                                message:
                                    "パスワードを変更しました。新しいパスワードでログインし直してください。"
                            }
                        });
                        await this.$store.dispatch("student/logout");
                    } else {
                        this.$utils.success(result);
                    }
                    this.$emit("hide");
                }
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                await this.$store.dispatch("loader/hideSub");
                await this.$store.dispatch("dialog/loading", false);
                if (this.$utils.isUser() && this.$refs.form.validate()) {
                    this.$router.push("/admin/student");
                }
            }
        }
    }
};
</script>

<style scoped></style>
