define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var caModel = BaseModel.extend({
		url : '/ws/blog/search/',

		parse : function(response) {
			return response;
		}
	});
	return caModel;
});