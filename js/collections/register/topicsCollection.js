define(['collections/baseCollection', 'models/register/topicsModel'], function(BaseCollection, Topic) {
	var TopicsCollection = BaseCollection.extend({
		title : 'Lista de Tópicos',
		model : Topic,
		url : function() {
			return '/ws/list/topic';
		},

		initialize : function( options ) {			
			this.lastIndexVal = 0;
		},

		parse : function(response) {
			return response.items;
		}
	});
	return TopicsCollection;
});