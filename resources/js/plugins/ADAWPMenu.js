/**
 * Example usage: 
 *
 * new ADAWPMenu('.main-menu .menu', {
 *  menuTogglerContainer: '',                   //defaults to the `el` this is being mounted on, but if you have a set up like this: <div class="thing"><div><!-- stuff --></div><ul><!-- wp menu --></ul></div> and you want to trigger "thing" add `.thing` here  
 *  menuDisplayWhenOpen: 'block',               //the display property of the menu when open
 *  slideDuration: 450,                         //the speed for open/close menus
 *  subMenuDisplayWhenOpen: 'block',            //the display property of sub menus
 *  mouseActionToOpenTopLevelSubmenus: 'hover', //the mouse action to display sub menus - hover|click
 *  menuToggler: '.main-menu-toggler',          //a class name/ID to show/hide the entire menu
 *  menuFillsScreenHeightOnToggle: true,        //should the menu fill the screen when the menuToggler is clicked?
 *  submenuTogglerContent: '+',                 //content of the submenu toggler element
 *  mobileSwitch: 768,                          //when does the menu switch to mobile mode? 
 * })
 */

class ADAWPMenu {
  constructor(el, options){
    this.el = document.querySelector(el)
    this.options = jQuery.extend({
      menuTogglerContainer: '', //defaults to the `el` this is being mounted on, but if you have a set up like this: <div class="thing"><div><!-- stuff --></div><ul><!-- wp menu --></ul></div> and you want to trigger "thing" add that here
      menuDisplayWhenOpen: 'block', //the display property of the menu when open
      slideDuration: 450, //the speed for open/close menus
      subMenuDisplayWhenOpen: 'block', //the display property of sub menus
      mouseActionToOpenTopLevelSubmenus: 'hover', //the mouse action to display sub menus
      menuToggler: '', //a class name/ID to show/hide the entire menu
      menuFillsScreenHeightOnToggle: true, //should the menu fill the screen when the menuToggler is clicked?
      submenuTogglerContent: '', //content of the submenu toggler element
      mobileSwitch: 768, //when does the menu switch to mobile mode? 
    }, options)
    this.timeoutAnimationInstances = []

    this._addSubmenuTogglers()
    this._enableSubMenuActions()

    this._enableArrowNavigation()
    this._enableMouseAction()
    this._enableMenuToggler()

    //watch for class changes to submenus (that way this runs whether the submenu is opened via keyboard or mouse)
    const observer = new MutationObserver((mutation) => this._handleMutation(mutation))
    observer.observe(this.el, { childList: true, attributes: true, subtree: true })
  }

  //enables submenu actions (click on mobile|hover on desktop)
  _enableSubMenuActions(){
    const childrenWithSubmenus = this.el.querySelectorAll('.sub-menu .menu-item-has-children')

    childrenWithSubmenus.forEach(el => {
      el.addEventListener('mouseenter', e => {
        el.parentNode.querySelector('.sub-menu').classList.toggle('open')
      })

      el.addEventListener('mouseleave', e => {
        el.parentNode.querySelector('.sub-menu').classList.toggle('open')
      })
    })
  }

  //adds sub menu togglers
  _addSubmenuTogglers(){
    const itemsWithChildren = this.el.querySelectorAll('.menu-item-has-children')
    if(itemsWithChildren.length){
      itemsWithChildren.forEach(el => {
        const toggler = document.createElement('span')
        toggler.classList.add('sub-menu-toggler')
        toggler.innerHTML = this.options.submenuTogglerContent
        el.append(toggler)
      })
    }
  }

  //enables the open/close menu toggler, generally on mobile only
  _enableMenuToggler(){
    if(this.options.menuToggler){
      const toggler = document.querySelector(this.options.menuToggler)

      toggler.addEventListener('click', e => {
        const el = this.options.menuTogglerContainer ? document.querySelector(this.options.menuTogglerContainer) : this.el

        setTimeout(() => {
          el.classList.toggle('open')
          toggler.classList.toggle('open')
        }, 0)
        if(this._elIsVisible(el)){
          this._enableScroll()
          this._slideUp(el)
        } else {
          this._disableScroll()
          this._slideDown(el)
        }
      })
    }
  }

