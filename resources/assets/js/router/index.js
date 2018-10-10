import Vue from 'vue'
import Router from 'vue-router'
import VueImg from 'v-img'
import SuiVue from 'semantic-ui-vue'
import Notifications from 'vue-notification'

Vue.use(Notifications)
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


import Activity from '../components/Activity'
import ActivityId from '../components/ActivityId'
import ActivityIdGroup from '../components/ActivityIdGroup'
import Login from '../components/Login'
import finish from '../components/finish';
import finishId from '../components/finishId';
import Pineapple from '../components/manager/Pineapple';
import GroupsCreate from '../components/manager/GroupsCreate';
import PineappleLogin from '../components/manager/PineappleLogin';
import ManagerActivityLists from '../components/manager/ActivityLists';
import PineappleGroups from '../components/manager/PineappleGroups';
import NotFound from '../components/404';


export default new Router({
    mode: 'history',
    routes: [{
            path: '/',
            component: Activity
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
            path: '/activity/:id',
            props: true,
            component: ActivityId,
            beforeEnter: (to, from, next) => {

                axios.get('//127.0.0.1:8000/groups/info/' + to.params.id).then(response => {

                    if (!response.data.status) {
                        next({ path: '/404' });
                    }
                    else {
                        next();
                        this.items = response.data.groups;
                        this.info = response.data.activity;
                    }   
                });
            }
        },
        {
            path: '/group/:id',
            props: true,
            component: ActivityIdGroup
        },
        {
            path: '/pineapple/login',
            component: PineappleLogin
        },
        {
            path: '/pineapple',
            component: Pineapple,
            beforeEnter: (to, from, next) => {
                axios.get('//127.0.0.1:8000/pineapple/login/status').then(res => {

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
            component: GroupsCreate
        },
        {
            path: '/pineapple/activity',
            component: ManagerActivityLists
        },
        {
            path: '/pineapple/groups/:id',
            component: PineappleGroups
        },
        {
            path: '/404',
            component: NotFound
        },
        {
            path: '*',
            name: '/404',
            component: NotFound
        },
    ]
})