import Vue from 'vue'
import Router from 'vue-router'

const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [{
            path: '/',
            component: () => import('../components/Activity.vue')
        },
        {
            path: '/done',
            component: () => import('../components/finish.vue')
        },
        {
            path: '/done/:id',
            component: () => import('../components/finishId.vue')
        },
        {
            path: '/activity/:id',
            props: true,
            component: () => import('../components/ActivityId.vue')
        },
        {
            path: '/team/:id',
            props: true,
            component: () => import('../components/ActivityIdGroup.vue')
        },
        {
            path: '/pineapple/login',
            component: () => import('../components/manager/PineappleLogin.vue')
        },
        {
            path: '/pineapple',
            component: () => import('../components/manager/Pineapple.vue'),
            beforeEnter: (to, from, next) => {
                axios.get('/pineapple/login/status').then(res => {

                    if (res.data != "") {
                        next();
                        return false;
                    }
                    window.location.href = '/pineapple/login';
                });
            }
        },
        {
            path: '/pineapple/groups/create/:id',
            props: true,
            component: () => import('../components/manager/GroupsCreate.vue')
        },
        {
            path: '/pineapple/activity',
            component: () => import('../components/manager/ActivityLists.vue')
        },
        {
            path: '/pineapple/groups/:id',
            component: () => import('../components/manager/PineappleGroups.vue')
        },
        {
            path: '/404',
            component: () => import('../components/404.vue')
        },
    ]
})