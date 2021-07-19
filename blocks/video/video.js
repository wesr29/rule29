~(function($){
  function init() {
    $(".video--image-overlay").click(e => {
      let frame = $(e.target).parent().find("iframe")
      frame.attr("src", frame.attr("src") + "?autoplay=1&autopause=0")
      $(e.target).parent().addClass('open')
    })
    $(".video--play-button").click(e => {
      let frame = $(e.target).closest('.wrapper').find("iframe")
      frame.attr("src", frame.attr("src") + "?autoplay=1&autopause=0")
      $(e.target).closest('.wrapper').addClass('open')
    })
  }
  $(document).ready(init)
})(jQuery)
