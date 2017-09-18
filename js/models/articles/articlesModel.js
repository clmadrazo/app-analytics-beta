define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var articlesModel = BaseModel.extend({
		url: '/ws/article/published/retrieve/',

		parse : function(response) {
			return response;
		}
	});
	return articlesModel;
});