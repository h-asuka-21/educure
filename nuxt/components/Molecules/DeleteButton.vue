<template>
    <div>
        <v-btn
            class="ml-3"
            dark
            :color="$colors.check"
            @click.stop="show_dialog=true"
            :disabled="disabled"
        >
            {{button_label}}
        </v-btn>
        <v-dialog
            v-model="show_dialog"
            max-width="290px"
            min-width="200px"
        >
            <v-card>
                <v-card-text >
                    <div class="pt-4 text-center justify-center" v-html="message"></div>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        :color="$colors.main"
                        dark
                        @click.stop="deleteAction"
                    >
                        はい
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red"
                        dark
                        @click.stop="show_dialog=false"
                    >
                        いいえ
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        props: ['message','disabled','label'],
        data(){
            return{
                show_dialog : false,
                button_label: this.label === undefined ? '削除':this.label
            }
        },
        mounted() {
            // console.log(this.$store.default.state.is_editable || this.$store.default.state.type === 'worker')
        },
        methods:{
            deleteAction(){
                this.show_dialog = false;
                this.$emit('delete')
            }
        }
    }
</script>
