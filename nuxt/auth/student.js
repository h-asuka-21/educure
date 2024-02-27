module.exports = {
    provider: 'laravel/jwt',
    endpoints: {
        login: {//ログインを実行する際のリクエスト設定
            url: 'student/auth/login', method: 'post', propertyName: 'student_token'
        },
        user: {//ログイン済みのユーザ情報取得を実行する際のリクエスト設定
            url: 'student/auth/me', method: 'get', propertyName: 'student_data'
        },
        logout: {//ログアウトを実行する際のリクエスト設定
            url: 'student/auth/logout', method: 'post'
        },
        refresh: {
            url: 'student/auth/refresh', method: 'post', propertyName: 'student_token'
        }
    },
    token: {
        property: 'student_token',
        maxAge: 60 * 60
    },
    refreshToken: {
        maxAge: 20160 * 60
    },
};
