// import MenuHelper from '../../resources/js/plugins/menuhelper'
import ADAWPMenu from '../../resources/js/plugins/ADAWPMenu'

~(($) => {
  function resizePlaceholder(){
    $('.main-header--spacer').height($('.main-header').outerHeight())
  }

  function init(){
    new ADAWPMenu('#menu-main-menu', { 
      menuDisplayWhenOpen: 'flex',
      subMenuSlideDuration: 450, 
      subMenuDisplayWhenOpen: 'flex',
      mouseActionToOpenTopLevelSubmenus: window.innerWidth >= 768 ? 'hover' : 'click', 
      menuToggler: '.mobile-menu-button',
      onMenuToggle(){
        document.querySelector('.main-header').classList.toggle('mobile-menu-open')
      }
    })

    // new MenuHelper('.main-menu .menu', {
    //   toggler: '.main-header .mobile-menu-button',
    //   togglerTarget: '.main-menu',
    //   submenuTogglerContent: '',
    //   onMenuToggle(state){
    //     if(state == 'open'){
    //       document.querySelector('.main-header').classList.add('mobile-menu-open')
    //     } else {
    //       document.querySelector('.main-header').classList.remove('mobile-menu-open')
    //     }

    //     document.querySelector('.main-menu').style.maxHeight = (window.innerHeight - document.querySelector('.main-header').offsetHeight) + 'px'
    //   }
    // }) 

    setTimeout(resizePlaceholder, 250)
    $(window).resize(resizePlaceholder)
    $(window).scroll(e => {
      if(window.scrollY > 10 && window.innerWidth > 768){
        $('.main-header').css('background', 'white')
      } else {
        $('.main-header').css('background', '')
      }
    })
  }

  $(document).ready(init)
})(jQuery)