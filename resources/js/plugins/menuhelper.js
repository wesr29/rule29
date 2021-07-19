const $ = jQuery

/** 
 * @param el - element selector to attach the helper to
 * @param options[toggler] - element's class to open/close the entire menu
 */
class MenuHelper { 
  constructor(el, options){
    const domNode = document.querySelector(el)
    const menubar = new Menubar(domNode)
    menubar.init()
    this.addAriaTagsAndRoles(domNode, true)

    //open/close the menu when the toggler is clicked
    let state = 'closed'
    $(options.toggler).click(function(){
      state = state == 'open' ? 'closed' : 'open'
      $(this).toggleClass('open')
      $(options.togglerTarget).slideToggle().toggleClass('open')
      if(typeof(options.onMenuToggle) == 'function'){
        options.onMenuToggle(state)
      }      

      if(state == 'open'){
        //disable scroll
        document.body.style.top = `-${window.scrollY}px`
        document.body.style.position = 'fixed'
        document.body.style.width = '100%'
      } else {     
        //enable scroll
        const scrollY = document.body.style.top
        document.body.style.top = ''
        document.body.style.position = ''
        document.body.style.width = ''    
        window.scrollTo(0, parseInt(scrollY || '0') * -1)
      }      
    })


    //add submenu togglers
    let itemsNeedingTogglers = $(domNode).find('.menu-item-has-children')
    if(itemsNeedingTogglers){
      itemsNeedingTogglers.each(function(){
        let toggler = '<span class="submenu-toggler">'+options.submenuTogglerContent+'</span>'
        $(this).find('> .sub-menu').before(toggler)
      })
    }
  }

  addAriaTagsAndRoles(el, addLabelOnEl){
    if(addLabelOnEl){
      //don't do this after calling it recursively... the label will already be set
      el.setAttribute('aria-label', el.parentNode.getAttribute('aria-label'))
      el.setAttribute('role', 'menubar')
    }

    el.querySelectorAll(':scope > li').forEach(li => {
      li.setAttribute('role', 'none')

      let link = li.querySelector(':scope > a')
      let ul = li.querySelector(':scope > ul')

      link.setAttribute('role', 'menuitem')

      if(li.classList.contains('menu-item-has-children')){
        link.setAttribute('aria-haspopup', 'true')
        link.setAttribute('aria-expanded', 'false')
        ul.setAttribute('role', 'menu')
        ul.setAttribute('aria-label', link.innerText)
        //gettin recursive
        this.addAriaTagsAndRoles(ul, false)
      }
    })
  }
}


class Menubar {
  constructor(domNode){
    this.isMenubar = true
    this.domNode = domNode

    this.menubarItems = [] // See Menubar init method
    this.firstChars = [] // See Menubar init method

    this.firstItem = null // See Menubar init method
    this.lastItem = null // See Menubar init method

    this.hasFocus = false // See MenubarItem handleFocus, handleBlur
    this.hasHover = false // See Menubar handleMouseover, handleMouseout    
  }


  /**
  * Adds ARIA role to the menubar node
  * Traverse menubar children for A elements to configure each A element as a ARIA menuitem
  * and populate menuitems array. Initialize firstItem and lastItem properties.
  */
  init(){
    var menubarItem, childElement, menuElement, textContent, numItems;


    // Traverse the element children of menubarNode: configure each with
    // menuitem role behavior and store reference in menuitems array.
    let elem = this.domNode.firstElementChild;

    while (elem) {
      var menuElement = elem.firstElementChild;

      if (elem && menuElement && menuElement.tagName === 'A') {
        menubarItem = new MenubarItem(menuElement, this);
        menubarItem.init();
        this.menubarItems.push(menubarItem);
        textContent = menuElement.textContent.trim();
        this.firstChars.push(textContent.substring(0, 1).toLowerCase());
      }

      elem = elem.nextElementSibling;
    }

    // Use populated menuitems array to initialize firstItem and lastItem.
    numItems = this.menubarItems.length;
    if (numItems > 0) {
      this.firstItem = this.menubarItems[ 0 ];
      this.lastItem = this.menubarItems[ numItems - 1 ];
    }
    this.firstItem.domNode.tabIndex = 0;
  }

  /* FOCUS MANAGEMENT METHODS */

