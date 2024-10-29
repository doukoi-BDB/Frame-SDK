import App from './App'

// #ifndef VUE3
import Vue from 'vue'

import store from './store'
import {
	api
} from "Frame-SDK/Demo/日程项目/unapp-web/common/api.js"
//时间格式发
import moment from "Frame-SDK/Demo/日程项目/unapp-web/common/moment.min.js";

import './uni.promisify.adaptor'
Vue.config.productionTip = false
App.mpType = 'app'

const toast = s => {
	uni.showToast({
		title: s,
		icon: 'none'
	})
}
Vue.prototype.$msg = toast;
Vue.prototype.$api = api;
Vue.prototype.$store = store;
Vue.prototype.$moment = (date, type = 'YYYY-MM-DD HH:mm') => {
	return moment(date).format(type);
};
const app = new Vue({
  ...App
})
app.$mount()
// #endif

// #ifdef VUE3
import { createSSRApp } from 'vue'
export function createApp() {
  const app = createSSRApp(App)
  return {
    app
  }
}
// #endif