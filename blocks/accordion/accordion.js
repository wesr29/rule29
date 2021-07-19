~(function($){
  function init(){
  	$('.accordion--title').click(event => {

  		let parent = $(event.target).closest('.accordion--section')
  		let title = parent.find('.accordion--title')
  		let target = parent.find('.accordion--content')

			if (parent.hasClass('active')) {
				parent.removeClass('active')
				target.slideUp()
				title.attr('aria-expanded', 'false')
			} else {
				parent.addClass('active')
				target.slideDown()
				title.attr('aria-expanded', 'true')
			}

  	})
  }
  $(document).ready(init)

})(jQuery)
