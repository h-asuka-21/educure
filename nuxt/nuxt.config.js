import ja from 'vuetify/es5/locale/ja';
const ENV = require('dotenv').config().parsed;
export default {
    /*
    ** Nuxt rendering mode
    ** See https://nuxtjs.org/api/configuration-mode
    */
    mode: 'universal',
    /*
    ** Nuxt target
    ** See https://nuxtjs.org/api/configuration-target
    */
    target: 'server',
    /*
    ** Headers of the page
    ** See https://nuxtjs.org/api/configuration-head
    */
    head: {
        title: 'educure',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: 'educure'},
            { name: 'robots', content: 'noindex'},
        ],
        link: [
            {rel: 'icon', type: 'image/svg+xml', href: '/image/educure_logo.png'},
            {rel:'stylesheet',href:'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900'},
            {rel:'stylesheet',href:'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css'},
            {rel:'stylesheet',href:'https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap'},
        ]
    },
    /*
    ** Global CSS
    */
    css: [
        '~/assets/scss/app.scss',
        'vue-wysiwyg/dist/vueWysiwyg.css',
        'vue-tour/dist/vue-tour.css'
    ],
    /*
    ** Plugins to load before mounting the App
    ** https://nuxtjs.org/guide/plugins
    */
    plugins: [
        '~/plugins/sidebars.js',
        '~/plugins/colors.js',
        '~/plugins/utils.js',
        '~/plugins/apis.js',
        '~/plugins/urls.js',
        '~/plugins/selects.js',
        '~/plugins/validation.js',
        '~/plugins/vue-chartjs.js',
        '~/plugins/libs.js',
        { src: '~/plugins/localStorage.js', ssr: false }
    ],
    /*
    ** Auto import components
    ** See https://nuxtjs.org/api/configuration-components
    */
    components: true,
    /*
    ** Nuxt.js dev-modules
    */
    buildModules: [
        '@nuxtjs/dotenv',
        ['@nuxtjs/vuetify', {
            customVariables: ['~/assets/variables.scss'],
            lang:{
                locales:{ja},
                current:'ja'
            },
            theme: {
                light: {
                    primary: '#607d8b',
                    secondary: '#009688',
                    accent: '#ff9800',
                    error: '#ff5722',
                    warning: '#ffc107',
                    info: '#00bcd4',
                    success: '#03a9f4',
                    title_bg: '#505050',
                    sidebar: '#A5A4BF',
                    master_sidebar: '#6200EE'
                },
            }
        }]
    ],
    /*
    ** Nuxt.js modules
    */
    modules: [
        '@nuxtjs/axios',
        ['@nuxtjs/moment', ['ja']],
        ['cookie-universal-nuxt', { parseJSON: false }]
    ],
    // env: {
    //     API_URL:ENV.API_URL,
    //     BASE_URL:ENV.BASE_URL
    // },
    /*
    ** Axios module configuration
    ** See https://axios.nuxtjs.org/options
    */
    axios: {},
    /*
    ** Build configuration
    ** See https://nuxtjs.org/api/configuration-build/
    */
    build: {}
}
