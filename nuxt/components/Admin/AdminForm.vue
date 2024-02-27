<template>
    <v-form
        ref="form"
        lazy-validation
    >
        <v-row dense>
            <v-col cols="6">
                <text-input
                    id="name"
                    label="氏名"
                    v-model="data.name"
                    :rules="[v => $validation.required(v,'氏名'),$validation.name]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    id="name_kana"
                    label="氏名（カナ）"
                    v-model="data.name_kana"
                    :rules="[v => $validation.required(v,'氏名（カナ）'),$validation.kana]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    id="email"
                    label="メールアドレス"
                    v-model="data.email"
                    :rules="[v => $validation.required(v,'メールアドレス'),$validation.email]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="12" class="mb-1">
                <password-change v-model="data"/>
            </v-col>
            <v-col cols="12" class="d-flex justify-center">
                <v-btn
                    :color="$colors.main"
                    dark
                    @click="submit"
                >{{btn}}</v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script>
import TextInput from "@/components/Molecules/Forms/TextInput";
import PasswordChange from "@/components/Molecules/PasswordChange";

export default {
    name: "AdminForm",
    components: {PasswordChange, TextInput},
    data() {
        return {
            data: this.value,
            btn:'登録'
        }
    },
    props: {
        value: {
            type: Object,
            default: {}
        }
    },
    created() {
        if(this.data.id){
            this.btn = '更新'
        }
    },
    watch: {
        value(v) {
            this.data = v;
            if(this.data.id){
                this.btn = '更新'
            }
        }
    },
    methods:{
        async submit(){
            if(!this.$refs.form.validate()){
                return;
            }
            try{
                this.$store.dispatch('dialog/loading', true);
                const result = await this.$axios.post('/admin/auth/update',this.data)
                if(this.data.password_change){
                    this.$utils.success({data: {message: 'パスワードを変更しました。新しいパスワードで再度ログインしてください。'}});
                    await this.$store.dispatch('admin/logout')
                }
                this.$utils.success(result);
                this.$emit('hide');
            } catch (e){
                this.$utils.catchError(e);
            } finally {
                this.$store.dispatch('dialog/loading', false);
            }
        }
    }
}
</script>

<style scoped>

</style>
