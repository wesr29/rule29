import Vue from 'vue'
import r29ContactPageForm from './r29ContactPageForm.vue'

~($ => {
  function init(){
    if(document.querySelector('#contact-page-form')){
      new Vue({
        el: '#contact-page-form',
        components: { r29ContactPageForm }
      })
    }
  }

  $(document).ready(init)
})(jQuery)