<template>
    <v-autocomplete
        :items="items"
        :loading="items.length === 0"
        :search-input.sync="search"
        label="ワーカー"
        v-model="workers"
        multiple
    >
    </v-autocomplete>
</template>
<script>
    export default {
        name: 'WorkerAutocomplete',
        data(){
            return {
                items: [],
                url:this.$apis.worker_autocomplete,
                search: '',
                workers:this.value
            }
        },
        props: {
            project_id:{
                required: true,
                type:[Number,String],
            },
            value:{
                required: true
            },
            default_check_all:{
                type:Boolean,
                default: false,
            }
        },
        watch:{
            workers(val){
                this.returnName(val);
                this.$emit('input', val);
            },
            items(val){

            }

        },
        created() {
            this.getItems();
        },
        methods: {
            getItems() {
                this.$axios.get(this.url, {params:{project_id:this.project_id}})
                    .then( resp => {
                        this.items = resp.data;
                        this.checkAll();
                    })
                    .catch(err => this.$utils.catchError(err));
            },
            checkAll() {
                if (!this.default_check_all) {

                    return;
                }
                if (this.workers !== undefined && this.workers.length > 0) {
                    return;
                }
                // 初期値未設定の場合
                this.workers = [];
                this.items.map((v, k) => {
                    this.workers.push(v.value);
                })
            },
            returnName(val) {
                let names = [];
                this.items.map(v => {
                    if (val.indexOf(v.value) > -1) {
                        names.push(v.text);
                    }
                });
                this.$emit('name', names);
            }
        }
    }
</script>
