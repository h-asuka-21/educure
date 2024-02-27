<template>
    <div id="error_page">
        <v-row dense>
            <v-col cols="6">
                <v-img src="/image/educure_logo.png" alt="educure" id="logo"/>
            </v-col>
            <v-col cols="12">
                <span class="error_code">{{ error.statusCode }}</span>
                <span class="message">{{ getMessage(error.statusCode) }}</span>
            </v-col>
            <v-col cols="12">
                <span v-if="error.statusCode===404" class="description">
                    大変申し訳ございませんが、お探しのページは、<br>
                    削除されたか、ファイル名が間違っている可能性があります。<br>
                    アドレスを確認してください。
                </span>
                <span v-else-if="error.statusCode === 500" class="description">
                    サーバーエラーが発生しました。<br>
                    サーバーの問題でお探しのページを表示できません。<br>
                    再度時間をおいてアクセスしてください。<br>
                </span>
                <span v-else-if="error.statusCode === 503" class="description">
                    一時的にアクセスできません。<br>
                    混雑のためアクセスしにくくなっています。<br>
                    再度時間をおいてアクセスしてください。<br>
                </span>
            </v-col>
            <v-col>
                <v-btn dark :color="$colors.main" @click="$router.back()">
                    <v-icon>mdi-skip-previous</v-icon>
                    戻る
                </v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import NotFound from "@/components/Molecules/NotFound";

export default {
    components: {NotFound},
    props: ['error'],
    created() {
        console.log(this.error);
        this.$store.dispatch('page/sideBar', false);
    },
    methods: {
        getMessage(code) {
            switch (code) {
                case 404:
                    return 'Not Found';
                case 500:
                    return 'Server Error';
                case 502:
                    return 'Bad Gateway';
                case 503:
                    return 'Service Unavailable';
                default:
                    return 'Error';
            }
        }
    }
}
</script>

<style scoped lang="scss">
#error_page {
    max-width: 600px;
    margin: 40px auto;

    .error_code {
        color: #616161;
        letter-spacing: -16px;
        line-height: 8rem;
        font-size: 10rem;
    }

    .message {
        padding-left: 30px;
        font-size: 3rem;
        letter-spacing: -1px;
        font-weight: bold;
        color: #00BCD4;
    }
    .description {
        color: #424242;
        letter-spacing: -1px;
    }
}
</style>
