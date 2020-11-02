import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store/index';
import helper from './helper';

import DefaultLayout from './components/Layouts/DefaultLayout.vue';
import AuthLayout from './components/Layouts/AuthLayout.vue';
import ErrorLayout from './components/Layouts/ErrorLayout.vue';

Vue.config.productionTip = false;

Vue.component('default-layout', DefaultLayout);
Vue.component('auth-layout', AuthLayout);
Vue.component('error-layout', ErrorLayout);

window.axios = require('axios');

axios.defaults.baseURL = 'http://localhost/api/';
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('access_token');

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },

  error => Promise.reject(error),
);

const DEFAULT_TITLE = 'Laravel-vue-SPA-starter'

router.beforeEach((to, from, next) => {
	if(to.matched.some(record => record.meta.requiresAuth)){
    return helper.check().then(response => {
      if (!response) {
        return next({
          name: 'login'
        })
      }
      return next()
    })
  } else if(to.matched.some(record => record.meta.requiresVisitor)){
    return helper.check().then(response => {
      if (response) {
        return next({
          name: 'profile'
        })
      }
      return next()
    })
  } else {
    next()
  }
})

router.afterEach((to, from) => {
  document.title = to.meta.title || DEFAULT_TITLE;
});

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
