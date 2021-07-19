/*
  Example usage
  ========================================================================

  const izi = new iziSlider('.slider', {
    //pagination
    addPagination: true,
    customPagination: '.custom-pagination',
    
    //controls
    addControls: true,
    nextText: 'Next',
    customNext: '.custom-next',
    prevText: 'Previous',
    customPrev: '.custom-prev',  
    
    //animation
    duration: 500,
    easing: 'ease-in-out',

    //callbacks
    start: page => console.log('starting to go to page ', page),
    done: page => console.log('finished going to page ', page),

    //general
    slideCount: 2,
    infinite: true,
    touchEnabled: true,
    adaptiveHeight: false,
    
    //autoplay
    autoplay: false,
    pauseDuration: 2000,
    stopAutoplayOnInteract: true
  })

  //public methods
  // izi.goToPage(1)
  // izi.next()
  // izi.previous()

  //these variables can be helpful
  // console.log(izi.totalPages)
  // console.log(izi.currentPage)
  // console.log(izi.slides)
*/


//polyfill for IE 11
Array.from||(Array.from=function(){var r=Object.prototype.toString,n=function(n){return"function"==typeof n||"[object Function]"===r.call(n)},t=Math.pow(2,53)-1,e=function(r){var n=function(r){var n=Number(r);return isNaN(n)?0:0!==n&&isFinite(n)?(n>0?1:-1)*Math.floor(Math.abs(n)):n}(r);return Math.min(Math.max(n,0),t)};return function(r){var t=Object(r);if(null==r)throw new TypeError("Array.from requires an array-like object - not null or undefined");var o,a=arguments.length>1?arguments[1]:void 0;if(void 0!==a){if(!n(a))throw new TypeError("Array.from: when provided, the second argument must be a function");arguments.length>2&&(o=arguments[2])}for(var i,u=e(t.length),f=n(this)?Object(new this(u)):new Array(u),c=0;c<u;)i=t[c],f[c]=a?void 0===o?a(i,c):a.call(o,i,c):i,c+=1;return f.length=u,f}}());

const $ = jQuery

class iziSlider {
  //set properties and options, then start everything else up
  constructor(el, options) {
    this.options = $.extend({
      //pagination options
      addPagination: true,
      customPagination: false,
      
      //control options
      addControls: true,      
      prevText: 'Previous',
      nextText: 'Next',
      customNext: false,
      customPrev: false,
      
      //animation options
      duration: 350,
      easing: 'ease-in-out',
      
      //general
      slideCount: 1,
      infinite: true,
      touchEnabled: true,
      adaptiveHeight: false,
      
      //callbacks
      start: () => { },
      done: () => { },
      
      //autoplay
      autoplay: false,
      pauseDuration: 5000,
      stopAutoplayOnInteract: true,
    }, options)

    //set private variables
    this._el = typeof el == 'string' ? document.querySelector(el) : el
    this._originalSlideCount = this._el.children.length
    this._autoplayInterval = undefined
    
    //variables the user can access
    this.slides = Array.from(this._el.children)
    this.currentPage = 0
    
    //set some stuff differently if it doesn't make sense
    if(this.options.slideCount > this.slides.length){
      this.options.slideCount = this.slides.length 
    }

    this.totalPages = this.slides.length

    if(this.options.infinite){
      this.totalPages += this.options.slideCount
    } else {
      this.totalPages -= this.options.slideCount - 1
    }

    //start everything else 
    this._init()
  }

  _init(){
    //set up events
    this._wrapSlider()
    this._addRequiredStyles()

    if(this.options.addControls){
      this._addControls()
    }

    if(this.options.customNext){
      let next = []

      if(typeof this.options.customNext == 'string'){
        //probably a selector
        next = document.querySelectorAll(this.options.customNext)
      } else if(this.options.customNext.tagName){
        //probably a single DOM element
        next = [ this.options.customNext ]
      } else {
        //probably an array of dom elements?
        next = this.options.customNext 
      }

      next.forEach(button => button.addEventListener('click', e => this.next()))
    }
    
    if(this.options.customPrev){
      let prev = []
      
      if(typeof this.options.customPrev == 'string'){
        //probably a selector
        prev = document.querySelectorAll(this.options.customPrev)
      } else if(this.options.customPrev.tagName){
        //probably a single DOM element
        prev = [ this.options.customPrev ]
      } else {
        //probably an array of dom elements?
        prev = this.options.customPrev 
      }

      prev.forEach(button => button.addEventListener('click', e => this.previous()))
    }    
  
    if(this.options.addPagination){
      this._addPagination()
    }

    if(this.options.customPagination){
      this._addCustomPagination()
    }

    if(this.options.infinite){
      this._cloneSlides()
    }

    if(this.options.touchEnabled){
      this._addTouchEvents()
    }

    if(this.options.autoplay){
      this.autoplayInterval = setInterval(() => {
        this.next()
      }, this.options.pauseDuration)
    }

    //go to the starting page
    setTimeout(() => this.goToPage(this.currentPage, true), 50)
    window.addEventListener('resize', e => this.goToPage(this.currentPage, true))
  }

