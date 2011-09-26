/**
 * Plugin que pré-carrega imagens
 * @author André Gumieri
 * @version 1.0.0
 */
 (function($) {
 	$.fn.preloadImages = function(options, callback) {
 		var settings = {
 			aditionalImages: []
 		}
 		
 		var qtyload = 0;
 		var qtyloaded = 0;
 		
 		if (typeof options == 'function') { // make sure the callback is a function
 			callback = options; // brings the scope to the callback
 		} else {
 			$.extend(settings, options);
 		}
 		
 		var execCallback = function() {
 			if(qtyloaded==qtyload) {
 				if (typeof callback == 'function') { // make sure the callback is a function
 					callback.call(this); // brings the scope to the callback
 				}
 			}
 		}
 		
 		var $this = $(this);
 		var load = Array();
 		$this.each(function() {
 			if($(this).is("img") && $(this).attr("src")!="") {
 				load.push($(this).attr("src"));
 				qtyload++;
 			}
 		});
 		
 		var x=0;
 		for(x; x<settings.aditionalImages.length; x++) {
 			load.push(settings.aditionalImages[x]);
 			qtyload++;
 		}
 		
 		x=0;
 		for(x; x<load.length; x++) {
 			var img = document.createElement("img");
 			img.src = load[x];
 			$(img).bind("load", function() {
 				qtyloaded++;
 				execCallback();
 			});
 			$(img).bind("error", function() {
 				qtyloaded++;
 				execCallback();
 			});
 			//console.log(load[x]);
 		}
 		
 		
 	}
 })(jQuery);