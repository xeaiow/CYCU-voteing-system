require('./bootstrap')

import Vue from 'vue'
import VueImg from 'v-img'
import App from './components/App'
import router from './router'
import VueMeta from 'vue-meta'

Vue.use(VueMeta, {
  // optional pluginOptions
  refreshOnceOnNavigation: true
})

const vueImgConfig = {
  // Use `alt` attribute as gallery slide title
  altAsTitle: false,
  // Display 'download' button near 'close' that opens source image in new tab
  sourceButton: true,
  // Event listener to open gallery will be applied to <img> element
  openOn: 'click',
  // Show thumbnails for all groups with more than 1 image
  thumbnails: false
}
Vue.use(VueImg, vueImgConfig)

const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
})