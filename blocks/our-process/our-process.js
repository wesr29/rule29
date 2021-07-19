import iziModal from '../../resources/js/plugins/iziModal'
import iziSlider from '../../resources/js/plugins/iziSlider'

~($ => {
  let currentIndex = 1

  function updateClipPath(el, index, property, operation){
    //grab the raw numbers from the property
    let numbers = property.replace(/\D/g,' ').split(' ').filter(x => x.length)
  
    //add or subtract from the number at INDEX
    numbers = numbers.map((num, i) => {
      num = parseInt(num)

      if(i == index){
        if(operation == '+'){
          num += 5
        } else {
          num -= 5
        }
      }

      return num
    })

    //update the el with new numbers
    el.style.clip = `rect(${numbers[0]}px, ${numbers[1]}px, ${numbers[2]}px, ${numbers[3]}px)`
  }

  function nextSlide(){
    if(currentIndex == 1){
      currentIndex = $('.pie--slice').length
    } else {
      currentIndex -= 1
    }

    $(`.pie--slice-${currentIndex} .pie--slice--inner`).trigger('click')
  }

  function prevSlide(){
    if(currentIndex == $('.pie--slice').length){
      currentIndex = 1
    } else {
      currentIndex += 1
    }

    $(`.pie--slice-${currentIndex} .pie--slice--inner`).trigger('click')
  }

  function startEvents(){
    $('.pie--slice--inner').click(function(){
      const index = $(this).parent().attr('data-counter')
      //remove inline styles and active from everything
      $('.pie--slice.active .pie--slice--inner').css('clip', '')
      $('.pie--slice.active').removeClass('active').css('clip', '')
      $('.pie--inner.active').removeClass('active').addClass('leaving')
      $('.pie--number.active').removeClass('active')

      //add active to this one
      $(this).parent().addClass('active')
      $(`.pie--inner[data-counter="${index}"]`).addClass('active')
      $(`.pie--number[data-counter="${index}"]`).addClass('active')


      //remove the leaving class
      setTimeout(() => {
        $('.pie--inner.leaving').removeClass('leaving')
      }, 500)

      //add to the 4th parameter of the parent and remove from the 2nd parameter of this
      let parentStyles = getComputedStyle($(this).parent().get(0))
      let thisStyles = getComputedStyle($(this).get(0))
      updateClipPath($(this).parent().get(0), 3, parentStyles.clip, '+')
      updateClipPath($(this).get(0), 1, thisStyles.clip, '-')

      currentIndex = parseInt(index)
    })

    $('.pie--pagination--prev').click(nextSlide)

    $('.pie--pagination--next').click(prevSlide)

    $(document).keydown(function(e){
      if (e.which == 37) { 
        nextSlide()
      }
      if (e.which == 39) { 
        prevSlide()
      }
    })
  }

  function startModalSlider(){
    let firstOpen = true

    const slider = new iziSlider('.our-process--modal--slider', { 
      duration: 0,
      addPagination: false,
      addControls: false,
      customPrev: '.our-process--modal--slider-control--prev',
      customNext: '.our-process--modal--slider-control--next',
    })

    new iziModal('.our-process--mobile', {
      onOpen(){
        if(firstOpen){
          setTimeout(() => slider.goToPage(0, true), 1)
          firstOpen = false
        }
      }
    })
  }

  function init(){
    if(document.querySelector('.our-process')){
      startEvents()
      startModalSlider()
    }
  }

  $(document).ready(init)
})(jQuery)