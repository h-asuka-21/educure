module.exports = {
    endpoints: {
        login: {
            url: 'admin/auth/login', method: 'post', propertyName: 'admin_token'
        },
        user: {
            url: 'admin/auth/me', method: 'get', propertyName: 'admin_data'
        },
        logout: {
            url: 'admin/auth/logout', method: 'post'
        },
        refresh: {
            url: 'admin/auth/refresh', method: 'post', propertyName: 'admin_token'
        }
    },
    token: {
        property: 'admin_token',
        maxAge: 60 * 60
    },
    refreshToken: {
        maxAge: 20160 * 60
    },
};
