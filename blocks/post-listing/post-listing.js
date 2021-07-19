import Vue from '../../resources/node_modules/vue/dist/vue.esm.browser.min.js'
import postSearch from './postSearch.vue'

if(document.querySelector('#post-app')){
  Vue.config.devtools = window.location.href.indexOf('devbucket') !== -1

  new Vue({
    el: '#post-app',
    components: { postSearch }
  })
}