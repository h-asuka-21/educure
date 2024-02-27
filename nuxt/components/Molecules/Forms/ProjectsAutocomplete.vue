<template>
    <v-autocomplete
        :items="items"
        :loading="items.length === 0"
        label="案件"
        v-model="project"
    >
        <template v-slot:item="data">
            <v-list-item-content>
                <v-list-item-title v-html="">{{data.item.text}}</v-list-item-title>
                <v-list-item-subtitle v-html="">{{data.item.contract_type}}</v-list-item-subtitle>
            </v-list-item-content>
        </template>

    </v-autocomplete>
</template>
<script>
    export default {
        name: 'ProjectsAutocomplete',
        data() {
            return {
                items: [],
                url: this.$apis.project_autocomplete,
                project: this.value
            }
        },
        props: {
            client_id: {
                required: false,
                type: [Number, String],
                default: undefined
            },
            worker_id: {
                required: false,
                type: [Number, String],
                default: undefined
            },
            value: {
                required: true
            }
        },
        watch: {
            project(val) {
                this.returnName(val);
                this.$emit('input', val);
            }
        },
        created() {
            this.getItems();
        },
        methods: {
            getItems() {
                this.$axios.get(this.url, {
                    params: {
                        client_id: this.client_id,
                        worker_id: this.worker_id
                    }
                })
                    .then(resp => {
                        this.items = resp.data;
                    })
                    .catch(err => this.$utils.catchError(err));
            },
            returnName(val) {
                this.items.map(v => {
                    if (val === v.value) {
                        this.$emit('name', v.text);
                    }
                });
            }
        }
    }
</script>
