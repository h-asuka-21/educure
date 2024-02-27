<template>
    <div>
        <v-row dense>
            <v-col sm="12" md="6" lg="6" xl="6" >
                <v-card
                    outlined
                    :disabled="company_loading"
                    :loading="company_loading"
                    class="fill-height"
                >
                    <v-card-title>企業情報</v-card-title>
                    <v-card-text>
                        <company-form
                            @loading="v => this.company_loading = v"
                        />
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col sm="12" md="6" lg="6" xl="6" >
                <v-card
                    outlined
                    class="fill-height">
                    <v-card-title>企業担当者<v-spacer/><v-btn dark :color="$colors.sub" @click="$refs.user.clickRow({company_id:$route.params.id})">新規作成</v-btn></v-card-title>
                    <v-card-text>
                        <users ref="user"/>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card outlined>
                    <v-card-title>受講者<v-spacer/><v-btn dark :color="$colors.sub" @click="$refs.student.clickRow({company_id:$route.params.id})">新規作成</v-btn></v-card-title>
                    <v-card-text>
                        <student-list
                            :admin="true"
                            ref="student"
                        />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import CompanyForm from "~/components/Company/CompanyForm";
import Users from "~/components/Company/Detail/Users";
import StudentList from "~/components/Student/StudentList";

export default {
    components: {
        StudentList,
        Users,
        CompanyForm
    },
    created() {
        this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/showBackButton');
        this.$store.dispatch('page/setTitle','企業詳細');
        this.$store.dispatch('page/setBackUrl',null)
    },
    data(){
        return {
            company_loading:true
        }
    },
}
</script>
