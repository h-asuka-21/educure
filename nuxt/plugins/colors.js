import Vue from 'vue'

const $colors = {
    main:'teal',
    sub:'cyan',
    check: 'deep-orange',
    error: 'red',
    master:{
        sidebar_active:'purple lighten-3'
    },
    admin:{
        sidebar_active:'cyan lighten-3'
    },
    student:{
        sidebar_active:'teal lighten-3'
    }
};

Vue.prototype.$colors = $colors;
export default (context) => {
    context.$colors = $colors;
};
