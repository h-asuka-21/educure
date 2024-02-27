import Vue from 'vue'
import $axios from "~/.nuxt/axios";

const $this = Vue.prototype;
const $utils = {
    empty(val) {
        if (val === undefined || val === null || val === '' || val === 0) {
            return true;
        }
    },
    success(resp) {
        if (resp.data.message !== undefined && resp.data.message !== null) {
            $this.$nuxt.$store.dispatch('alert/success', resp.data.message);
        }
    },
    catchError(error) {
        console.error(error);
        const store = $this.$nuxt.$store;
        if (error.response === undefined) {
            store.dispatch('alert/error', '処理に失敗しました。しばらくしてからもう一度お試しください');
            return;
        }

        const resp = error.response;
        console.error(resp);
        if (resp.status === undefined) {
            store.dispatch('alert/error', '通信に失敗しました。しばらくしてからもう一度お試しください');
            return;
        }
        switch (resp.status) {
            case 500:
            case 504:
            case 422:
                // サーバーエラー
                if (resp.data.message === 'Unauthorized') {
                    // 認証エラー（たまに500で認証エラーが帰ってくる）
                    store.dispatch('alert/error', 'ユーザー認証に失敗しました。再度ログインしてください。');
                    store.dispatch('user/clearStatus');
                    break;
                }
                if (resp.data.message !== undefined && resp.data.message !== 'fail' && /[^\x01-\x7E].*/.test(resp.data.message)) {
                    // 指定された日本語が含まれるメッセージがある場合
                    console.log(resp.data.message);
                    store.dispatch('alert/error', resp.data.message);
                    break;
                }
                store.dispatch('alert/error', '通信に失敗しました。しばらくしてからもう一度お試しください');
                break;
            case 404:
                // 404ページにリダイレクト
                $this.$nuxt.error({statusCode: 404});
                break;
            case 401:
                store.dispatch('alert/error', 'ユーザー認証に失敗しました。再度ログインしてください。');
                this.logout();
                break;
        }
    },
    logout() {
        if ($this.$nuxt.$route.path.indexOf('/master') === 0) {
            return $this.$nuxt.$store.dispatch('admin/clearStatus');
        } else if ($this.$nuxt.$route.path.indexOf('/admin') === 0) {
            return $this.$nuxt.$store.dispatch('user/clearStatus');
        }
        return $this.$nuxt.$store.dispatch('student/clearStatus');
    },
    clearAlert() {
        const store = $this.$nuxt.$store;
        store.dispatch('alert/clear');
    },
    loader: {
        show() {
            const store = $this.$nuxt.$store;
            store.dispatch('loader/show');
        },
        hide() {
            const store = $this.$nuxt.$store;
            store.dispatch('loader/hide');
        }
    },
    getTax(price) {

        return Math.floor(Number(price) * 0.1);
    },
    total(worker) {
        let result = Number(worker.wage) + this.getTax(worker.wage) + Number(worker.travel_expenses);
        return result.toLocaleString();
    },
    formatDate(date, time = false) {
        if (date === undefined || date === null) {
            return '';
        }
        if (time) {
            return $this.$moment(date).format('Y年M月D日 HH:mm');
        }
        return $this.$moment(date).format('Y年M月D日')
    },
    formatTime(time) {
        const today = $this.$nuxt.$moment().format('YYYY-MM-DD');
        return $this.$nuxt.$moment(today + ' ' + time).format('HH:mm');
    },
    priceFormat(price) {
        if (price === undefined || price === null) {
            return '0';
        }
        if (Number(price) === 0 || price === '0') {
            return '0';
        }
        return Number(price).toLocaleString();
    },
    isLogin() {
        if ($this.$nuxt.$route.path.indexOf('/master') === 0) {
            return $this.$nuxt.$store.state.admin.is_login;
        } else if ($this.$nuxt.$route.path.indexOf('/admin') === 0) {
            return $this.$nuxt.$store.state.user.is_login;
        }
        return this.is_login = $this.$nuxt.$store.state.student.is_login;
    },
    getThemeClass() {
        if ($this.$nuxt.$route.path.indexOf('/master') === 0) {
            return 'master';
        } else if ($this.$nuxt.$route.path.indexOf('/admin') === 0) {
            return 'admin';
        }
        return 'student'
    },
    getUserName() {
        if (this.getThemeClass() === 'master') {
            return $this.$nuxt.$store.state.admin.data.name;
        } else if (this.getThemeClass() === 'admin') {
            return $this.$nuxt.$store.state.user.data.name;
        }
        return this.is_login = $this.$nuxt.$store.state.student.data.name;
    },
    getCompanyName() {
        if (this.getThemeClass() === 'master') {
            return '';
        } else if (this.getThemeClass() === 'admin') {
            return $this.$nuxt.$store.state.user.data.company_name;
        }
        return this.is_login = $this.$nuxt.$store.state.student.data.company_name;
    },
    /**
     * @param string
     * @return {string|*}
     */
    escapeHtml(string) {
        if (typeof string !== 'string') {
            return string;
        }
        return string.replace(/[&'`"<>]/g, function (match) {
            return {
                '&': '&amp;',
                "'": '&#x27;',
                '`': '&#x60;',
                '"': '&quot;',
                '<': '&lt;',
                '>': '&gt;',
            }[match]
        });
    },
    formatBytes(bytes, decimals) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024,
            dm = decimals <= 0 ? 0 : decimals || 2,
            sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
            i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    },
    getApiUrl(baseUrl, setType = false, without_id = false) {
        if ($this.$nuxt.$route.params.id && !without_id) {
            baseUrl += '/' + $this.$nuxt.$route.params.id;
        }
        if (setType) {
            baseUrl = this.getApiType() + baseUrl;
        }
        return baseUrl;
    },
    getApiType() {
        if ($this.$nuxt.$route.path.indexOf('/master') === 0) {
            return '/admin';
        } else if ($this.$nuxt.$route.path.indexOf('/admin') === 0) {
            return '/user';
        }
        return '/student'
    },

    getHTTPMethod() {
        if ($this.$nuxt.$route.params.id) {
            return 'PUT'
        }
        return 'POST';
    },
    getHomeUrl() {
        switch (this.getThemeClass()) {
            case 'master':
                return '/master';
            case 'admin':
                return '/admin';
            default:
                return '/';
        }
    },
    getWeekStr(day) {
        const week = ["日", "月", "火", "水", "木", "金", "土"];
        return week[day.format('d')];
    },
    isStudent() {
        return this.getThemeClass() === 'student';
    },
    isUser() {
        return this.getThemeClass() === 'admin';
    },
    isAdmin() {
        return this.getThemeClass() === 'master';
    },
    openNewTab(url) {
        window.open(url + '?new_tab=true', '_blank')
    }

};
$this.$utils = $utils;
export default (context) => {
    context.$utils = $utils;
};

