<template>
    <v-select
        :loading="loading"
        :disabled="loading"
    ></v-select>
</template>

<script>
    export default {
        name: "ProjectSelect.vue",
        props:{
            worker_id:{
                required: true,
                type:[String,Number],
            },
            value:{
                required:true,
                type:[String,Number,null]
            },
            current_items:{
                type:Array,
                default: []
            }
        },
        data(){
            return {
                loading: true,
                items: this.current_items,
                data: this.value
            }
        },
        watch:{
            data(v){
                this.$emit('input', v);
            }
        },
        mounted(){
            if(this.items.length === 0){
                this.getItems();
            }
        },
        methods:{
            getItems(){
                this.$axios.get(this.$apis.project_autocomplete,{params:{worker_id:this.worker_id}})
                    .then(resp => {

                    })
            }
        }
    }
</script>

<style scoped>

</style>
