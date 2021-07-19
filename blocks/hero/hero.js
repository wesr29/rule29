import iziSlider from '../../resources/js/plugins/iziSlider'
import fillContainer from '../../resources/js/plugins/fillContainer'

~(($) => {
  $.fn.fillContainer = fillContainer

  function init(){
    const video = $('.hero--video--frame')
    const slider = $('.hero--slides')

    if(video.length){
      video.fillContainer()
    }

    if(slider.length){
      new iziSlider('.hero--slides', {
        addPagination: true,
        addControls: true,
        nextText: 'Next',
        prevText: 'Previous',
        autoplay: true,
        pauseDuration: 5000
      })
    }
  }

  $(document).ready(init)
})(jQuery)