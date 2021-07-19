~($ => {
  function init(){
    if(window.innerWidth <= 768){
      return false
    }
    $('.case-study--image').each(function(){
      setTimeout(() => {
        $(this).css('height', '100%')
      }, 250)
    })
  }

  $(document).ready(init)
})(jQuery)