<template>
    <div v-if="image !== null">
        <!--   Inputから呼んだときaspectRatioがあるとなぜか表示されないので     -->
        <v-img v-if="aspectRatio === undefined"
               :src="image"
               :max-width="maxWidth"
               @click="dialog = !dialog"></v-img>
        <v-img v-else
               :src="image"
               :aspect-ratio="aspectRatio"
               :max-width="maxWidth"
               @click="dialog = !dialog"></v-img>
        <v-dialog v-model="dialog" :max-width="dialogMaxWith">
            <div class="close_btn"
                 :style="'max-width:' + dialogMaxWith "
            >
                <v-btn icon @click="dialog = !dialog">
                    <v-icon>mdi-close-circle-outline</v-icon>
                </v-btn>
            </div>
            <v-img :src="image"></v-img>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: "ImageWithDialog",
    props: {
        value: {
            required: true
        },
        maxWidth: {
            type: String,
            default: '150px'
        },
        dialogMaxWith: {
            type: String,
            default: '600px'
        },
        aspectRatio: {
            // undefined指定で通常のサイズで表示
            type: String,
            default: undefined
        }
    },
    data() {
        return {
            image: null,
            dialog: false
        }
    },
    watch: {
        value(val) {
            this.setImageUrl();
        },
    },
    created() {
        this.setImageUrl();
    },
    methods: {
        setImageUrl() {
            if (this.value === undefined || this.value === null) {
                this.image = '';
                return
            }
            if (typeof this.value === "string") {
                this.image = this.value;
                return;
            }
            if (this.value.name.lastIndexOf('.') <= 0) {
                this.image = '';
                return
            }
            const fr = new FileReader()
            fr.readAsDataURL(this.value);
            fr.addEventListener('load', () => {
                this.image = fr.result
            })
        },
    }
}
</script>

<style scoped lang="scss">
.close_btn {
    position: absolute;
    z-index: 100;
    display: flex;
    justify-content: flex-end;
}
</style>
