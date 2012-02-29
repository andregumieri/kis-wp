;(function($) {
	$(document).ready(function() {
		$("div.kis_manager_panel").eq(0).addClass("kis_manager_panel_showed");
		
		/* Abas */
		$("h2.nav-tab-wrapper a").click(function(e) {
			e.preventDefault();
			var $abas = $("div.kis_manager_panel");
			var $aba_ativa = $("div.kis_manager_panel_showed");
			var $aba_clicada = $("#kis_manager_" + $(this).attr("href").substr(1));

			if($aba_clicada.hasClass("kis_manager_panel_showed")==true) 
				return false;
				
			$(this).siblings().removeClass("nav-tab-active");
			$(this).addClass("nav-tab-active");
			
			$aba_ativa.fadeOut("fast", function() {
				$(this).removeClass("kis_manager_panel_showed");
				$aba_clicada.fadeIn("fast");
				$aba_clicada.addClass("kis_manager_panel_showed");
			});
		});
		
		/* Botões de Upload */
		$("a.kis_manager_upload_btn").click(function(e) {
			$("a.kis_manager_upload_btn").removeClass("kis_manager_upload_btn_clicado");
			$(this).addClass("kis_manager_upload_btn_clicado");
			
			e.preventDefault();
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		});
		
		$("a.kis_manager_upload_remove_btn").click(function(e) {
			e.preventDefault();
			if( confirm("Tem certeza que deseja remover a imagem?") ) {
				var field_id = $(this).data("ref-field");
				$("#"+field_id).val("");
			}
		});
		
		if($("a.kis_manager_upload_btn").is("*")) {
			window.send_to_editor = function(html) {
				imgurl = $('img',"<div>"+html+"</div>").attr('src');
				
				var field_id = $("a.kis_manager_upload_btn_clicado").data("ref-field");
				$("#"+field_id).val(imgurl);
				tb_remove();
			}
		}
	});
})(jQuery);