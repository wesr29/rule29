~($ => {
  function init(){
    $('.search-form--toggle').click(e => {
      $('.search-form--toggle').toggleClass('open')
      $('.search-form form').slideToggle(250)

      setTimeout(() => {
        if($('.search-form--toggle.open').length){
          $('.search-form input[type="search"]').focus()
          $('.search-form form').attr('aria-hidden', false)
        } else {
          $('.search-form form').attr('aria-hidden', true)
        }
      }, 300)
    })
  }

  $(document).ready(init)
})(jQuery)