  setFocusToItem(newItem) {

    var flag = false;

    for (var i = 0; i < this.menubarItems.length; i++) {
      var mbi = this.menubarItems[i];

      if (mbi.domNode.tabIndex == 0) {
        flag = mbi.domNode.getAttribute('aria-expanded') === 'true';
      }

      mbi.domNode.tabIndex = -1;
      if (mbi.popupMenu) {
        mbi.popupMenu.close();
      }
    }

    newItem.domNode.focus();
    newItem.domNode.tabIndex = 0;

    if (flag && newItem.popupMenu) {
      newItem.popupMenu.open();
    }
  };

  setFocusToFirstItem(flag) {
    this.setFocusToItem(this.firstItem);
  };

  setFocusToLastItem(flag) {
    this.setFocusToItem(this.lastItem);
  };

  setFocusToPreviousItem(currentItem) {
    var index, newItem

    if (currentItem === this.firstItem) {
      newItem = this.lastItem;
    }
    else {
      index = this.menubarItems.indexOf(currentItem);
      newItem = this.menubarItems[ index - 1 ];
    }

    this.setFocusToItem(newItem);

  };

  setFocusToNextItem(currentItem) {
    var index, newItem

    if (currentItem === this.lastItem) {
      newItem = this.firstItem;
    }
    else {
      index = this.menubarItems.indexOf(currentItem);
      newItem = this.menubarItems[ index + 1 ];
    }

    this.setFocusToItem(newItem);

  };

  setFocusByFirstCharacter(currentItem, char) {
    var start, index, char = char.toLowerCase();
    var flag = currentItem.domNode.getAttribute('aria-expanded') === 'true';

    // Get start index for search based on position of currentItem
    start = this.menubarItems.indexOf(currentItem) + 1;
    if (start === this.menubarItems.length) {
      start = 0;
    }

    // Check remaining slots in the menu
    index = this.getIndexFirstChars(start, char);

    // If not found in remaining slots, check from beginning
    if (index === -1) {
      index = this.getIndexFirstChars(0, char);
    }

    // If match was found...
    if (index > -1) {
      this.setFocusToItem(this.menubarItems[ index ]);
    }
  };

  getIndexFirstChars(startIndex, char) {
    for (var i = startIndex; i < this.firstChars.length; i++) {
      if (char === this.firstChars[ i ]) {
        return i;
      }
    }
    return -1;
  };
}
















/*
*   This content is licensed according to the W3C Software License at
*   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
*/
class MenubarItem {
  constructor(domNode, menuObj){
    this.menu = menuObj;
    this.domNode = domNode;
    this.popupMenu = false;

    this.hasFocus = false;
    this.hasHover = false;

    this.isMenubarItem = true;

    this.keyCode = Object.freeze({
      'TAB': 9,
      'RETURN': 13,
      'ESC': 27,
      'SPACE': 32,
      'PAGEUP': 33,
      'PAGEDOWN': 34,
      'END': 35,
      'HOME': 36,
      'LEFT': 37,
      'UP': 38,
      'RIGHT': 39,
      'DOWN': 40
    });
  }

  init(){
    this.domNode.tabIndex = -1;

    this.domNode.addEventListener('keydown', this.handleKeydown.bind(this));
    this.domNode.addEventListener('focus', this.handleFocus.bind(this));
    this.domNode.addEventListener('blur', this.handleBlur.bind(this));
    this.domNode.parentNode.addEventListener('mouseenter', this.handleMouseover.bind(this));
    this.domNode.parentNode.addEventListener('mouseleave', this.handleMouseout.bind(this));

    // Initialize pop up menus

    var nextElement = this.domNode.nextElementSibling;

    if (nextElement && nextElement.tagName === 'UL') {
      this.popupMenu = new PopupMenu(nextElement, this);
      this.popupMenu.init();
    }
  }

  handleKeydown(event) {
    var tgt = event.currentTarget,
      char = event.key,
      flag = false,
      clickEvent;

    function isPrintableCharacter (str) {
      return str.length === 1 && str.match(/\S/);
    }

    switch (event.keyCode) {
      case this.keyCode.SPACE:
      case this.keyCode.RETURN:
      case this.keyCode.DOWN:
        if (this.popupMenu) {
          this.popupMenu.open();
          this.popupMenu.setFocusToFirstItem();
          flag = true;
        }
        break;

      case this.keyCode.LEFT:
        this.menu.setFocusToPreviousItem(this);
        flag = true;
        break;

      case this.keyCode.RIGHT:
        this.menu.setFocusToNextItem(this);
        flag = true;
        break;

      case this.keyCode.UP:
        if (this.popupMenu) {
          this.popupMenu.open();
          this.popupMenu.setFocusToLastItem();
          flag = true;
        }
        break;

      case this.keyCode.HOME:
      case this.keyCode.PAGEUP:
        this.menu.setFocusToFirstItem();
        flag = true;
        break;

      case this.keyCode.END:
      case this.keyCode.PAGEDOWN:
        this.menu.setFocusToLastItem();
        flag = true;
        break;

      case this.keyCode.TAB:
        typeof this.popupMenu.close == 'function' ? this.popupMenu.close(true) : null;
        break;

      case this.keyCode.ESC:
        typeof this.popupMenu.close == 'function' ? this.popupMenu.close(true) : null;
        break;

      default:
        if (isPrintableCharacter(char)) {
          this.menu.setFocusByFirstCharacter(this, char);
          flag = true;
        }
        break;
    }

    if (flag) {
      event.stopPropagation();
      event.preventDefault();
    }
  }

