<template>
    <v-autocomplete
        :items="items"
        :loading="items.length === 0"
        :search-input.sync="search"
        label="クライアント"
        v-model="client"
        :rules="rules"
    >
    </v-autocomplete>
</template>
<script>
    export default {
        name: 'ClientAutoComplete',
        data(){
            return {
                items: [],
                url:this.$apis.client_autocomplete,
                search: '',
                client:this.value,
                rules: [v => true]
            }
        },
        mounted() {
            if (this.required) {
                this.rules = [v => this.$validation.required(v, 'クライアント')]
            }
        },
        props: {
            params:{
                default:undefined
            },
            value:{
                required: true
            },
            required: {
                type: Boolean,
                default: false
            }
        },
        watch:{
            client(val){
                this.returnName(val);
                this.$emit('input', val);
            }
        },
        created() {
            this.getItems();
        },
        methods:{
            getItems(){
                const data = {};
                if(this.params !== undefined){
                    data.params = this.params;
                }
                this.$axios.get(this.url, data)
                    .then( resp => {
                        this.items = resp.data;
                    })
                    .catch(err => this.$utils.catchError(err));
            },
            returnName(val){
                this.items.map(v => {
                    if (v.value === val) {
                        this.$emit('name', v.text);
                    }
                });
            }
        }
    }
</script>
