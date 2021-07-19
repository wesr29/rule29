import ScrollOut from 'scroll-out'

function updateElVariable(){
  if(window.innerWidth > 768 && document.querySelector('.animate-on-scroll')){
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      const position = getComputedStyle(el).getPropertyValue('--visible-y')
      const intersect = getComputedStyle(el).getPropertyValue('--intersect-y')
      const percentShowing = (position - 1) * intersect * -1
      el.style.setProperty('--translate-y', (percentShowing * 100) + 'px')
    })
  }
}

function init(){
  window.addEventListener('scroll', updateElVariable)

  setTimeout(() => {
    ScrollOut({
      targets: 'h1',
      once: true,
      onShown(el){
        if(el.classList.contains('dont-animate')){
          return
        }
        // el.innerHTML = el.textContent.split(' ').map((word, index) => `<span class='cover'><span style='transition-delay:${index * 100}ms'>${word}</span></span>`).join(' ')
        el.innerHTML = el.textContent.split(' ').map((word, index) => `<span class='cover'><span>${word}</span></span>`).join(' ')
        setTimeout(() => el.classList.add('visible'))
        
      }
    })

    ScrollOut({
      targets: '.animate-on-scroll',
      cssProps: {
        visibleY: true,
        intersectY: true,
        viewportY: true
      }
    })

    ScrollOut({
      targets: '.parallax',
      cssProps: {
        viewportY: true
      }
    })

    ScrollOut({
      targets: '.will-animate',
      onShown(el){
        el.classList.add(el.dataset.animation)
      }
    })
  }, 500)
}

function animations(){
  window.addEventListener('DOMContentLoaded', init)
}

export default animations