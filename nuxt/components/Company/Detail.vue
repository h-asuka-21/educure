<template>
    <v-dialog
        v-if="dialog"
        v-model="dialog"
    >
        <v-card
            :loading="loading"
            :disabled="loading"
        >
            <v-card-title>{{data.name}}詳細</v-card-title>
            <v-form
                ref="form"
                lazy-validation
            >
                <v-container>
                    <v-row>
                        <v-col cols="6">
                            <v-text-field
                                label="企業コード"
                                v-model="data.company_code"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                label="企業名"
                                v-model="data.name"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row class="justify-center">
                        <v-btn dark :color="$colors.main" @click="submit()">登録</v-btn>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Detail",
        data(){
            return{
                dialog: false,
                data: this.value,
                loading: false
            }
        },
        watch: {
            data(val){
                console.log(val);
            },
            value(val){
                this.data = val;
            }
        },
        props: {
            value:{
                type:Object,
                required: true,
            }
        },
        methods:{
            show(){
                this.dialog = true;
            },
            hide(){
                this.dialog = false;
            },
            submit(){
                if(!this.$refs.form.validate()){
                    return;
                }
                const url = '/admin' + this.$apis.company + '/' + this.data.id;
                this.loading = true;
                this.$axios.put(url, this.data).then(resp => {
                    this.loading = false;
                    this.$utils.success(resp);
                    this.hide();
                }).catch(err => {
                    this.$utils.catchError(err);
                    this.loading = false;
                });
            },
        }
    }
</script>

<style scoped>

</style>
