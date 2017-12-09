import Vue from 'vue'
import Router from 'vue-router'
import VueImg from 'v-img'
import SuiVue from 'semantic-ui-vue';

Vue.use(SuiVue);
Vue.use(Router)

const vueImgConfig = {
    // Use `alt` attribute as gallery slide title
    altAsTitle: true,
    // Display 'download' button near 'close' that opens source image in new tab
    sourceButton: false,
    // Event listener to open gallery will be applied to <img> element
    openOn: 'click',
}


Vue.use(VueImg, vueImgConfig);

import Example from '../components/Example'
import About from '../components/About'
import Activity from '../components/Activity'
import ActivityId from '../components/ActivityId'
import ActivityLists from '../components/ActivityLists'
import ActivityIdGroup from '../components/ActivityIdGroup'
import Login from '../components/Login'
import finish from '../components/finish';
import finishId from '../components/finishId';
import Pineapple from '../components/Pineapple';


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
        },
        {
            path: '/pineapple',
            component: Pineapple
        }
    ]
})