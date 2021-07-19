/**
 * This is the main js file, any global JS can go into here, otherwise it should go in a block
 */
import lazySizes from 'lazysizes'
import touchDetector from './plugins/touchDetector'
import smoothScroller from './plugins/smoothScroller'
import animations from './plugins/animations'

 //lazy images
lazySizes.cfg.lazyClass = 'lazy'
setTimeout(lazySizes.init, 250)

//add touch detection
touchDetector()

//add smooth scroll to #jump-links
smoothScroller()

//add animations
//elements should have a class of `will-animate` and a `data-animation="animation-class"` where animation class is in sass/global/animations.sass
animations()