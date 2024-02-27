import Vue from 'vue'

const $sidebars = {
    master:[
        {
            title:'ダッシュボード',
            icon:'mdi-home-outline',
            url: '/master'
        },
        {
            title:'企業',
            icon:'mdi-office-building',
            url: '/master/company'
        },
        {
            title:'テスト',
            icon:'mdi-pencil',
            url: '/master/test'
        },
        {
            title:'コース',
            icon:'mdi-school',
            url: '/master/course'
        },
        {
            title:'カリキュラム',
            icon:'mdi-file-table-outline',
            url: '/master/curriculum'
        },
        {
            title:'進捗・受講',
            icon:'mdi-account-box-multiple-outline',
            url: '/master/progress'
        },
    ],
    admin:[
        {
            title:'ダッシュボード',
            icon:'mdi-home-outline',
            url: '/admin'
        },
        {
            title:'受講者',
            icon:'mdi-face',
            url: '/admin/student'
        },
        {
            title:'テスト',
            icon:'mdi-pencil',
            url: '/admin/test'
        },
        {
            title:'カリキュラム',
            icon:'mdi-file-table-outline',
            url: '/admin/curriculum'
        },
        {
            title:'管理者',
            icon:'mdi-account-cog-outline ',
            url: '/admin/user'
        },
    ],
    student:[
        {
            title:'ダッシュボード',
            icon:'mdi-home-outline',
            url: '/'
        },
        {
            title:'マイページ',
            icon:'mdi-face',
            url: '/mypage'
        },
        {
            title:'カリキュラム',
            icon:'mdi-notebook-edit-outline',
            url: '/curriculum'
        },
    ]
};

Vue.prototype.$sidebars = $sidebars;
export default (context) => {
    context.$sidebars = $sidebars;
};
