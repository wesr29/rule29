/**

  Usage:       
  
  <div class="parent-container">
    <select>
       <option></option>
       <option></option>
    </select>
    <select>
       <option></option>
       <option></option>
    </select>
  </div>

  new iziSelect('.parent-container', {
    onOpen   : el  => console.log(el),
    onClose  : el  => console.log(el),
    onSelect : val => console.log(val)
  })
  
 */


module.exports = class iziSelect{
  constructor(el, options = {}) {
    this.options = Object.assign({
      onOpen: () => { },
      onClose: () => { },
      onSelect: () => { }
    }, options)
    
    //find all selects within the parent element
    //and convert the html collection to array
    this.allSelects = Array.prototype.slice.call(document.querySelector(el).getElementsByTagName('select'))

    //replace each select with iziSelects
    this.allSelects.forEach(select => {
      this._addCustomHTML(select)
      this._addEventsToHTML(select)
    })
    
    //hide options if the user clicks pretty much anything 
    this._hideOptionsOnClickOff()
  }

  //see if an element has a parent of selector
  _getClosest(el, selector){
    // Get the closest matching elent
    for ( ; el && el !== document; el = el.parentNode ) {
      if ( el.matches( selector ) ) return el;
    }
    return null;
  }
  
  //close all selects on document click
  _hideOptionsOnClickOff(){
    document.addEventListener('click', (e) => {
      this.allSelects.forEach(select => {

        //if we are clicking within the container ignore the rest
        if(this._getClosest(e.target, '.izi-select--container')){
          return false
        } else {
          this._hideAllOptions()
        }
      })
    })
  }
  
  //hides all options container
  _hideAllOptions(){
    let containers = document.querySelectorAll('.izi-select--open')
    
    for(let i = 0; i < containers.length; i++){
      let container = containers[i]
      container.querySelector('.izi-select--options').style.display = 'none'
      container.classList.remove('izi-select--open')
      this.options.onClose(container)
    }
  }
  
  _openOptions(optionsContainer, container){
    this._hideAllOptions()
    optionsContainer.style.display = 'block'
    container.classList.add('izi-select--open')
    this.options.onOpen(container)
  }

  //add events to a select
  _addEventsToHTML(select){
    let container = this._getClosest(select, '.izi-select--container')
    let selectContainer = container.querySelector('.izi-select--current')
    let optionsContainer = container.querySelector('.izi-select--options')
    let options = container.querySelectorAll('.izi-select--option')

    //open the options container when the selected is clicked
    selectContainer.addEventListener('click', e => {
      this._openOptions(optionsContainer, container)
    })

    selectContainer.addEventListener('focus', e => {
      this._openOptions(optionsContainer, container)
    })

    // add a click event on each custom option
    // TODO: make these a function so we aren't copying code twice
    for(let i = 0; i < options.length; i++){          
      options[i].addEventListener('click', e => {
        let value = select.options[i].value

        //remove/add classes
        for(let x = 0; x < options.length; x++){
          options[x].classList.remove('izi-select--selected')
        }
        options[i].classList.add('izi-select--selected')

        //change the select
        select.value = value
        select.dispatchEvent(new Event("change"))

        //update the hmtl/style          
        selectContainer.innerHTML = select.options[i].innerHTML
        this._hideAllOptions()

        //run custom callback
        this.options.onSelect(value)
      })

      options[i].addEventListener('keyup', e => {
        if(e.keyCode !== 13){
          return
        }
        let value = select.options[i].value

        //remove/add classes
        for(let x = 0; x < options.length; x++){
          options[x].classList.remove('izi-select--selected')
        }
        options[i].classList.add('izi-select--selected')

        //change the select
        select.value = value
        select.dispatchEvent(new Event("change"))

        //update the hmtl/style          
        selectContainer.innerHTML = select.options[i].innerHTML
        this._hideAllOptions()

        //run custom callback
        this.options.onSelect(value)
      })
    }
    
    //TODO : make sure the changes go both ways
    // select.addEventListener('change', e => {
    //   console.log(e)
    // })
  }
  
  //adds custom HTML around a specific select
  _addCustomHTML(select){
    //hide the original select
    select.style.visibility = 'hidden'
    select.style.position = 'absolute'
    select.style.left = '-9999px'

    //wrap the select in a container
    let wrap = document.createElement('div')
    select.before(wrap)
    wrap.appendChild(select)
    wrap.classList.add('izi-select--container')

    //add current box to container
    let current = document.createElement('div')
    wrap.appendChild(current)
    current.classList.add('izi-select--current')
    current.innerHTML = select.options[select.selectedIndex].innerHTML
    current.tabIndex = 0

    //create options container
    let optionsContainer = document.createElement('div')
    wrap.appendChild(optionsContainer)
    optionsContainer.classList.add('izi-select--options')
    optionsContainer.style.display = 'none'      

    //create each option
    for(let i = 0; i < select.options.length; i++){
      let option = document.createElement('div')
      option.innerHTML = select.options[i].innerHTML
      optionsContainer.appendChild(option)
      option.classList.add('izi-select--option')
      option.tabIndex = 0
      
      if(select.value == select.options[i].value){
        option.classList.add('izi-select--selected')
      }
      
      if(select.options[i].disabled){
        option.classList.add('izi-select--disabled')
        option.style.pointerEvents = 'none'
      }
    }   
  }
}