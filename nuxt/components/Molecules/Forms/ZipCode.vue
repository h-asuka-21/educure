<template>
    <v-text-field
        label="郵便番号"
        prepend-icon="〒"
        v-model="data"
        :rules="$validation.zip_code"
    ></v-text-field>
</template>

<script>
    export default {
        name: "ZipCode",
        data(){
            return{
                data: this.value,
                address: null,
            }
        },
        props:{
            value:{
                required:true
            }
        },
        watch:{
            data(val){
                this.$emit('input',val)
                // this.getAddress(val)
            },
            value(val){
                this.data = val;
            }
        },
        methods:{
            getAddress(val){
                // リバースプロキシの関係上cors問題が解決できず。一旦使わない方向で
                if (/^\d{3}-?\d{4}$/.test(val)) {
                    const param = {
                        zipcode: val
                    };
                    if (val.indexOf('-') > -1) {
                        let exploded = val.split('-')
                        param.zipcode = exploded[0] + exploded[1];
                    }
                    this.$axios.get('/zip_code/api/search', {params: param})
                        .then(resp => {
                            console.log(resp);
                            if (resp.data.results !== null && resp.data.results.length === 1) {
                                let data = resp.data.results[0];
                                this.address = data.address1 + data.address2 + data.address3;
                                this.$emit('address', this.address);
                            }
                        })
                        .finally(resp => {
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>
