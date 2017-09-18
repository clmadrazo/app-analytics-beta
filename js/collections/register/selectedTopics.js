define(['collections/baseCollection', 'models/register/topicsModel'], function(BaseCollection, Topic) {
	var selectedTopics = Backbone.Collection.extend({
		title : 'Tópico selecionado',
		model : Topic
	});
	return selectedTopics;
});