  setExpanded(value) {
    if (value) {
      this.domNode.setAttribute('aria-expanded', 'true');
    }
    else {
      this.domNode.setAttribute('aria-expanded', 'false');
    }
  };

  handleFocus(event) {
    this.menu.hasFocus = true;
  };

  handleBlur(event) {
    this.menu.hasFocus = false;
  };

  handleMouseover(event) {
    this.hasHover = true;
    if(this.popupMenu){
      this.popupMenu.open()
      $(this.domNode).closest('.menu-item').find('> .submenu-toggler').addClass('open')
    }
  };

  handleMouseout(event) {
    this.hasHover = false;
    if(this.popupMenu){
      setTimeout(this.popupMenu.close.bind(this.popupMenu, false), 50);
      $(this.domNode).closest('.menu-item').find('> .submenu-toggler').removeClass('open')
    }
  };
}








/*
*   This content is licensed according to the W3C Software License at
*   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
*/
class MenuItem{
  constructor(domNode, menuObj){
    this.domNode = domNode;
    this.menu = menuObj;
    this.popupMenu = false;
    this.isMenubarItem = false;

    this.keyCode = Object.freeze({
      'TAB': 9,
      'RETURN': 13,
      'ESC': 27,
      'SPACE': 32,
      'PAGEUP': 33,
      'PAGEDOWN': 34,
      'END': 35,
      'HOME': 36,
      'LEFT': 37,
      'UP': 38,
      'RIGHT': 39,
      'DOWN': 40
    });
  }

  init(){
    this.domNode.tabIndex = -1;
    this.domNode.addEventListener('keydown', this.handleKeydown.bind(this));
    this.domNode.addEventListener('click', this.handleClick.bind(this));
    this.domNode.addEventListener('focus', this.handleFocus.bind(this));
    this.domNode.addEventListener('blur', this.handleBlur.bind(this));
    this.domNode.addEventListener('mouseover', this.handleMouseover.bind(this));
    this.domNode.parentNode.parentNode.addEventListener('mouseleave', this.handleMouseout.bind(this));

    // Initialize flyout menu
    var nextElement = this.domNode.nextElementSibling;
    if (nextElement && nextElement.tagName === 'UL') {
      this.popupMenu = new PopupMenu(nextElement, this);
      this.popupMenu.init();
    }
  }

  isExpanded(){
    return this.domNode.getAttribute('aria-expanded') === 'true';
  }

  /* EVENT HANDLERS */
  handleKeydown(event) {
    var tgt  = event.currentTarget,
      char = event.key,
      flag = false,
      clickEvent;

    function isPrintableCharacter (str) {
      return str.length === 1 && str.match(/\S/);
    }

    switch (event.keyCode) {
      case this.keyCode.SPACE:
      case this.keyCode.RETURN:
        if (this.popupMenu) {
          this.popupMenu.open();
          this.popupMenu.setFocusToFirstItem();
        }
        else {

          // Create simulated mouse event to mimic the behavior of ATs
          // and let the event handler handleClick do the housekeeping.
          try {
            clickEvent = new MouseEvent('click', {
              'view': window,
              'bubbles': true,
              'cancelable': true
            });
          }
          catch (err) {
            if (document.createEvent) {
              // DOM Level 3 for IE 9+
              clickEvent = document.createEvent('MouseEvents');
              clickEvent.initEvent('click', true, true);
            }
          }
          tgt.dispatchEvent(clickEvent);
        }

        flag = true;
        break;

      case this.keyCode.UP:
        this.menu.setFocusToPreviousItem(this);
        flag = true;
        break;

      case this.keyCode.DOWN:
        this.menu.setFocusToNextItem(this);
        flag = true;
        break;

      case this.keyCode.LEFT:
        this.menu.setFocusToController('previous', true);
        this.menu.close(true);
        flag = true;
        break;

      case this.keyCode.RIGHT:
        if (this.popupMenu) {
          this.popupMenu.open();
          this.popupMenu.setFocusToFirstItem();
        }
        else {
          this.menu.setFocusToController('next', true);
          this.menu.close(true);
        }
        flag = true;
        break;

      case this.keyCode.HOME:
      case this.keyCode.PAGEUP:
        this.menu.setFocusToFirstItem();
        flag = true;
        break;

      case this.keyCode.END:
      case this.keyCode.PAGEDOWN:
        this.menu.setFocusToLastItem();
        flag = true;
        break;

      case this.keyCode.ESC:
        this.menu.setFocusToController();
        this.menu.close(true);
        flag = true;
        break;

      case this.keyCode.TAB:
        this.menu.setFocusToController();
        break;

      default:
        if (isPrintableCharacter(char)) {
          this.menu.setFocusByFirstCharacter(this, char);
          flag = true;
        }
        break;
    }

    if (flag) {
      event.stopPropagation();
      event.preventDefault();
    }
  };

