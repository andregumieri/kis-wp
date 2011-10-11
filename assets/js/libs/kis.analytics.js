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
			this.gaq().push(['_trackPageview', page]);
		} 
	}
})(Kis);