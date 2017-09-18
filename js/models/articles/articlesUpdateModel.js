define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var articlesUpdateModel = BaseModel.extend({
		url: '/ws/article/published/'
	});
	return articlesUpdateModel;
});