import Vue from 'vue';

const $this = Vue.prototype;
const $validation = {
     required(v,label,no_message=false,disallow_zero = false){
        if(disallow_zero){
            if(v === '0' || v=== 0){
                if(no_message){
                    return '';
                }
                return label + 'は必須です';
            }
        }
        if(typeof v === "string" && /^ |^　/.test(v)){
            return label + 'は必須です';
        }
        if(no_message){
            return !!v || '';
        }
        if(v instanceof Array){
            if(v.length === 0){
                return label + 'は必須です';
            }
        }
        return !!v
            || v === 0
            || label + 'は必須です';
    },
    zip_code:[
        v => {
            if(v === undefined || v=== null || v === ''){
                return true;
            }
            return /^\d{3}-?\d{4}$/.test(v) || '正しい郵便番号を入力してください'
        }
    ],
    tel:[
        v => {
            if(v === undefined || v=== null || v === ''){
                return true;
            }
            return /^0\d{1,4}-\d{1,4}-\d{3,4}$/.test(v) || '正しい電話番号を入力してください。'
        }
    ],
    maxFileSize(v,size,label){
        return !v || v.size < size || label + 'は' + $this.$utils.formatBytes(size) + '以下のファイルをアップロードしてください。';
    },
    email(v) {
        if(/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(v)){
            return true;
        }
        return '正しいメールアドレスを入力してください'
    },
    password(v) {
        if (/^[a-z\d]{8,32}$/i.test(v)) {
            return true;
        }
        return 'パスワードは半角英数字で8文字以上32文字以下で入力してください'
    },
    timeBetween(v,from,to=null,fail_message=''){
        if(v === null || v === undefined){
            return true;
        }
        const target = $this.$nuxt.$moment();
        let splited = v.split(':');
        target.set('hours', splited[0]).set('minute', splited[1]);
        from = $this.$nuxt.$moment().set('hour',from.split(':')[0]).set('minute',from.split(':')[1])
        if(to === null){
            // fromよりあとならOK
            if(!target.isSameOrAfter(from)){
                return fail_message;
            }
        }
        to = $this.$nuxt.$moment().set('hour',to.split(':')[0]).set('minute',to.split(':')[1])
        console.log(target.format('HH:mm'))
        console.log(from.format('HH:mm'));
        console.log(to.format('HH:mm'));
        console.log(target.isSameOrAfter(from));
        console.log(target.isSameOrAfter(from) && target.isSameOrBefore(to));
        if(!(target.isSameOrAfter(from) && target.isSameOrBefore(to))){
            return fail_message;
        }
        return true;
    },
    kana(v){
         if(/^([ 　ァ-ンー])+$/.test(v)){
             return true;
         }
         return '全角カタカナと半角全角スペースのみで入力してください'
    },
    name(v){
        if(/^([ 　\u30a0-\u30ff\u3040-\u309f\u3005-\u3006\u30e0-\u9fcf])+$/.test(v)){
            return true;
        }
        return '全角ひらがな、カタカナ、漢字、半角全角スペースのみで入力してください'
    },
    same(v, v1, label) {
        return v1 === v || label + 'と同じ値を入力してください'
    },
    notSame(v,v1,label){
        return v1 !== v || label+'と異なる値を入力してください'
    }

};
$this.$validation = $validation;
export default (context) => {
    context.$validation = $validation;
};
