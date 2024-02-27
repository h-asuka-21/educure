<template>
    <v-container id="login_form" fluid>
        <v-row class="fill-height">
            <v-col cols="6" id="logo_area" class="fill-height">
                <logo-and-animations></logo-and-animations>
            </v-col>
            <v-col id="forms">
                <v-container>
                    <v-row dense>
                        <v-col cols="12" class="text-h5 text-center pb-5" id="page_name">
                            {{page_name}}ログイン
                        </v-col>
                        <v-col cols="12">
                            <text-input
                                id="mail"
                                label="メールアドレス"
                                name="mail"
                                v-model="data.email"
                            ></text-input>
                        </v-col>
                        <v-col cols="12">
                            <text-input
                                label="パスワード"
                                id="password"
                                name="password"
                                v-model="data.password"
                                type="password"
                            ></text-input>
                        </v-col>
                        <v-col cols="12" v-if="data.company_code !== undefined">
                            <text-input
                                label="企業コード"
                                id="company_code"
                                name="company_code"
                                v-model="data.company_code"
                            ></text-input>
                        </v-col>
                        <v-row justify="center">
                            <v-btn
                                dark
                                :color="$colors.main"
                                @click="login">ログイン</v-btn>
                        </v-row>
                    </v-row>
                </v-container>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import TextInput from "@/components/Molecules/Forms/TextInput";
    import LogoAndAnimations from "@/components/Molecules/LogoAndAnimations";
    export default {
        name: "LoginForm",
        components: {LogoAndAnimations, TextInput},
        data(){
            return{
                data:{
                    email:null,
                    password:null
                },
                page_name: '受講者',
                strategy: 'student',
                url: null,
            }
        },
        created() {
            if(this.$route.path.indexOf('/master') === -1){
                // master管理ではないとき
                this.data.company_code = null;
            }
            this.setStrategy(this.$route.path);
        },
        methods:{
            /**
             *
             * @param {string} path
             */
            setStrategy(path){
                if(path.indexOf('/master') === 0){
                    this.strategy = 'admin'
                    this.page_name = '管理者'
                } else if (path.indexOf('/admin') === 0 ){
                    this.strategy = 'user'
                    this.page_name = '企業担当者'
                }
            },
            login(){
                const action = this.strategy + '/login';
                this.$store.dispatch(action,this.data);
            },
            setResultData(data){
                switch (this.strategy) {
                    case "admin":
                        break;
                    case "user":
                        break;
                    case "student":
                        break;
                    default:
                        break;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
#login_form {
    padding: 0;
    height: 100vh;
    width: 100vw;
    max-height: 100vh;
    max-width: 100vw;
    position: absolute;
    top: 0;
    left: 0;
    background-color: #fff;
    #forms{
        position: relative;
        top: 32%;
        max-height: 282px;
    }
    #logo_area{
        padding: 0;
    }
    #page_name{
        color: #757575;
    }
}
</style>
