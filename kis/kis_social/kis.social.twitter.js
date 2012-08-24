/**
 * Classe com funções do Kis.Social.Twitter
 *
 * @version 1.1
 * @author André Gumieri
 */
 
if(!window['Kis']) { var Kis={} }
(function(namespace, $) {
	if(!namespace.Social) { namespace.Social = {}; }
	namespace.Social.Twitter = {
		init: function(options) {
			var settings = {
				'containerId': '',
				'url': '',
				'twitterUser': '',
				'qty': '',
				'vars': {}
			}
			$.extend(settings, options);
			
			this.show(settings);
		},
		
		show: function(settings) {
			var $container = $("#"+settings.containerId);
			$container.addClass("carregando");
			$container.html("<div class=\"carregando\">Carregando...</div>");
			
			var $lista = $("<ul class=\"tweets\">");
			
			// Variaveis de configuracao
			var vars = {
				user: settings.twitterUser,
				qty: settings.qty
			}
			$.extend(vars, settings.vars);
			
			// Pega a lista de tweets
			$.post(settings.url, vars, function(data) {
				console.log(data);
				$container.removeClass("carregando");

				if(data.error) {
					var $li = $("<li class=\"tweet\" />");
					$li.addClass("error");
					$li.append("<div class=\"text\">" + data.error + "</div>");
					$lista.append($li);
					$container.html($lista);
					return ;
				}
				
				// Faz o loop em todos os tweets
				$.each(data, function(key, value) {
					var $li = $("<li class=\"tweet\" />");
					
					// Adiciona a imagem
					if(value.retweeted_status) {
						$li.addClass("retweet");
						$li.append("<img src=\"" + value.retweeted_status.user.profile_image_url + "\" >");
					} else {
						$li.append("<img src=\"" + value.user.profile_image_url + "\" >");
					}
					
					
					// Prepara o texto para adição de links
					var newText = value.text;
					
					
					// Coloca link nas URLs
					if(value.entities.urls && value.entities.urls.length>0) {
						$li.addClass("has-links");
						$.each(value.entities.urls, function(urlKey, urlValue) {
							newText = Kis.General.replaceAll(newText, urlValue.url, "<a href=\"" + urlValue.expanded_url + "\" target=\"_blank\">" + urlValue.display_url + "</a>");
						});
					}
					
					
					// Coloca LINK nos nomes e usuários
					if(value.entities.user_mentions && value.entities.user_mentions.length>0) {
						$li.addClass("has-username");
						$.each(value.entities.user_mentions, function(userKey, userValue) {
							$li.addClass("has-username-"+userValue.screen_name);
							newText = Kis.General.replaceAll(newText, "@"+userValue.screen_name, "<a href=\"http://twitter.com/"+userValue.screen_name + "\" target=\"_blank\" class=\"username\">" + "@{REPLACEALL}"+userValue.screen_name + "</a>");
							newText = Kis.General.replaceAll(newText, "{REPLACEALL}", "");
						});
					}
					
					
					// Coloca LINK nas Hashtags
					if(value.entities.hashtags && value.entities.hashtags.length>0) {
						$li.addClass("has-hashtag");
						$.each(value.entities.hashtags, function(hashKey, hashValue) {
							$li.addClass("has-hashtag-"+hashValue.text);
							newText = Kis.General.replaceAll(newText, "#"+hashValue.text, "<a href=\"http://twitter.com/#!/search?q=%23"+hashValue.text + "\" class=\"hashtag hashtag-"+hashValue.text+"\" target=\"_blank\">" + "#{REPLACEALL}"+hashValue.text + "</a>");
							newText = Kis.General.replaceAll(newText, "{REPLACEALL}", "");
						});
					}
					
					newText = "<span class=\"texto\">" + newText + "</span>";
					
					// Coloca o link para o profile no começo do texto
					newText = "<a href=\"http://twitter.com/"+value.user.screen_name+"\" target=\"_blank\" class=\"screen_name\">@"+value.user.screen_name+"</a> " + newText;
					
					// Adiciona o tempo por escrito
					newText = newText + " <span class=\"tempo_escrito\">" + value.tempoEscrito + "</span>";
				
					// Coloca o tweet na lista
					$li.append("<div class=\"text\">" + newText + "</div>");
					$lista.append($li);
				});
				
				$container.html($lista);
			}, "json");
		}
	}
})(Kis, jQuery);