  //enables scrolling again
  _enableScroll(){
    const scrollY = document.body.style.top
    document.body.style.top = ''
    document.body.style.position = ''
    document.body.style.width = ''    
    window.scrollTo(0, parseInt(scrollY || '0') * -1)
  }

  //disables scroll (when menu is open)
  _disableScroll(){
    document.body.style.top = `-${window.scrollY}px`
    document.body.style.position = 'fixed'
    document.body.style.width = '100%'
  }

  //enables either click or hover mouse actions on sub menus
  _enableMouseAction(){
    if(this.options.mouseActionToOpenTopLevelSubmenus != 'click' && window.innerWidth <= this.options.mobileSwitch){
      //open/close things based on the togglers instead
      const togglers = this.el.querySelectorAll('.sub-menu-toggler')
      if(togglers){
        togglers.forEach(toggle => {
          toggle.addEventListener('click', e => {
            const siblingMenu = toggle.parentNode.querySelector('.sub-menu')
            const shouldOpen = !siblingMenu.classList.contains('open')

            this._closeAllTopLevelSubMenus()

            if(shouldOpen){
              setTimeout(() => siblingMenu.classList.add('open'), 0)
            }
          })
        })
      }
      return false
    }

    const action = this.options.mouseActionToOpenTopLevelSubmenus == 'hover' ? 'mouseenter' : 'click'
    const childrenWithSubmenus = this.el.querySelectorAll(':scope > .menu-item-has-children')

    childrenWithSubmenus.forEach(el => {
      el.addEventListener(action, e => {

        if(action == 'click' && e.target.parentNode == el){
          e.preventDefault()
        }

        const menuShouldOpen = el.querySelector('.sub-menu.open') ? false : true


        this._closeAllTopLevelSubMenus()

        if(menuShouldOpen){
          setTimeout(() => el.querySelector('.sub-menu').classList.toggle('open'), 0)
        }
      })
    })

    //if we are using hover, set the mouse leave to close sub menus
    if(action == 'mouseenter' && window.innerWidth > this.options.mobileSwitch){
      childrenWithSubmenus.forEach(el => {
        el.addEventListener('mouseleave', e => {
          this._closeAllTopLevelSubMenus()
        })
      })
    }
  }

  //starts arrow navigation events
  _enableArrowNavigation(){
    this.el.addEventListener('keydown', e => {
      switch(e.key){
        case 'ArrowUp':
          e.preventDefault()
          this._focusTabbable('prev')
          break
        case 'ArrowRight':
          e.preventDefault()
          this._focusTabbable('next')
          break
        case 'ArrowDown':
          e.preventDefault()
          this._handleArrowDown()
          break
        case 'ArrowLeft':
          e.preventDefault()
          this._focusTabbable('prev')
          break
      }
    })

    this.el.querySelectorAll(':scope > li > a').forEach(link => {
      link.addEventListener('focus', e => {
        this._closeAllTopLevelSubMenus()
      })
    }) 
  }

  _closeAllTopLevelSubMenus(){
    this.el.querySelectorAll('.open').forEach(open => open.classList.remove('open'))
  }

  //focus a the next or previous tabbable element
  _focusTabbable(type){
    const tabbables = Array.from(this.el.querySelectorAll('a, button, input, [tabindex]:not([tabindex="-1"]')).filter(el => this._elIsVisible(el) && !el.hasAttribute('disabled'))
    for (var i = tabbables.length - 1; i >= 0; i--) {
      if(tabbables[i] == document.activeElement){
        if(type == 'next'){
          if(typeof tabbables[i + 1] != 'undefined'){
            tabbables[i + 1].focus()
          } else {
            tabbables[0].focus()
          }
        } else {
          if(typeof tabbables[i - 1] != 'undefined'){
            tabbables[i - 1].focus()
          } else {
            tabbables[tabbables.length - 1].focus()
          }
        }
        break
      }
    }
  }

  //handle an arrow down event
  _handleArrowDown(){
    let activeParent = document.activeElement.parentNode
    if(activeParent.classList.contains('menu-item-has-children')){
      activeParent.querySelector('.sub-menu').classList.add('open')
    }
    setTimeout(() => this._focusTabbable('next'), 0)
  }

  //checks if an element is visible or not
  _elIsVisible(el){
    return el.offsetWidth > 0 || el.offsetHeight > 0 || el.getClientRects().length > 0
  }

