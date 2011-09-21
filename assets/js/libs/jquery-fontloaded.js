/**
 * Plugin que verifica se uma fonte foi carregada
 * @author André Gumieri
 * @version 1.0
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
			console.log("verifying");
			var w = $this.width();
			var h = $this.height();
			
			if( w!=initialWidth || h!=initialHeight ) { execCallback(); console.log("font_loaded"); }
			if(verifyDone==true) { clearInterval(verify_interval); console.log("interval_cleared"); }
			
		}, settings.verify_interval);
		
		$(window).load(function() {
			console.log("window_loaded");
			execCallback();
		});
		
	}
})(jQuery)