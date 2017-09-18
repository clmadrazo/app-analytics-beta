define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
	var registerModel = BaseModel.extend({
		url : '/ws/registration',

		registerUser : function(name,lastName,email,password) {
			this.fetch({
				type: 'POST',
				data: '[{ "name": "'+name+'","lastName": "'+lastName+'","email": "'+email+'","password": "'+password+'"}]',
				complete: function (xhr) {
					var statusCode = xhr.status;
				    if (statusCode == 200) 
				    {
				     	session.normalLogin(email,password,true);
				    }
				    else
				    {
				    	if (statusCode == 404)  {
				    		$('#email').after('<label for="email" class="error">'+PORT[0].langData.validations.emailExists+'</label>');
				    	}
				    }			    			    	
				}
			});
		}
	});
	return registerModel;
});