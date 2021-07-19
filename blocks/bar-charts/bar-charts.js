import ScrollOut from '../../resources/node_modules/scroll-out'

~($ => {
  function init(){
    if(document.querySelector('.bar-charts')){
      ScrollOut({
        targets: '.bar-charts--dataset--bar',
        onShown(el){
          el.style.width = el.dataset.width + '%'
        }
      })
    }
  }

  $(document).ready(init)
})(jQuery)