  setExpanded(value) {
    if (value) {
      this.domNode.setAttribute('aria-expanded', 'true');
    }
    else {
      this.domNode.setAttribute('aria-expanded', 'false');
    }
  };

  handleClick(event) {
    this.menu.setFocusToController();
    this.menu.close(true);
  };

  handleFocus(event) {
    this.menu.hasFocus = true;
  };

  handleBlur(event) {
    this.menu.hasFocus = false;
    setTimeout(this.menu.close.bind(this.menu, false), 300);
  };

  handleMouseover(event) {
    this.menu.hasHover = true;
    this.menu.open();
    if (this.popupMenu) {
      this.popupMenu.hasHover = true;
      this.popupMenu.open();
    }
  };

  handleMouseout(event) {
    if (this.popupMenu) {
      this.popupMenu.hasHover = false;
      this.popupMenu.close(true);
    }

    this.menu.hasHover = false;
    setTimeout(this.menu.close.bind(this.menu, false), 300);
  };
}









/*
*   This content is licensed according to the W3C Software License at
*   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
*/
class PopupMenu {
  constructor(domNode, controllerObj){
    this.isMenubar = false;
    this.domNode    = domNode;
    this.controller = controllerObj;

    this.menuitems = []; // See PopupMenu init method
    this.firstChars = []; // See PopupMenu init method

    this.firstItem = null; // See PopupMenu init method
    this.lastItem = null; // See PopupMenu init method

    this.hasFocus = false; // See MenuItem handleFocus, handleBlur
    this.hasHover = false; // See PopupMenu handleMouseover, handleMouseout
  }

  init() {
    var childElement, menuElement, menuItem, textContent, numItems, label;

    // Configure the domNode itself

    this.domNode.addEventListener('mouseover', this.handleMouseover.bind(this));
    this.domNode.addEventListener('mouseleave', this.handleMouseout.bind(this));

    // Traverse the element children of domNode: configure each with
    // menuitem role behavior and store reference in menuitems array.
    childElement = this.domNode.firstElementChild;

    while (childElement) {
      menuElement = childElement.firstElementChild;

      if (menuElement && menuElement.tagName === 'A') {
        menuItem = new MenuItem(menuElement, this);
        menuItem.init();
        this.menuitems.push(menuItem);
        textContent = menuElement.textContent.trim();
        this.firstChars.push(textContent.substring(0, 1).toLowerCase());
      }
      childElement = childElement.nextElementSibling;
    }

    // Use populated menuitems array to initialize firstItem and lastItem.
    numItems = this.menuitems.length;
    if (numItems > 0) {
      this.firstItem = this.menuitems[ 0 ];
      this.lastItem = this.menuitems[ numItems - 1 ];
    }
  };

  /* EVENT HANDLERS */

  handleMouseover(event) {
    this.hasHover = true;
  };

  handleMouseout(event) {
    this.hasHover = false;
    setTimeout(this.close.bind(this, false), 1);
  };

  /* FOCUS MANAGEMENT METHODS */

