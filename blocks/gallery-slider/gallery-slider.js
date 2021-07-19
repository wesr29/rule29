import iziSlider from '../../resources/js/plugins/iziSlider';
~(function($){

  function startPaginationSliders(){
    let paginationSliders = document.querySelectorAll('.pagination-slider--slides')

    paginationSliders.forEach(element => {
      //use this ID later to start the custom pagination
      element.id = 'pagination--slider--slides--' + ~~(Math.random() * 10000)
      var slide_count

      if ($(window).width() >= 1024) {
        slide_count = 4
      }
      else if($(window).width() > 500 && $(window).width() < 1024){
        slide_count = 3
      }
      else {
        slide_count = 2
      }

      new iziSlider(element, {
        addPagination: false,
        slideCount: slide_count,
        infinite: false,
        addControls: element.children.length > slide_count,
        adaptiveHeight: true
      })
    })
  }

  function startParentSliders(){
    let paginationSliders = document.querySelectorAll('.gallery-slider--slides')

    paginationSliders.forEach(element => {
      let customPaginationElement = $(element).closest('.gallery-slider').find('.pagination-slider--slides')[0]

      if(customPaginationElement){
        customPaginationElement.querySelectorAll('img').forEach(img => {
          img.removeAttribute('width')
          img.removeAttribute('height')
        })

        const slider = new iziSlider(element, {
          customPagination: `#${customPaginationElement.id}`,
          addControls : true, //window.innerWidth <= 768,
          adaptiveHeight: true,
          start(index){
            const slides = element.closest('.gallery-slider').querySelectorAll('.pagination-slider--slide')
            const cleanIndex = index < 0 ? slides.length - 1 : index > slides.length - 1 ? 0 : index
            slides[cleanIndex].click()
          }
        })

        customPaginationElement.children[0].classList.add('active')
      }
    })
  }
  

  function init(){
    if(document.querySelector('.gallery-slider')){
      startPaginationSliders()
      startParentSliders()
      setTimeout(() => {
        $(window).resize()
      }, 50)
    }
  }

  $(document).ready(init)
})(jQuery)