/**
 * Plugin que verifica se uma fonte foi carregada
 * @author André Gumieri
 * @version 1.0.1
 */
(function($) {
	$.fn.fontLoaded = function(options, callback) {
		var settings = {
			'verify_interval': '100'
		}
		if (typeof options == 'function') { // make sure the callback is a function
			callback = options; // brings the scope to the callback
		} else {
			$.extend(settings, options);
		}
		
	
		var $this = $(this).eq(0);
		var original_font = $this.css("font-family")
		$this.css("font-family", "sans-serif");
		var initialWidth = $this.width();
		var initialHeight = $this.height();
		var verifyDone = false;
		
		var execCallback = function() {
			if(verifyDone == false) {
				verifyDone = true;
				if (typeof callback == 'function') { // make sure the callback is a function
					callback.call(this); // brings the scope to the callback
				}
			}
		}
		
		var verify_interval = window.setInterval(function() {
			var w = $this.width();
			var h = $this.height();
			
			if( w!=initialWidth || h!=initialHeight ) { execCallback(); }
			if(verifyDone==true) { clearInterval(verify_interval); }
			
		}, settings.verify_interval);
		
		$(window).load(function() {
			execCallback();
		});
		$this.css("font-family", original_font);
	}
})(jQuery)