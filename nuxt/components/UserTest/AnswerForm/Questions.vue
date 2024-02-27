<template>
    <div>
        <v-skeleton-loader
            type="image"
            :loading="loading"
        >
            <v-row dense>
                <v-col v-if="data.length > 0">
                    <v-row dense>
                        <v-col cols="12" v-for="(item,key) in data " :key="key">
                            <item
                                :item_key="key"
                                :item="item"
                                :show="show"
                            />
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-skeleton-loader>
    </div>
</template>

<script>
import Item from "./Questions/Item";

export default {
    name: "Questions",
    components: {Item},
    props: {
        questions: {
            required: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        show: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            data: this.questions,
            edit_item: {},
        }
    }
}
</script>

<style scoped>

</style>
