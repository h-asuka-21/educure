<template>
    <user-form
        v-model="form_data"
    ></user-form>
</template>
<script>
    import UserForm from "@/components/User/UserForm";
    export default {
        components: {
            UserForm,
        },
        data(){
            return {
                form_data:{
                    id: null,
                    company_id: this.$store.state.user.data.company_id,
                    name:'',
                    name_kana:'',
                    email:'',
                    password:'',
                },
            }
        },
    }
</script>
