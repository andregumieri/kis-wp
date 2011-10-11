/**
 * Classe com funções do Analytics
 *
 * @version 1.0.1
 * @author André Gumieri
 */
 
if(!window['Kis']) { var Kis={} }
(function(namespace) {
	namespace.Analytics = {
		gag: function() {
			return window['_gaq'] || [];
		},
		
		event: function(category, action) {
			this.gag().push(['_trackEvent', category, action]);
		},
		
		pageView: function(page) {
			this.gag().push(['_trackPageview', page]);
		} 
	}
})(Kis);