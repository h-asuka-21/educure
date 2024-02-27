import Vue from 'vue'

const $apis = {
    auth: {
        login: '/auth/login',
        logout: '/auth/logout',
        me: '/auth/me',
        refresh: '/auth/refresh'
    },
    test: '/test',
    test_results: '/test/results',
    // 企業
    company: '/company',
    // コース
    course: '/course',
    course_curriculums: '/course/curriculum',
    // カリキュラム
    curriculum: '/curriculum',
    // 管理者
    user: '/user',
    // 受講者
    student: '/student',
    student_progress: '/student/progress',
    autocomplete:{
        course: '/course/autocomplete',
        course_group: '/course_group/autocomplete',
        course_group_student: '/course_group_student/autocomplete',
        curriculum: '/curriculum/autocomplete',
        curriculum_group: '/curriculum_group/autocomplete',
        schedule: '/schedule_user/autocomplete',
        steps: '/step/autocomplete',
        company:'/company/autocomplete',
        test:'/test/autocomplete',
    },
    // スケジュール
    schedule: '/schedule',
    schedule_bulk: '/schedule/bulk',
    // カレンダー（管理者画面用）
    calendar: '/schedule/calendar',
    schedule_student: '/schedule/student',
    // 担当スケジュール
    teacher_schedule: '/teacher_schedule',

    reserve:'/reserve',
    today_reserve: '/reserve/today',

    progress: '/progress',
    progress_attendance: '/progress/attendance',
    // 企業トップ進捗一覧API
    progress_students:'/progress/students',
    has_test: '/test/has_test',
    current_progress: '/progress/current',
    interview_history: '/interview_history',
    interview_history_student: '/interview_history/student',
    interview_histories: '/interview_history/history',

    // 評価不足項目
    missing_evaluation_items: '/missing_evaluation/history',

    step:'/step',
    student_progress_list:'/student/progress_list',
    student_evaluation_ranking:'/student/evaluation_ranking',
    student_self_ranking:'/student/self_ranking',
    evaluation:'/student/evaluation',
    delay_students: '/student/delay',
    low_evaluation_students: '/student/low_evaluation',
    not_attended_students: '/student/not_attended',
    company_ranking: '/student/company_ranking',
    reserved_students: '/student/reserve',
    student_statistics: '/student/student_statistics',
    company_statistics: '/student/company_statistics',
    student_detail:'/student/detail',
    // スコア一覧
    scores: '/scores',
    teacher_evalution:'/student/teacher_evalution',
    save_student_progoress: '/progress/save_student_progoress'
};
Vue.prototype.$apis = $apis;
export default (context) => {
    context.$apis = $apis;
};
