<template>
    <v-card outlined>
        <v-card-title class="py-0">
            <v-switch label="パスワードを変更する" v-model="data.password_change"/>
        </v-card-title>
        <v-expand-transition>
            <v-card-text v-if="data.password_change">
                <v-card-text>
                    パスワードは再発行出来ませんので必ず忘れないよう管理してください。<br>
                    パスワード不明によりアカウントを再発行した場合は学習状況の引き継ぎは出来ません。
                </v-card-text>
                <v-row dense>
                    <v-col cols="12">
                        <text-input label="現在のパスワード" id="password" type="password"
                                    v-model="data.current_password" hint="必須" :persistent-hint="true"
                                    :rules="[v => $validation.required(v,'現在のパスワード')]"/>
                    </v-col>
                    <v-expand-transition>
                        <v-col cols="12" v-if="data.current_password">
                            <text-input label="新しいパスワード" id="new_password" type="password"
                                        v-model="data.new_password" hint="必須" :persistent-hint="true"
                                        :rules="[v => $validation.required(v,'新しいパスワード'),v => $validation.notSame(v,data.current_password,'現在のパスワード'),$validation.password]"/>
                        </v-col>
                    </v-expand-transition>
                    <v-expand-transition>
                        <v-col cols="12" v-if="data.new_password && data.current_password">
                            <text-input label="新しいパスワード(確認)" id="new_password_check" type="password"
                                        v-model="data.new_password_check" hint="必須" :persistent-hint="true"
                                        :rules="[v => $validation.required(v,'新しいパスワード(確認)'),
                                                v => $validation.same(v,data.new_password,'新しいパスワード')]"/>
                        </v-col>
                    </v-expand-transition>
                </v-row>
            </v-card-text>
        </v-expand-transition>
    </v-card>
</template>

<script>
import TextInput from "@/components/Molecules/Forms/TextInput";

export default {
    name: "PasswordChange",
    components: {TextInput},
    data() {
        return {
            data: this.value
        }
    },
    props: {
        value: {
            type: Object,
            default: undefined
        }
    },
    watch: {
        value(v) {
            this.data = v;
        },
        data(v) {
            this.$emit('input', v);
        }
    }
}
</script>

<style scoped>

</style>
