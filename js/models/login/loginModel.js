define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var loginModel = BaseModel.extend({
		url : '/ws/authentication/login/',

		parse : function(response) {
			return response;
		}	
	});
	return loginModel;
});