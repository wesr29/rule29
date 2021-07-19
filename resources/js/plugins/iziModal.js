// simple example
// new iziModal('.simple')

// complex example
// const complex = new iziModal('.complex', {
//   onOpen  : modal => console.log(modal),
//   onClose : modal => console.log(modal),
//   clickBackdropToClose: true,
//   addCSS: true,
//   modalStyleOut: {
//     opacity: 0,
//     transform: 'translateY(-100px)',
//     transition: 'opacity 250ms ease-out, transform 250ms ease-out'
//   },
//   modalStyleIn: {
//     opacity: 1,
//     transform: 'translateY(0)',
//     transition: 'opacity 250ms ease-out, transform 250ms ease-out'   
//   },
//   backdropStyle: {
//     position: 'fixed',
//     top: 0,
//     left: 0,
//     width: '100vw',
//     height: '100vh',
//     background: 'rgba(0,0,0,0.75)',
//     opacity: 0,
//     transition: 'opacity 250ms ease-in',
//     zIndex: 9999999
//   }  
// })

// public methods
// complex.openModal(document.querySelector('#modal-1'))
// complex.hideModal()

// example markup
// <div class="simple">
//   <a href="#simple-modal">Click Me</a>
//   <div id="simple-modal">
//     <div data-modal-close>X</div>
//     <p>Thanks, that felt good.</p>
//   </div>
// </div>
const $ = jQuery

class iziModal {
  //add options and set event listener
  constructor(el, options){    
    this.el = typeof el == 'string' ? document.querySelector(el) : el
    
    // IE can't object.assign
    // this.options = Object.assign({
    this.options = $.extend({
      onOpen: () => { },
      onClose: () => { },
      clickBackdropToClose: true,
      addCSS: true,
      modalStyleOut: {
        opacity: 0,
        transform: 'matrix(1, 0, 0, 1, 0, -100)',
        transition: 'opacity 350ms ease-out, transform 350ms ease-out',
      },
      modalStyleIn: {
        opacity: 1,
        transform: 'matrix(1, 0, 0, 1, 0, 0)',
        transition: 'opacity 350ms ease-out, transform 350ms ease-out',   
      },
      backdropStyle: {
        position: 'fixed',
        top: 0,
        left: 0,
        width: '100vw',
        height: '100vh',
        background: 'rgba(0,0,0,0.75)',
        opacity: 0,
        transition: 'opacity 350ms ease-in',
        overflow: 'auto',
        zIndex: 9999999
      }
    }, options)
    
    //if we aren't adding CSS, unset all that now
    if(!this.options.addCSS){
      this.options.modalStyleOut = this.options.modalStyleIn = this.options.backdropStyle = {}
    }
    
    this.el.addEventListener('click', (e)=> { this._handleClick.call(this, e) })
  }
  
  //handle a click on this.el
  _handleClick(e){
    if(
      e.target.dataset.modalClose !== undefined
      || (
        this.options.clickBackdropToClose 
        && e.target.classList.contains('izi-modal-backdrop')
      )
    ){
       //if the target has dad-modal-close
      // or if the target is the backdrop
       this.hideModal()
    } else {
      //check if it could be a modal link
      let modalID = e.target.hash
      
      //if the target isn't clicked, go up the tree and see if it is in an a tag
      if(!modalID){
        let closestA = $(e.target).closest('a')
        if(closestA.length){
          modalID = closestA.get(0).hash
        }
      }

      if(modalID){
        //check if a modal exists for it
        const modal = this.el.querySelector(modalID)

        if(modal){
          //all set
          e.stopPropagation()
          e.preventDefault()
          this.openModal(modal, e.target)
        }
      }
    }
  }
  
  //utility function to add styles to an element
  // @param styles (object) - { cssProperty: value, ... }
  _addStylesToEl(styles, el){
    Object.keys(styles).forEach((key,index) => {
      el.style[key] = styles[key]
    })    
  }
  
  //disable scroll
  _disableScroll(){
    document.body.style.overflow = 'hidden'
  }
  
  //enable scroll
  _enableScroll(){
    document.body.style.overflow = ''
  }
  
  //hide a modal
  hideModal(){
    const backdrop = this.el.querySelector('.izi-modal-backdrop')
    const modal = backdrop.firstChild
    const speed = parseFloat(getComputedStyle(backdrop)['transitionDuration'])

    this._enableScroll()
    this.options.onClose(modal)
    this._addStylesToEl(this.options.modalStyleOut, modal)

    backdrop.style.opacity = 0
    setTimeout(() => {
      modal.style.display = 'none'
      backdrop.parentNode.appendChild(modal)
      backdrop.parentElement.removeChild(backdrop)    
    }, speed * 1000)    
  }
  
  //open a modal
  openModal(modal, trigger){
    const openBackdrop = this.el.querySelector('.izi-modal-backdrop')
    const backdrop = document.createElement('div')

    if(openBackdrop){
      this.hideModal()
    }

    this._addStylesToEl(this.options.backdropStyle, backdrop)
    this._disableScroll()    
    this.options.onOpen(modal, trigger)
    
    modal.parentNode.appendChild(backdrop)
    backdrop.appendChild(modal)
    backdrop.classList.add('izi-modal-backdrop')
    
    //force refresh the backdrop
    getComputedStyle(backdrop).position  
    //animate the fade
    backdrop.style.opacity = 1 
    
    //animate the modal
    modal.style.display = 'block'   

    this._addStylesToEl(this.options.modalStyleOut, modal)

    requestAnimationFrame(() => {   
      //force refresh the modal
      getComputedStyle(modal).position
      this._addStylesToEl(this.options.modalStyleIn, modal) 
    })
  }
}

export default iziModal