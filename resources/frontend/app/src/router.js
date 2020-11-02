import Vue from 'vue';
import Router from 'vue-router';
import Welcome from './views/Welcome.vue';
import PageNotFoundError from './views/PageNotFoundError.vue'

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: '/',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Welcome,
    },
    {
      path: '/profile',
      name: 'profile',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "about" */ './views/Profile.vue'),
      meta: {
        layout: 'error',
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import(/* webpackChunkName: "login" */ './views/Auth/Login.vue'),
      meta: {
        layout: 'auth',
        title: 'Login'
      },
    },
    {
      path: '*',
      name: 'page-not-found',
      component: PageNotFoundError,
      meta: { layout: 'error' }
    },
  ],
});
