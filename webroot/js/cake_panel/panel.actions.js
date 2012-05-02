



	Panel.actionLinks = function() {
		
		var _handleConfirm = function() {
			
			var $this = $(this);
			
			$this.bind('click',function( e ){
				
				var _url = $(this).attr('href');
				
				var _msg = '<p>Do you really want to delete selected item?<br>Do you know you can\'t revert this action?';
				if ( $this.attr('data-confirm-msg') ) _msg = $this.attr('data-confirm-msg');
				
				e.preventDefault ();
				
				Panel.confirm({
					text:			_msg,
					confirmText:	'Yes, Delete!',
					ok: 			function () { window.location = _url }
				});
				
				
			});
		
		}
		
		$('.ui-action').each(function(){
		
			var $this 	= $(this);
			var _icon	= '';
			
			if ( $this.hasClass('ui-action-init') ) return;
			
			$this.addClass('ui-action-init btn btn-blue btn-small');
			
			// Check for a custom icon request.
			if ( $this.attr('data-icon') ) _icon = $this.attr('data-icon');
			
			// Setup an action icon
			if ( _icon.length ) {
				$this.addClass('incon-holder');
				$this.html('<span class="' + _icon + '"></span>' + $this.html() );
			}
			
			// Handle a confirmation message request.
			if ( $this.attr('data-confirm-msg') ) _handleConfirm.call( this );
			
		});
		
		
		$('.ui-action-delete').each(function(){
			
			var $this 	= $(this);
			var _icon	= 'icon-x';
			
			if ( $this.hasClass('ui-action-init') ) return;
			
			$this.addClass('ui-action-init btn btn-red btn-small incon-holder');
			
			// Check for a custom icon request.
			if ( $this.attr('data-icon') ) _icon = $this.attr('data-icon');
			
			// Setup internal content for icon handling.
			$this.html('<span class="' + _icon + '"></span>' + $this.html() );
			
			_handleConfirm.call( this );
			
		});
		
		
		$('.ui-action-edit').each(function(){
		
			var $this = $(this);
			var _icon	= 'icon-pen';
			
			if ( $this.hasClass('ui-action-init') ) return;
			
			$this.addClass('ui-action-init btn btn-blue btn-small incon-holder');
			
			// Check for a custom icon request.
			if ( $this.attr('data-icon') ) _icon = $this.attr('data-icon');
			
			// Setup internal content for icon handling.
			$this.html('<span class="' + _icon + '"></span>' + $this.html() );
			
			// Handle a confirmation message request.
			if ( $this.attr('data-confirm-msg') ) _handleConfirm.call( this );
			
		});
		
	
	};