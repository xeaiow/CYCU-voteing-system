import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Example from '../components/Example'
import About from '../components/About'
import Activity from '../components/Activity'
import ActivityId from '../components/ActivityId'
import ActivityLists from '../components/ActivityLists'
import ActivityIdGroup from '../components/ActivityIdGroup'
import Login from '../components/Login'
import finish from '../components/finish';
import finishId from '../components/finishId';


export default new Router({
    mode: 'history',
    routes: [{
            path: '/',
            component: Activity
        },
        {
            path: '/login',
            component: Login
        },
        {
            path: '/about',
            component: About
        },
        {
            path: '/finished',
            component: finish
        },
        {
            path: '/finished/:id',
            component: finishId
        },
        {
            path: '/activity',
            component: Example
        },
        {
            path: '/activity/:id',
            props: true,
            component: ActivityId
        },
        {
            path: '/group/:id',
            props: true,
            component: ActivityIdGroup
        }
    ]
})