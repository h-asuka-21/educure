module.exports = {
    endpoints: {
        login: {
            url: 'user/auth/login', method: 'post', propertyName: 'user_token'
        },
        user: {
            url: 'user/auth/me', method: 'get', propertyName: 'user_data'
        },
        logout: {
            url: 'user/auth/logout', method: 'post'
        },
        refresh: {
            url: 'user/auth/refresh', method: 'post', propertyName: 'user_token'
        }
    },
    token: {
        property: 'user_token',
        maxAge: 60 * 60
    },
    refreshToken: {
        maxAge: 20160 * 60
    },
};
