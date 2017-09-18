define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var articlesTestModel = BaseModel.extend({
		url: '/ws/article/test/'
	});
	return articlesTestModel;
});