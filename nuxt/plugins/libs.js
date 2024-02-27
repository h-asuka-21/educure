import Vue from 'vue';

// wysiwygエディタ
import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg);

// ★評価
import StarRating from 'vue-star-rating'
Vue.component('star-rating',StarRating);

// ドラッグアンドドロップ
import vuedraggable from 'vuedraggable'
Vue.component('draggable',vuedraggable);

// タイマー（使ってない）
import CircularCountDownTimer from "vue-circular-count-down-timer";
Vue.use(CircularCountDownTimer);

import InfiniteLoading from "vue-infinite-loading";
Vue.use(InfiniteLoading);

import VueTour from 'vue-tour'
Vue.use(VueTour)
