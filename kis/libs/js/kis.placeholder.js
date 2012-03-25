/**
 * Placeholder
 *
 * @version 1.0
 * @author André Gumieri
 */
if(!window['Kis']) { var Kis={} }
;(function(namespace, $) {
	namespace.Placeholder = {
		init: function($form) {
			$form.find("input[type=text],textarea").each(function(i) {
				var placeHolder = $(this).attr("data-placeHolder");
				$(this).data("placeHolder", placeHolder);
				$(this).val(placeHolder);
				$(this).addClass("with-placeholder");
			});
			
			this.events($form);
		},
		
		events: function($form) {
			var $campos = $form.find("input,textarea");
			$campos.focus(function() {
				var val = $(this).val();
				var ph = $(this).data("placeHolder");
				
				if(val!=ph || $(this).attr("readonly")=="readonly")
					return ;
					
				$(this).val("");
				$(this).removeClass("with-placeholder")
			});
			
			$campos.blur(function() {
				var val = $(this).val();
				var ph = $(this).data("placeHolder");
				
				if(val!="") 
					return ;
					
				$(this).val(ph);
				$(this).addClass("with-placeholder");
			});
		}
	};
})(Kis, jQuery);