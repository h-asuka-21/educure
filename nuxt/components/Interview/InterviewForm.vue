<template>
    <v-form
        ref="form"
        lazy-validation
    >

        <v-row dense>
            <v-col v-if="data.updated_at" cols="12" class="d-flex">
                <v-row dense>
                    <v-col cols="4">評価日（最終更新日）</v-col>
                    <v-col cols="8">{{$moment(data.updated_at).format('YYYY年M月D日')}}</v-col>
                </v-row>
            </v-col>
            <v-col v-if="data.user_name" cols="12" class="d-flex">
                <v-row dense>
                    <v-col cols="4">評価者</v-col>
                    <v-col cols="8">{{data.user_name}}</v-col>
                </v-row>
            </v-col>
            <v-col cols="12">
                <v-row dense>
                    <v-col cols="4" class="pt-3">評価</v-col>
                    <v-col cols="8">
                        <star-rating
                            v-model="data.sales_evaluation"
                            :star-size="40"/>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12">
                <text-area
                    id="evaluation_reason"
                    label="コメント"
                    v-model="data.evaluation_reason"
                    :rules="[v=>$validation.required(v,'コメント')]"
                />
            </v-col>
        </v-row>
        <v-row class="justify-center">
            <v-btn
                dark
                :color="$colors.main"
                @click="sendForm()"
            >
                {{ data.id ? '更新' : '登録' }}
            </v-btn>
            <delete-button
                v-if="data.id"
                @delete="sendForm(true)"
                message="<p>この面談履歴を削除してもよろしいですか？</p>"
            >
            </delete-button>
        </v-row>
    </v-form>
</template>
<script>
import TextArea from "@/components/Molecules/Forms/TextArea";
import SelectInput from "../Molecules/Forms/SelectInput";
import DeleteButton from "../Molecules/DeleteButton";

export default {
    components: {
        TextArea,
        SelectInput,
        DeleteButton
    },
    data() {
        return {
            data: this.value,
        }
    },
    created() {
        console.log(this.data);
    },
    props: {
        value: {
            type: Object,
        }
    },
    watch: {
        data(v) {
            this.$emit('input', v);
        },
        value(v) {
            this.data = v
        }
    },
    methods: {
        async sendForm(delete_flg = false) {
            try {
                this.$store.dispatch('dialog/loading');
                let url = this.$utils.getApiUrl(this.$apis.interview_history, true, true);
                let ret = null;
                if (delete_flg) {
                    url += '/' + this.data.id;
                    ret = await this.$axios.delete(url);
                } else if (this.$refs.form.validate()) {
                    if (this.data.id) {
                        url += '/' + this.data.id;
                        ret = await this.$axios.put(url, this.data);
                    } else {
                        ret = await this.$axios.post(url, this.data);
                    }
                }
                this.$utils.success(ret);
                this.$emit('success');
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.$store.dispatch('dialog/loading',false);
            }
        },
    }
}
</script>
