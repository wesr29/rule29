var $ = jQuery
var fillContainer = function( options ) {
  
    var defaults = {
        aspectRatio   : null,
        parent        : '',
        fillMode      : 'fill',
        continuous    : true,
        cropFactor    : [ 1, 1 ],
        offset        : [ 0, 0 ],
        debounceDelay : 100
    };

    options = $.extend( defaults, options );

    this.each(function() {
        var element = $( this );
        // merge options from inline attributes
        var elementOptions = $.extend( options, element.data() );
        
        var debounceTimer = null;

        // get basic infos about element
        var elementWidth  = element.width();
        var elementHeight = element.height();
        var parentElement = elementOptions.parent ? element.parents( elementOptions.parent ) : element.parent();

        if ( null === elementOptions.aspectRatio ) {
            elementOptions.aspectRatio = element.width() / element.height();
        }

        if ( -1 === $.inArray( elementOptions.fillMode, ['fill', 'fit'] ) ) {
            elementOptions.fillMode = 'fill';
        }

        // fix some css issues
        fixCSS();
        // resize element to fit parent, now and then
        resizeElement();

        if ( true === elementOptions.continuous ) {
            $(window).on( 'resize.fillcontainer', function(){
                
                if ( 0 === elementOptions.debounceDelay ) resizeElement();
                else {
                    clearTimeout( debounceTimer );
                    debounceTimer = window.setTimeout(function() {
                        resizeElement();
                    }, elementOptions.debounceDelay );
                }

            } );
        }
        
        function fixCSS(){
          $(parentElement).css('overflow', 'hidden')
        }
      
        function resizeElement ( ) {
            
            var parentWidth = parentElement.outerWidth();
            var parentHeight = parentElement.outerHeight();
            var parentRatio = parentWidth / parentHeight;

            if ( ( ( 'fill' === elementOptions.fillMode ) && ( parentRatio > elementOptions.aspectRatio ) )
               || ( ( 'fit' === elementOptions.fillMode ) && ( parentRatio < elementOptions.aspectRatio ) ) ) {
                
                element.css({
                    'width'       : parentWidth,
                    'height'      : Math.ceil( parentWidth / elementOptions.aspectRatio ),
                    'margin-left' : 0 + elementOptions.offset[0],
                    'margin-top'  : - Math.ceil( ( parentWidth / elementOptions.aspectRatio - parentHeight ) / 2 ) + elementOptions.offset[1]
                });
            }
            else {
                
                element.css({
                    'height'      : parentHeight,
                    'width'       : Math.ceil( parentHeight * elementOptions.aspectRatio ),
                    'margin-top'  : 0 + elementOptions.offset[1],
                    'margin-left' : - Math.ceil( ( parentHeight * elementOptions.aspectRatio - parentWidth ) / 2 ) + elementOptions.offset[0]
                });
            }
        }
    });
    return this;

};

export default fillContainer