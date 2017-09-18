define(['backbone'], function(Backbone) {
	var LangModel = Backbone.Model.extend({	
		 defaults : {
	       userlang : 'es.json',
	       locale : 'ES',
	       langData : ''
		},

		parse : function(response) {
			return response.data;
		}
	});
	return LangModel;
});