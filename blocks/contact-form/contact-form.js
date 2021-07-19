import Vue from 'vue'
import r29ContactForm from './r29ContactForm.vue'

~($ => {
  function init(){
    if(document.querySelector('#contact-form')){
      new Vue({
        el: '#contact-form',
        components: { r29ContactForm }
      })
    }
  }

  $(document).ready(init)
})(jQuery)