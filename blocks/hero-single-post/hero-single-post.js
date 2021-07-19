 ~(function($){
  function fixReadTimeTop(){
    let els = document.querySelectorAll('.hero-single-post--read-time')
      if(els.length){
        if(document.querySelector('.admin-bar')){
          adminBarHeight = $('#wpadminbar').height(),
          els.forEach(el => el.style.top = document.querySelector('.main-header').offsetHeight + adminBarHeight + 'px')
        }
        else{
          els.forEach(el => el.style.top = document.querySelector('.main-header').offsetHeight + 'px')
        }
        
      }
  }

  function init() { 
    if(document.querySelector('.hero-single-post')){
      $('.hero-single-post--read-time').prepend('<div id="progresswrapper"><progress id="progressbar" value="0" max="100" ></progress></div>'); // Makes a CSS styleable div for the progressbar
      $(window).scroll(function () { 
        var   scvp = $(window).scrollTop(), // pixels on top hidden from view
          heroHeight = $('.hero-single-post').height(),
          contentHeight = $('.single-post--wrapper').height(),
          scrollOffsetHeight = heroHeight + contentHeight,
          scrollPercent = (scvp / (scrollOffsetHeight)) * 100; 
        var position = scrollPercent;
        $("#progressbar").attr('value', position);
      });

      fixReadTimeTop()
    }
  }
  $(document).ready(init)
  $(window).scroll(fixReadTimeTop)
})(jQuery)