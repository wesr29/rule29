function smoothScroller(){
  ~(($) => {
    $(document).on('click', 'a', (e) => {
      if(typeof e.target.hash == 'string'){
        let target = $(e.target.hash)
        if(target.length){
          e.preventDefault()
          jQuery('html, body').animate({ scrollTop: target.offset().top }, 650)
        }
      }
    })
  })(jQuery)
}

export default smoothScroller