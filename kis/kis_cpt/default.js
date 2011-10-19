;(function($) {
	$(document).ready(function() {
		$("a.kis_cpt_upload_btn").click(function(e) {
			$("a.kis_cpt_upload_btn").removeClass("kis_cpt_upload_btn_clicado");
			$(this).addClass("kis_cpt_upload_btn_clicado");
			
			e.preventDefault();
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		});
		
		$("a.kis_cpt_upload_remove_btn").click(function(e) {
			e.preventDefault();
			if( confirm("Tem certeza que deseja remover a imagem?") ) {
				var field_id = $(this).data("ref-field");
				$("#"+field_id).val("");
			}
		});
		
		if($("a.kis_cpt_upload_btn").is("*")) {
			window.send_to_editor = function(html) {
				imgurl = $('img',html).attr('src');
				
				var field_id = $("a.kis_cpt_upload_btn_clicado").data("ref-field");
				$("#"+field_id).val(imgurl);
				tb_remove();
			}
		}
		
	});
})(jQuery);