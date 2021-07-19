import iziSlider from '../../resources/js/plugins/iziSlider.js'

~(function($){
  function init() {
    if(document.querySelector('.rule-single--slides')){
      const rulesSlider = new iziSlider('.rule-single--slides', {
        addControls: false,
        addPagination: false,
        customNext: '.rule-single--next',
        customPrev: '.rule-single--prev',
        infinite: false,
        duration: 0
      })
      let startingSlide = $('.starting-slide').attr("data-slider-page")
      setTimeout(() => {
        rulesSlider.goToPage(startingSlide, true)
        setTimeout(() =>{
          document.querySelector('.rule-single--slides').style.opacity = 1
        }, 100)
      }, 50)
    }
  }
  $(document).ready(init)
})(jQuery)