  setFocusToController(command, flag) {

    if (typeof command !== 'string') {
      command = '';
    }

    function setFocusToMenubarItem (controller, close) {
      while (controller) {
        if (controller.isMenubarItem) {
          controller.domNode.focus();
          return controller;
        }
        else {
          if (close) {
            controller.menu.close(true);
          }
          controller.hasFocus = false;
        }
        controller = controller.menu.controller;
      }
      return false;
    }

    if (command === '') {
      if (this.controller && this.controller.domNode) {
        this.controller.domNode.focus();
      }
      return;
    }

    if (!this.controller.isMenubarItem) {
      this.controller.domNode.focus();
      this.close();

      if (command === 'next') {
        var menubarItem = setFocusToMenubarItem(this.controller, false);
        if (menubarItem) {
          menubarItem.menu.setFocusToNextItem(menubarItem, flag);
        }
      }
    }
    else {
      if (command === 'previous') {
        this.controller.menu.setFocusToPreviousItem(this.controller, flag);
      }
      else if (command === 'next') {
        this.controller.menu.setFocusToNextItem(this.controller, flag);
      }
    }

  };

  setFocusToFirstItem() {
    this.firstItem.domNode.focus();
  };

  setFocusToLastItem() {
    this.lastItem.domNode.focus();
  };

  setFocusToPreviousItem(currentItem) {
    var index;

    if (currentItem === this.firstItem) {
      this.lastItem.domNode.focus();
    }
    else {
      index = this.menuitems.indexOf(currentItem);
      this.menuitems[ index - 1 ].domNode.focus();
    }
  };

  setFocusToNextItem(currentItem) {
    var index;

    if (currentItem === this.lastItem) {
      this.firstItem.domNode.focus();
    }
    else {
      index = this.menuitems.indexOf(currentItem);
      this.menuitems[ index + 1 ].domNode.focus();
    }
  };

  setFocusByFirstCharacter(currentItem, char) {
    var start, index, char = char.toLowerCase();

    // Get start index for search based on position of currentItem
    start = this.menuitems.indexOf(currentItem) + 1;
    if (start === this.menuitems.length) {
      start = 0;
    }

    // Check remaining slots in the menu
    index = this.getIndexFirstChars(start, char);

    // If not found in remaining slots, check from beginning
    if (index === -1) {
      index = this.getIndexFirstChars(0, char);
    }

    // If match was found...
    if (index > -1) {
      this.menuitems[ index ].domNode.focus();
    }
  };

  getIndexFirstChars(startIndex, char) {
    for (var i = startIndex; i < this.firstChars.length; i++) {
      if (char === this.firstChars[ i ]) {
        return i;
      }
    }
    return -1;
  };

  /* MENU DISPLAY METHODS */

  open() {
    $(this.domNode).stop().slideDown(150).css('display', 'flex')


    //check if the node is off screen... if it is push it to the right
    var rect = this.domNode.getBoundingClientRect()
    if(rect.x + rect.width > window.innerWidth){
      this.domNode.classList.add('push-left')
    }

    // Get position and bounding rectangle of controller object's DOM node
    // var rect = this.controller.domNode.getBoundingClientRect();

    // Set CSS properties
    // if (!this.controller.isMenubarItem) {
    //   this.domNode.parentNode.style.position = 'relative';
    //   // this.domNode.style.display = 'block';
    //   this.domNode.style.position = 'absolute';
    //   this.domNode.style.left = rect.width + 'px';
    //   this.domNode.style.zIndex = 100;
    //   $(this.domNode).slideDown(150)
    // }
    // else {
    //   // this.domNode.style.display = 'block';
    //   this.domNode.style.position = 'absolute';
    //   this.domNode.style.top = (rect.height - 1) + 'px';
    //   this.domNode.style.zIndex = 100;
    //   $(this.domNode).slideDown(150)      
    // }

    this.controller.setExpanded(true);

  };

  close(force) {

    var controllerHasHover = this.controller.hasHover;

    var hasFocus = this.hasFocus;

    for (var i = 0; i < this.menuitems.length; i++) {
      var mi = this.menuitems[i];
      if (mi.popupMenu) {
        hasFocus = hasFocus | mi.popupMenu.hasFocus;
      }
    }

    if (!this.controller.isMenubarItem) {
      controllerHasHover = false;
    }

    if (force || (!hasFocus && !this.hasHover && !controllerHasHover)) {
      // this.domNode.style.display = 'none';
      // this.domNode.style.zIndex = 0;
      jQuery(this.domNode).stop().slideUp(150)
      this.domNode.style.pointerEvents = 'none'
      setTimeout(() => {
        this.domNode.style.pointerEvents = ''
      }, 250)
      this.controller.setExpanded(false);
    }
  };
}











export default MenuHelper