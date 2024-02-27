<template>
    <v-card outlined
            :color="color"
            dark
            class="fill-height alert_card"
    >
        <slot></slot>
        <v-row dense
               class="pr-1 pb-2"
               justify="end">
            <v-btn
                v-if="action_text"
                @click="$emit('click')"
                class="action" outlined>{{ action_text }}
            </v-btn>
        </v-row>
    </v-card>
</template>

<script>
export default {
    name: "AlertCard",
    props: {
        color: {
            type: String,
            required: true
        },
        action_text: {
            type: String,
            default: undefined
        }
    }
}
</script>

<style scoped lang="scss">
.alert_card {
    position: relative;
    .row {
        position: absolute;
        bottom: 0;
        width: 100%;
    }
}
</style>
