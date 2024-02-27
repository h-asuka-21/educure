// 行数が増えたので認証は別ファイルに分割
module.exports = {
    redirect: {
        login: false,
        logout: false,
        callback: false,
        home: false
    },
    strategies: {
        user: require('./user.js'),
        master: require('./admin.js'),
        student: require('./student.js'),
    }
};
