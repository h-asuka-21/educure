import Vue from 'vue'

Vue.prototype.$urls = {
    master:{
        // master管理画面
        prefix: '/master',
        urls:{
            test:{
                path:'/test',
                name: 'テスト管理',
                children:{
                    index:{
                        path:'/',
                        name: '一覧'
                    }
                }
            }
        }
    },
    admin:{
        // 講師・営業画面
        prefix: '/admin'
    },
    student:{
        // 講師・受講者画面
        prefix:''
    },
    getUrl(path,type='student'){
        console.log(this);
    }
};
