define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var dashboardModel = BaseModel.extend({

		changePostStatus : function(postId,status) {
			this.url = '/ws/post/status/'+status;
			this.fetch({ 
				type: 'POST',
				data: '[{ "postId": "'+postId+'"}]',
				complete: function(response) {
					return true;
				}
			});
		},

		parse : function(response) {
			return response.result;
		}	
	});
	return dashboardModel;
});