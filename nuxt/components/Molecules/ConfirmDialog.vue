<template>
    <v-dialog max-width="400" v-model="dialog" :persistent="persistent">
        <v-card>
            <v-card-text class="pt-2">
                <slot v-if="!message"/>
                <div v-else class="confirm_text" v-html="message">
                </div>
                <v-row class="pt-3" justify="space-around">
                    <v-btn :color="$colors.main" @click="ok" dark>
                        はい
                    </v-btn>
                    <v-btn :color="$colors.check" @click="ng" dark>
                        いいえ
                    </v-btn>
                </v-row>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "ConfirmDialog",
    props: {
        message: {
            type: String,
            default:undefined
        },
        persistent:{
            type:Boolean,
            default:false
        }
    },
    data() {
        return {
            dialog: false
        }
    },
    methods: {
        show() {
            this.dialog = true;
        },
        hide() {
            this.dialog = false;
        },
        ok() {
            this.$emit('confirm');
            this.hide();
        },
        ng() {
            this.$emit('ng');
            this.hide();
        }
    }
}
</script>

<style scoped lang="scss">
.confirm_text {
    text-align: center;
}
</style>
