module.exports = class iziAnimateSVG {
  constructor(el, options){
    this.el = el
    this.paths = this.getPaths()
    this.direction = 'forward'
    
    //user options
    this.options = Object.assign({
      speed: 1,
      repeat: false,
      repeatDelay: 0,
      reverse: false,
      reverseOnEnd: false,
      onEnd: () => { }
    }, options)
    
    //start it up boiii
    requestAnimationFrame(() => { this.animate() })
  }
  
  //returns an array of path objects
  getPaths(){
    const paths = []
    const els = document.querySelectorAll(this.el)
            
    els.forEach(el => {
      paths.push({ 
        el: el,
        pathLength: 0,
        totalLength: el.getTotalLength(),
        done: false
      })
    })
        
    return paths
  }
  
  //this does all the heavy lifting
  animate(){    
    this.paths.forEach(path => {
      if(this.direction == 'forward'){
        if(path.pathLength - this.options.speed < path.totalLength){
          path.el.style.strokeDasharray = [path.pathLength, path.totalLength].join(' ')
          path.pathLength += this.options.speed
        } else {
          path.done = true
        }        
      } else {
        if(path.pathLength + this.options.speed > 0){
          path.el.style.strokeDasharray = [path.pathLength, path.totalLength].join(' ')
          path.pathLength -= this.options.speed
        } else {
          path.done = true
        } 
      }
    })
        
    //if any paths are still animating, keep going
    if(this.paths.filter(path => path.done == false).length){
      requestAnimationFrame(() => { this.animate() })
    } else {
      //all animations are finished
      if(this.options.reverseOnEnd){
        this.direction = 'reverse'
        this.paths.map(path => {
          path.done = false
        })

        //if there are any paths that need animation, call animate
        if(!this.paths.filter(path => path.pathLength <= 0).length){
          setTimeout(() => {
            requestAnimationFrame(() => { this.animate() })
          }, this.options.repeatDelay)
        } else {
          if(this.options.repeat){
            this.paths.map(path => path.done = false)
            this.direction = 'forward'
            setTimeout(() => {
              requestAnimationFrame(() => { this.animate() })   
            }, this.options.repeatDelay)              
          } else {
            this.options.onEnd()
          }
        }
      } else {
        if(this.options.repeat){
          setTimeout(() => {
            this.paths.map(path => {
              path.pathLength = 0
              path.done = false
            })
            requestAnimationFrame(() => { this.animate() })
          }, this.options.repeatDelay)
        } else {
          this.options.onEnd()
        }
      }
    }
  }
}