  _wrapSlider(){
    const slider = document.createElement('div')
    const container = document.createElement('div')
    this._el.parentNode.insertBefore(slider, this._el)
    slider.appendChild(this._el)
    slider.classList.add('izi-slider--slider')
    
    slider.parentNode.insertBefore(container, slider)
    container.appendChild(slider)
    container.classList.add('izi-slider--container')
  }

  _addTouchEvents(){
    let touchdown = false 
    let originX
    
    let downEvents = ['mousedown', 'touchstart']
    let upEvents = ['mouseup', 'touchend']
    let moveEvents = ['mousemove', 'touchmove']
    
    downEvents.forEach(event => {
      this._el.addEventListener(event, (e) => {
        originX = e.touches && e.touches.length ? e.touches[0].clientX : e.clientX
        touchdown = true

        if(this.options.stopAutoplayOnInteract){
          clearInterval(this.autoplayInterval)
        }
      })
    })

    upEvents.forEach(event => {
      this._el.addEventListener(event, (e) => { 
        touchdown = false
      })    
    })
    
    moveEvents.forEach(event => {
      this._el.addEventListener(event, (e) => { 
        if(touchdown){
          let x = e.touches && e.touches.length ? e.touches[0].clientX : e.clientX
          let delta = x - originX

          if(Math.abs(delta) > 5){
            touchdown = false

            if(delta > 0){
              this.previous()
            } else {
              this.next()
            }
          }
        }
      })
    })
  }

  _addCustomPagination(){
    let containers = Array.from(document.querySelectorAll(this.options.customPagination))

    let allLinks = []

    containers.forEach(container => {
      let links = Array.from(container.querySelectorAll('[data-index]'))

      //find all links
      links.forEach(link => allLinks.push(link))

      //add events to all links
      allLinks.forEach(link => {
        link.addEventListener('click', e => {
          let index = parseInt(e.currentTarget.dataset.index)

          allLinks.forEach(single => {
            if(parseInt(single.dataset.index) == index){
              single.classList.add('active')
            } else {
              single.classList.remove('active')
            }
          })

          this.goToPage(index)
        })
      })
    })
  }

  _addRequiredStyles(){
    const slideWidth = 100 / this.options.slideCount
        
    this.slides.forEach((slide) => {
      slide.style.flex = slideWidth + '% 1 0'
    })
    
    $(this._el).css({
      display: 'flex',
      willChange: 'transform height',
      transition: 'transform ' + this.options.duration + 'ms ' + this.options.easing + ', height ' + this.options.duration + 'ms ' + this.options.easing
    })    
    
    //this is what makes the transforming hidden
    $(this._el).parent().css({
      overflow: 'hidden',
      height: '100%',
      touchAction: 'pan-y'
    })

    if(this.options.adaptiveHeight){
      $(this._el).css({ alignItems: 'flex-start' }) 
    }
  }
  
  _addControls(){
    const controls = document.createElement("span")
    const prev = document.createElement("a")
    const next = document.createElement("a")
    const prevText = document.createTextNode(this.options.prevText)
    const nextText = document.createTextNode(this.options.nextText)
    
    $(prev).append(prevText).attr('href', 'javascript:;')
    $(next).append(nextText).attr('href', 'javascript:;')
    
    $(controls).append(prev)
    $(controls).append(next)
    $(this._el.parentNode).after(controls)
    
    prev.classList.add('izi-slider--controls--prev')
    next.classList.add('izi-slider--controls--next')
    controls.classList.add('izi-slider--controls')
    
    prev.addEventListener('click', () => { 
      this.previous() 
      if(this.options.stopAutoplayOnInteract){
        clearInterval(this.autoplayInterval)
      }
    })
    next.addEventListener('click', () => { 
      this.next() 
      if(this.options.stopAutoplayOnInteract){
        clearInterval(this.autoplayInterval)
      }
    })
  }

