// this adds a preview image to ACF modules
// each modules/block should have a corresponding JPG in the `images/block-previews` directory with the same slug as the module name
function acfModulePreviews(){
  ~(function($){
    function changeImages() {
      $('.acf-tooltip ul li a').hover(function(){
        let imageLayoutSlug = $(this).attr('data-layout')
        let image = new Image()
        let imageSource = adminLocal.directory + '/public/images/acf-module-previews/' + imageLayoutSlug + '.jpg'
        image.onload = function() {
          $('.acf-tooltip').append('<div class="module-preview"><img src="'+imageSource+'"></div>')
        }
        image.onerror = function(){
          imageSource = adminLocal.directory + '/public/images/acf-module-previews/' + imageLayoutSlug + '.png'
          image.src = imageSource
        }
        

        image.src = imageSource
      }, function(){
        $('.module-preview').remove()
      })
    }
    function checkDOMChange() {
      let toolTips = document.querySelectorAll('.acf-tooltip ul li')
      if (toolTips.length) {
        changeImages(toolTips)
      } else {
        setTimeout( checkDOMChange, 100 )
      }
    }
    function init(){
      $('a[data-name=add-layout]').click( () => {
        checkDOMChange()
      })
    }
    $(document).ready(init)
  })(jQuery)
}

export default acfModulePreviews