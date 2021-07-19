import 'jquery.tabbable'

function menuhelper(options){
  ~(function($){
    let toggler = $(options.toggler)
    let menu = $(options.menu)
    let speed = 250
    let isMobile = $(window).width() <= 768

    function slideSubMenu(submenu, forceSlideUp){
      submenu.stop()
      if(forceSlideUp){
        submenu.slideUp({
          duration: speed,
          start: function(){
            $(this).closest('.menu-item-has-children').find('a').attr('aria-expanded', false)
            $(this).closest('.menu-item-has-children').find('.sub-menu-toggler').removeClass('open')
          }
        })
      } else {
        submenu.slideToggle({
          duration: speed,
          start: function(){
            let bounding = this.getBoundingClientRect()
            if(bounding.right > (window.innerWidth || document.documentElement.clientWidth)){
              this.classList.add('push-left')
            } else {
              setTimeout( () => {
                this.classList.remove('push-left')
              }, speed+1)
            }
            $(this).closest('.menu-item-has-children').find('.sub-menu-toggler').toggleClass('open')
            let isOpen = $(this).closest('.menu-item-has-children').find('.sub-menu-toggler').hasClass('open')
            $(this).closest('.menu-item-has-children').find('a').attr('aria-expanded', isOpen)
          }
        })
      }
    }
    //handle the toggler click
    toggler.click(function(){

      $(menu).height($(window).outerHeight() - $('.main-header').outerHeight())

      $(menu).slideToggle({
        start: () =>{
          $(toggler).toggleClass('open')
          $(menu).toggleClass('open')
        }
      }) 
    })
    //fix display on resize
    var windowWidth = $(window).width()

    $(window).resize(function(){
      if ($(window).width() == windowWidth){
        return 
      } 

      if($(window).width() <= 768){
        $(menu).hide()
      } else {
        toggler.removeClass('open')
        $(menu).css({
          display: '',
          height: ''
        })        
      }
    })
    
    //handle hovering sub menu
    menu.find('.menu-item-has-children').hover(function(){
      if(!isMobile){
        slideSubMenu($(this).children('.sub-menu'))
      }
    })

    //focusing a menu item with children should close any non-related sub menus
    menu.find('.menu-item a').focus(function(e){
       if(!isMobile){
        let currentMenuItem = $(this).closest('.menu-item')
        let isSubMenuItem = $(currentMenuItem).parent('.sub-menu').length
        if (!isSubMenuItem ) {
          $('.menu-item').not(currentMenuItem).find('.sub-menu').each(function(){
            slideSubMenu($(this), true)
          })
        } 
      }
    })

    menu.find('.menu-item-has-children').each( function(index, element) {
      let ariaIdentifier = 'menu-item-'+index+'-controls'
      $(element).find('a').attr('aria-controls', ariaIdentifier).attr('aria-expanded', false)
      $(element).find('.sub-menu').attr('id', ariaIdentifier)
    })

    //hitting enter on an a with submenu should open it
    menu.find('.menu-item-has-children a').keydown(function(e) {
      //32 is spacebar
      if(e.key == 'Enter' || e.keyCode == 32){ 
        if(e.keyCode === 32){
          e.preventDefault()
        }
        slideSubMenu($(this).parent().children('.sub-menu'))
      }
    })
    //using arrow keys should be work  
    menu.keydown(function(e){
      let activeElement = document.activeElement
      switch (e.key) {
        case 'ArrowUp':
        case 'ArrowLeft':
          e.preventDefault()
          jQuery.tabPrev()
          break
        case 'ArrowDown':
        case 'ArrowRight':
          e.preventDefault()
          jQuery.tabNext()
          break
      }
    })

    //add toggler
    const submenuToggler = '<span class="sub-menu-toggler">'+options.submenuToggler+'</span>'
    menu.find('.menu-item-has-children > a').append(submenuToggler)
    // missing forEach on NodeList for IE11
    if (window.NodeList && !NodeList.prototype.forEach) {
      NodeList.prototype.forEach = Array.prototype.forEach;
    }
    document.querySelectorAll('.sub-menu-toggler').forEach(el => {
      el.addEventListener('click', e => {
        // console.log($(el).parent())/
        slideSubMenu($(el).parent().parent().children('.sub-menu'))
        e.preventDefault()
      })
    })

  })(jQuery)
}
export default menuhelper