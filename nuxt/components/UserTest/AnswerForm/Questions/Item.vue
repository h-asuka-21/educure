<template>
    <v-card outlined class="fill-height question_card">
        <v-card-title class="card_title">
            <p>
                {{ data.name }}
            </p>
        </v-card-title>
        <v-card-text>
            <div class="question_content">
                <html-element :content="data.content"/>
            </div>
            <div class="question_image" v-if="data.image !== null">
                <image-with-dialog v-model="data.image" dialog-max-with="900px" maxWidth="700px"></image-with-dialog>
            </div>
            <v-row dense>
                <v-col cols="12" id="answer_col">
                    <v-radio-group id="choices" v-model="data.choices" @input="v =>setChoices(v)" row>
                        <v-col v-for="n in 4" :key="n" cols="6">
                            <v-row dense align="center">
                                <v-radio
                                    :label="data['choice' + n]"
                                    :id="'question_choice' + n"
                                    :value="n"
                                    @input="v =>setChoices(v)"
                                    class="answer_radio"
                                ></v-radio>
                            </v-row>
                        </v-col>
                    </v-radio-group>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
import ImageWithDialog from "@/components/Molecules/ImageWithDialog";
import HtmlElement from "@/components/Molecules/HtmlElement";

export default {
    name: "Item",
    components: {HtmlElement, ImageWithDialog},
    props: {
        item: {
            type: Object,
            required: true
        },
        show: {
            type: Boolean,
            default: true
        },
        item_key:{
            type:Number,
            required: true
        }
    },
    data() {
        return {
            data: JSON.parse(JSON.stringify(this.item)),
            image: null,
        }
    },
    watch: {
        data: {
            handler(val) {
                console.log(val);
                this.$store.dispatch('test/setAnswer',{param:val,key:this.item_key});
            },
            deep: true
        }
    },
    methods:{
        setChoices(v){
            console.log(v);
            this.data.choices = v;
        }
    }

}
</script>

<style scoped lang="scss">
.card_title {
    padding-top: 4px;
    padding-bottom: 4px;

    > .buttons {
        position: relative;
        left: 10px;
        top: -4px;
    }
}
</style>
