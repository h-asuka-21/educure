<template>
    <div>
        <v-file-input v-if="data === null"
                      outlined
                      :label="label"
                      v-model="data"
                      dense
                      accept="image/png, image/jpeg"
                      :rules="[v => $validation.maxFileSize(v,max_size,label)]"
                      show-size
        >
        </v-file-input>
        <div class="image_thumbnail " v-else >
            <p class="subtitle">{{label}}</p>
            <div class="d-flex">
                <icon-close-button
                    @click="deleteItem()"
                ></icon-close-button>
                <image-with-dialog
                    v-model="data"
                ></image-with-dialog>
            </div>
        </div>
    </div>
</template>

<script>
    import ImageWithDialog from "../ImageWithDialog";
    import IconCloseButton from "../../Atoms/IconCloseButton";
    export default {
        name: "ImageInput",
        components: {IconCloseButton, ImageWithDialog},
        props: {
            label: {
                type: String,
                required: true
            },
            value: {
                required: true
            },
            max_size: {
                type: [String, Number],
                default: 2048 * 1024
            }
        },
        data() {
            return {
                data: this.value,
                image: null
            }
        },
        created() {
            this.setImageUrl();
        },

        watch: {
            value(val) {
                this.data = val;
            },
            data(val) {
                this.$emit('input', val);
            }
        },
        methods:{
            setImageUrl(){
                if (this.data === undefined || this.data === null || typeof this.data === "string") {
                    this.image = '';
                    return
                }
                if (this.data.name.lastIndexOf('.') <= 0) {
                    return
                }
                const fr = new FileReader()
                fr.readAsDataURL(this.data)
                fr.addEventListener('load', () => {
                    this.image = fr.result
                })
            },
            deleteItem(){
                this.data = null;
                this.image = null;
            }
        }

    }
</script>

<style scoped>

</style>
