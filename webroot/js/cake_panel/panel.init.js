



/**
 * 
 */


	Panel.init = function () {

	
		var $formActions 	= null;
		var $contentHeader 	= $('#contentHeader');
		var $content		= $('#content');
		var $mainMenu		= $('#sidebar');
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/**
		 * Sticky Toolbar
		 */
		
		$contentHeader.scrollSticky({
			onSticky: function() {
				
				$contentHeader.css({
					marginTop: '-5px',
					width: $('#content').outerWidth()
				});
				
				
				$('.sideSticky').addClass('stiky-active');
				
			},
			onUnsticky: function() {
				$('.sideSticky').removeClass('stiky-active');
			}
		});
		
		// Sticky on main menu.
		$mainMenu.scrollSticky({ usePlaceholder:false });
		
		
		
		
		/**
		 * Form Stiky Actions Bar
		 */
		
		var $formActions = false;
		
		if ( $('.container form').length == 1 ) {
			
			$formActions = $('.container form .form-actions');
			
		} else if ( $('.container form.main').length ) {
			
			$formActions = $('.container form.main-form .form-actions');
			
		} else {
			
			$formActions = $('.container form:first .form-actions');
			
		}
		
		if ( $formActions ) {
			
			$formActions.html('<div class="wrap">'+$formActions.html()+'</div>').addClass('sticky-form-actions').css({
				top: 	$(window).height() - $formActions.outerHeight(),
				left:	$content.offset().left,
				width:	$content.outerWidth()
			});
			
			$(window).keypress(function(e) {
				
				if ( (e.ctrlKey || e.metaKey) && ( e.which == 115 ) ) {
					
					$formActions.trigger('submit');
					
					e.preventDefault();
					
				}
				
			});
		
		}
		
		
		
		/**
		 * Window Resize Events
		 */
		var onWindowResizeTimeout;
		$(window).resize(function(){
			
			// Adjust view's title bar dimensions
			if ( $contentHeader ) $contentHeader.css({
				width: $content.outerWidth()
			});
			
			// Adjust form actions bar size and fixed position.
			if ( $formActions ) $formActions.css({
				top: 	$(window).height() - $formActions.outerHeight(),
				left:	$content.offset().left,
				width:	$content.outerWidth()
			});
			
			
			// Delayed Events:
			// Performance optimization for operations that not require real time application.
			clearTimeout(onWindowResizeTimeout);
			onWindowResizeTimeout = setTimeout(function(){},300);
			
		});
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/**
		 * Fetch error messages from the DOM and use Tipsy to display
		 * around the field.
		 */
		
		Panel.formErrors.call($('form.form .form-error'));
		
		
		
		/**
		 * Automagically ajaxForm
		 */
		$('form.ajaxForm, form[data-ajax]').each(function(){
			
			Panel.ajaxForm.call( $(this) );
			
		});
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/**
		 * JumpMenu
		 */
		$('form.jump-menu').each(function(){
			
			var $this = $(this);
			
			$this.find('input[type=submit]').hide();
			
			$this.find('select').bind('change',function(){
				$this.submit();
			});
			
		});
		
		
		
		
		
		
		/**
		 * Init action links
		 */
		Panel.actionLinks();
		
		
		


};
