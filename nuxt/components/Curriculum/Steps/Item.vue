<template>
    <v-card outlined class="fill-height step_card">
        <v-card-title class="card_title">
            <p>
                {{data.name}}
            </p>
            <v-spacer>
            </v-spacer>
            <div class="buttons">
                <v-btn icon
                       v-if="show"
                       class="edit_step"
                       @click="$emit('edit',data)">
                    <v-icon>mdi-square-edit-outline</v-icon>
                </v-btn>
                <icon-close-button
                    v-if="show"
                    class="remove_step"
                    @click="$emit('delete',data)"
                >
                </icon-close-button>
            </div>
        </v-card-title>
        <v-card-text>
            <div class="step_target_days" v-html="'目標日数（1ヶ月）　' + String(data.target_days) + '日'"></div>
            <div class="step_deadline_days" v-html="'目標日数（2ヶ月）　' + String(data.deadline_days) + '日'"></div>
            <div class="step_content"><html-element :content="data.content"/></div>
            <div class="step_image" v-if="data.image !== null">
                <image-with-dialog v-model="data.image" dialog-max-with="400px"></image-with-dialog>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
    import ImageWithDialog from "../../Molecules/ImageWithDialog";
    import IconCloseButton from "../../Atoms/IconCloseButton";
    import HtmlElement from "@/components/Molecules/HtmlElement";
    export default {
        name: "Item",
        components: {HtmlElement, IconCloseButton, ImageWithDialog},
        props:{
            value:{
                type:Object,
                required: true
            },
            show:{
                type:Boolean,
                default: true
            }
        },
        data(){
            return{
                data: this.value,
                image: null,
            }
        },
        watch:{
            value:{
                handler(val) {
                    this.data = val;
                },
                deep:true
            },
            data:{
                handler(val) {
                    this.$emit('input', val);
                },
                deep: true
            }
        },

    }
</script>

<style scoped lang="scss">
    .card_title{
        padding-top: 4px;
        padding-bottom: 4px;
        > .buttons{
            position: relative;
            left: 10px;
            top: -4px;
        }
    }
</style>