  _addPagination(){
    const pagination = document.createElement("div")

    this.slides.forEach((slide, index) => {
      if(index >= this.totalPages){
        return false
      }
      
      const link = document.createElement('a')
      link.addEventListener('click', () => { 
        this.goToPage(index) 
        
        if(this.options.stopAutoplayOnInteract){
          clearInterval(this.autoplayInterval)
        }
      })
      link.classList.add('izi-slider--pagination--link')
      $(link).attr('href', 'javascript:;')
      $(link).append(index + 1)
      
      $(pagination).append(link)
    })
    
    $(this._el.parentNode).after(pagination)
    pagination.classList.add('izi-slider--pagination')
  }

  _cloneSlides(){
    const startClones = this.slides.slice(0, this.options.slideCount)
    const endClones = this.slides.slice(Math.max(this._originalSlideCount - this.options.slideCount, 1))

    startClones.forEach(el => {
      let clone = $(el).clone()
      $(clone).addClass('izi-slider--clone')
      $(this._el).append(clone)
      this.slides.push(clone.get(0))
    })
    
    endClones.reverse().forEach(el => {
      let clone = $(el).clone()
      $(clone).addClass('izi-slider--clone')
      $(this._el).prepend(clone)      
      this.slides.unshift(clone.get(0))
    })
  }

  _updatePagination(){
    if(this.options.addPagination){            
      let paginationLinks = $(this._el).parent().parent().find('.izi-slider--pagination--link')
      paginationLinks.removeClass('active')
      $(paginationLinks.get(this.currentPage)).addClass('active')
    }
  }

  _addAriaTags(){
    let lowRange = this.currentPage
    let highRange = this.currentPage + this.options.slideCount - 1
        
    this.slides.forEach((slide, index) => {
      if(this.options.infinite){
        index -= this.options.slideCount
      }
      
      if(index >= lowRange && index <= highRange){
        $(slide).attr('aria-hidden', false)
        $(slide).find('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex], *[contenteditable]').attr('tabindex', '')
      } else {
        $(slide).attr('aria-hidden', true)
        $(slide).find('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex], *[contenteditable]').attr('tabindex', '-1')
      }
    })
  }

  //public methods
  next(){
    let target = this.currentPage + 1

    if(!this.options.infinte && target > this.totalPages - 1){
      return false
    }

    this.goToPage(target)

    if(this.options.infinite && target == this.totalPages - this.options.slideCount){
      setTimeout(() => {
        this.goToPage(0, true)
      }, this.options.duration + 1)
    }
  }

  previous(){
    let target = this.currentPage - 1

    if(!this.options.infinite && target < 0){
      return
    }

    this.goToPage(target)

    if(this.options.infinite && target < 0){
      setTimeout(() => {
        this.goToPage(this.totalPages - 1 - this.options.slideCount, true)
      }, this.options.duration + 1)
    }    
  }

  goToPage(index, dontAnimate){      
    if(!dontAnimate && parseInt(index) == this.currentPage){
      return false
    }    

    let slideIndex = parseInt(index)

    this.currentPage = slideIndex
        
    if(this.options.infinite){
      slideIndex += this.options.slideCount

      if(this.options.slideCount == this._originalSlideCount){
        slideIndex -= 1
      }
    }

    const targetSlide = this.slides[slideIndex]
    const childPos = $(targetSlide).offset()
    const parentPos = $(targetSlide).parent().offset()
    const targetIndexLeft = (childPos.left - parentPos.left) * -1  

    if(dontAnimate){
      this._el.style.transition = 'transform 0ms ' + this.options.easing
      setTimeout(() => {
        this._el.style.transform = 'translate3d(' + targetIndexLeft + 'px, 0px, 0px)'
        setTimeout(() => {
          this._el.style.transition = 'transform ' + this.options.duration + 'ms ' + this.options.easing + ', height ' + this.options.duration + 'ms ' + this.options.easing
        }, 100)
      }, 10)



    } else {
      this._el.style.transform = 'translate3d(' + targetIndexLeft + 'px, 0px, 0px)' 

      //run callbacks
      this.options.start(this.currentPage)
      setTimeout(() => this.options.done(this.currentPage), this.options.duration) 
    }

    this._updatePagination()
    this._addAriaTags()

    if(this.options.adaptiveHeight){
      let maxHeight = 0
      let visibleSlides = this._el.querySelectorAll('[aria-hidden="false"]')

      visibleSlides.forEach(slide => {
        if(slide.offsetHeight > maxHeight){
          maxHeight = slide.offsetHeight
        }
      })

      this._el.style.height = maxHeight + 'px'
    }  
  }
}

export default iziSlider