  //handle mutations on children of the main element
  _handleMutation(mutation){
    const data = mutation[0]
    //whenever the sub menu class has changed do this
    if(data.attributeName == 'class' && data.target.classList.contains('sub-menu')){
      if(data.target.classList.contains('open')){
        this._slideDown(data.target)
      } else {
        this._slideUp(data.target)
      }
    }
  }

  //slides a target up to hide it
  _slideUp(target){
    if(target.dataset.timeoutInstance){
      //clear the timeout and remove it from instances
      clearTimeout(this.timeoutAnimationInstances.filter(x => x == target.dataset.timeoutInstance)[0])
      this.timeoutAnimationInstances = this.timeoutAnimationInstances.filter(x => x != target.dataset.timeoutInstance)
    }
    target.style.transitionProperty = 'height, margin, padding'
    target.style.transitionDuration = this.options.slideDuration + 'ms'
    target.style.boxSizing = 'border-box'
    target.style.height = target.offsetHeight + 'px'
    target.offsetHeight
    target.style.overflow = 'hidden'
    target.style.height = 0
    target.style.paddingTop = 0
    target.style.paddingBottom = 0
    target.style.marginTop = 0
    target.style.marginBottom = 0
    
    const timeout = window.setTimeout( () => {
      target.style.display = 'none'
      target.style.removeProperty('height')
      target.style.removeProperty('padding-top')
      target.style.removeProperty('padding-bottom')
      target.style.removeProperty('margin-top')
      target.style.removeProperty('margin-bottom')
      target.style.removeProperty('overflow')
      target.style.removeProperty('transition-duration')
      target.style.removeProperty('transition-property')

      this.timeoutAnimationInstances = this.timeoutAnimationInstances.filter(x => x != target.dataset.timeoutInstance)      
      target.dataset.timeoutInstance = 0
    }, this.options.slideDuration)

    this.timeoutAnimationInstances.push(timeout)
    target.dataset.timeoutInstance = timeout
  }

  //slides a target down to display it
  _slideDown(target){
    if(target.dataset.timeoutInstance){
      //clear the timeout and remove it from instances
      clearTimeout(this.timeoutAnimationInstances.filter(x => x == target.dataset.timeoutInstance)[0])
      this.timeoutAnimationInstances = this.timeoutAnimationInstances.filter(x => x != target.dataset.timeoutInstance)
    }

    target.style.removeProperty('display')
    let display = window.getComputedStyle(target).display

    if (display === 'none'){
      display = target == this.el ? this.options.menuDisplayWhenOpen : this.options.subMenuDisplayWhenOpen
    }

    target.style.display = display
    let height = target.offsetHeight

    const parentEl = this.options.menuTogglerContainer ? document.querySelector(this.options.menuTogglerContainer) : this.el

    if(target == parentEl && this.options.menuFillsScreenHeightOnToggle){
      const header = this.el.closest('header')
      height = window.innerHeight - header.offsetHeight
    }

    target.style.overflow = 'hidden'
    target.style.height = 0
    target.style.paddingTop = 0
    target.style.paddingBottom = 0
    target.style.marginTop = 0
    target.style.marginBottom = 0
    target.offsetHeight
    target.style.boxSizing = 'border-box'
    target.style.transitionProperty = "height, margin, padding"
    target.style.transitionDuration = this.options.slideDuration + 'ms'
    target.style.height = height + 'px'
    target.style.removeProperty('padding-top')
    target.style.removeProperty('padding-bottom')
    target.style.removeProperty('margin-top')
    target.style.removeProperty('margin-bottom')

    const timeout = window.setTimeout( () => {
      if(target != parentEl || !this.options.menuFillsScreenHeightOnToggle){
        target.style.removeProperty('height')
      }
      target.style.removeProperty('overflow')
      target.style.removeProperty('transition-duration')
      target.style.removeProperty('transition-property')

      this.timeoutAnimationInstances = this.timeoutAnimationInstances.filter(x => x != target.dataset.timeoutInstance)      
      target.dataset.timeoutInstance = 0      
    }, this.options.slideDuration)

    this.timeoutAnimationInstances.push(timeout)
    target.dataset.timeoutInstance = timeout    
  }
}

export default ADAWPMenu