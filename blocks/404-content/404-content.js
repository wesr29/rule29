import fillContainer from '../../resources/js/plugins/fillContainer'

~(($) => {
  $.fn.fillContainer = fillContainer

  function init(){
    const video = $('.error404--video')
    if(video.length){
      video.fillContainer()
    }
  }

  $(document).ready(init)
})(jQuery)