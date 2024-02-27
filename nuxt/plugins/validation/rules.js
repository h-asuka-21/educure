import moment from "moment";

const rules = {
    name: [
        (v) => funcs.required(v, '名前')
    ],
    address: [
        (v) => funcs.required(v, '住所')
    ],

    kana: [
        // v => funcs.required(v,'カナ'),
        v => /^[ァ-ヶー　 ]*$/.test(v) || 'カタカナとスペースのみ入力可能です。'
    ],
    employee_number: [
        v => {
            if(!(/^[a-zA-Z0-9]*$/.test(v)) ) return 'スタッフコードは半角英数字のみ使用可能です。';
            return true;
        }
    ],
    tel: [
        v => /^0\d{1,4}-\d{1,4}-\d{3,4}$/.test(v) || '正しい電話番号を入力してください',
    ],
    int: [
        v => {
            if(v === null || v === undefined || v=== '') return true;
            return !/[^[0-9]]*$/.test(v) || '半角数字のみで入力してください。'
        }
    ],
    rest_time(v, absent_flg) {
        if((absent_flg === 1 || absent_flg === true) && (v === undefined || v === null || v === '' || v === 0 || v === '0')){
            // 欠勤の場合
            return true;
        }
        if (/[^[0-9]]*$/.test(v)) {
            return '半角数字のみで入力してください。';
        }
        return true;
    },
    clockIn(v, absent_flg) {
        if ((absent_flg === 1 || absent_flg === true) && (v === undefined || v === null || v === '' || v === 0 || v === '0')) {
            // 欠勤の場合
            return true;
        }
        if(!v){
            return '出勤時刻は必須です。';
        }
        return true;
    },

    time: [
        v => {
            if(v === null || v === undefined || v=== '') return true;
            return /^[0-9]{2}:[0-9]{2}$/.test(v) || '正しい時間を入力してください';
        }
    ],
    grant_days: [
        v => funcs.required(v, '付与日数'),
        v => !/[^[0-9]]*$/.test(v) || '半角数字のみで入力してください。'
    ],
    lest_time: [
        v => funcs.required(v, '休憩時間'),
        v => !/[^[0-9]]*$/.test(v) || '半角数字のみで入力してください。'
    ],
    leftover_days: [
        v => funcs.required(v, '残日数'),
        v =>  !/[^[0-9]]$/.test(v) || '半角数字のみで入力してください。'
    ],
    email: [
        v => funcs.required(v,'メールアドレス'),
        v => /.+@.+\..+/.test(v) || '正しいメールアドレスを入力してください',
    ],
    password: [
        v => funcs.required(v,'パスワード'),
        v => (v && v.length >= 8 && v.length <= 32) || 'パスワードは8文字以上32文字以内で指定したください',
        v => /^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z\d]{8,32}$/.test(v) || 'パスワードは半角英数字をそれぞれ1種類以上含む、8文字以上32文字以内で指定したください'
    ],
    client:[
        v => funcs.required(v,'クライアント'),
    ],
    project_name:[
        v => funcs.required(v,'案件名'),
    ],
    store_name: [
        v => funcs.required(v, '店舗名'),
    ],
    grant_date: [
        v => funcs.required(v, '付与日'),
    ],
    status: [
        v => funcs.required(v, 'ステータス', true),
        v => v !== 99 || '仮シフトは登録できません。他のステータスを選択してください。'
    ],
    time_card: {
        remarks(v,action){
            if(action === 4){
                return funcs.required(v, '遅刻の場合備考');
            }
            if(action === 5){
                return funcs.required(v, '早退の場合備考');
            }
            if(action === 6){
                return funcs.required(v, '欠勤の場合備考');
            }
            return true;
        }
    },
    time_card_store_worker(v, clock_in, clock_out) {
        if (v === '') {
            return true;
        }
        if ((clock_in !== null && clock_in !== '') || (clock_out !== null && clock_out !== '')) {
            if (v === null || v === undefined) {
                return '出退勤を登録する場合出勤先は必須です。'
            }
        }
        return true;
    },
    store_worker(v,status){
        if(v === undefined || status === undefined){
            return true;
        }
        if(status === 0){
            return funcs.required(v, '出勤先');
        }
        return true
    },
    clock_in(v,status){
        if(status === 0){
            return funcs.required(v, '出勤時刻');
        }
        return true
    },
    clock_out(v,status){
        if(status === 0){
            return funcs.required(v, '退勤時刻');
        }
        return true
    },
    clock_out_list(v,date,clock_in,next_day){
        let message = '';
        if(v === null || v === undefined || v=== '') return true;
        if(!/^[0-9]{2}:[0-9]{2}$/.test(v)){
            return '半角英数字のみで入力してください';
        }
        const moment = require('moment');
        let _clock_in = moment(date.format('YYYY-MM-DD') + ' ' + clock_in);
        let _clock_out = moment(date.format('YYYY-MM-DD') + ' ' + v);
        if(!_clock_out.isValid() || !_clock_in.isValid()){
            return true;
        }
        if(next_day === true){
            _clock_out.add(1, 'day').format('YYYY-MM-DD');
        }
        if(_clock_in.isAfter(_clock_out)){
            return '退勤時刻は、出勤よりあとに設定してください。';
        }
        return true;
    },
    password_confirm(v, data) {
        let result = funcs.required(v, 'パスワード（確認）');
        if (result !== true) return result;
        if (v !== data.password) return 'パスワードと同じ値を入力してください';
        if (v === data.current_password) return '現在と同じパスワードは設定できません';
        return true;
    },
    current_password(v,data){
        if(data.password !== '' || data.password_confirm){
            // 新しいパスワードが入力されているとき
            let result = funcs.required(v, '現在のパスワード');
            if (result !== true) return result;
        }
    },
    date(v){
        return /^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/.test(v)|| '正しい日付を入力してください'
    },
    bulkShift(v, status,before) {
        if(before||status!== 0){
            return true;
        }
        return !!v || '';
    },

};
const funcs = {
    required(v, name,zero_flg=false) {
        if(zero_flg && v === 0){
            return true;
        }
        return !!v || name + 'は必須です'
    }
};
export default rules;
