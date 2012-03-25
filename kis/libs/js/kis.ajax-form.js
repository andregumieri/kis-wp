/**
 * Ajax Form
 *
 * @version 1.0
 * @author André Gumieri
 */
if(!window['Kis']) { var Kis={} }
;(function(namespace, $) {
	namespace.AjaxForm = {
		init: function($form, wpAjaxFunction, mensagemDeSucesso) {
			if(typeof(mensagemDeSucesso)=="undefined") {
				mensagemDeSucesso = "Mensagem enviada. Obrigado.";
			}
			this.events($form, wpAjaxFunction, mensagemDeSucesso);
		},
		
		events: function($form, wpAjaxFunction, mensagemDeSucesso) {
			
			$form.submit(function(e) {
				e.preventDefault();
				var dados = new Object();
				$(this).find("input,textarea,select").each(function() {
					var objname = $(this).attr("name");

					var content = "";
					if(typeof($(this).data("placeHolder"))!="undefined") {
						if($(this).val()!=$(this).data("placeHolder")) {
							content = $(this).val();
						}
					} else {
						content = $(this).val();
					}
					
					content = Kis.General.replaceAll(content, "\r\n","<br />");
					content = Kis.General.replaceAll(content, "\r","<br />");
					content = Kis.General.replaceAll(content, "\n","<br />");
					
					if($(this).attr("type")=="checkbox") {
						objname = objname + "_" + $(this).val();
						if($(this).is(":checked")) {	
							content = $(this).val();
						}
					}
					
					if($(this).is("select")) {
						content = $(this).find("option:selected").val();
					}
					
					dados[objname]=content;
				});
				
				var $capa = $("<div />");
				$capa.addClass("formCapa");
				$capa.css("position", "absolute");
				$capa.css("top", "0");
				$capa.css("left", "0");
				$capa.css("z-index", "10");
				$capa.width($(this).width());
				$capa.height($(this).height());
				
				$(this).css("position", "relative");
				$(this).parent().addClass("carregando");
				$(this).prepend($capa.clone());
				$(this).find("*").attr("readonly", "readonly");
				$(this).animate({opacity: 0.2}, {duration: 300, complete: function() {
					dados["action"]=wpAjaxFunction;
					$.post($(this).attr("action"), dados, function(ret) {
						if( ret.toLowerCase()!="ok" ) {
							alert(ret);
						} else {
							alert(mensagemDeSucesso);
							$form.find("input[type=text],textarea").each(function() {
								$(this).val($(this).data("placeHolder"));
							});
							
							$form.find("select").each(function() {
								$(this).find("option").eq(0).attr("selected", true);
							});
						}
						
						$form.parent().removeClass("carregando");
						$form.find(".formCapa").remove();
						$form.find("*").attr("readonly", false);
						$form.animate({opacity: 1}, {duration: 300});
					});
				}});	// Fim Fadeout
	
				return false;
				
			});
		}
	};
})(Kis, jQuery);