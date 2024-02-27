<template>
    <v-card outlined class="fill-height question_card">
        <v-card-title class="card_title">
            <p>
                {{data.name}}
            </p>
            <v-spacer>
            </v-spacer>
            <div class="buttons">
                <v-btn icon
                       v-if="show"
                       class="edit_question"
                       @click="$emit('edit',data)">
                    <v-icon>mdi-square-edit-outline</v-icon>
                </v-btn>
                <icon-close-button
                    v-if="show"
                    class="remove_question"
                    @click="$emit('delete',data)"
                >
                </icon-close-button>
            </div>
        </v-card-title>
        <v-card-text>
            <div class="question_content"><html-element :content="data.content"/></div>
            <div class="question_image" v-if="data.image !== null">
                <image-with-dialog v-model="data.image" dialog-max-with="400px"></image-with-dialog>
            </div>
            <v-row justify="center">
                <v-col v-for="n in 4" :key="n" cols="6">
                    <div class="choices" v-bind:class="[n === data.answer? 'answer': '', (n == data.choice && data.choice != data.answer)? 'choice': '']" >
                        <p>{{n}}.</p>
                        <p class="text">{{data['choice' + n]}}</p>
                    </div>
                </v-col>
                <p v-if="data.choice == 0" class="note">※この設問は未回答です</p>
            </v-row>
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
    .choices {
        padding:4px 10px;
        &.answer{
            border-radius: 4px;
            border: thin solid #ff9800;
        }
        &.choice{
            border-radius: 6px;
            border: thin dashed #E91E63;
        }
        &.choice:after{
            color: #E91E63;
            content: '受講者回答';
        }
        p{
            margin-bottom: 0;
            &.text{
                font-weight: bold;
            }
        }
    }
    p{
        margin-bottom: 0;
        &.note{
            color: #E91E63;
        }
    }
</style>
