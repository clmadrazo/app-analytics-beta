define(['backbone', 'models/baseModel', 'models/login/loginModel'], function(Backbone, BaseModel, LoginModel) {
	var sessionModel = BaseModel.extend({
		defaults : {
			"logged" : false,
			"name" : "",
			"pic" : "",
			"uid" : "",
			"token" : "",

		},
		initialize : function() {
			this.loginModel = new LoginModel();

			var localstorage = localstorage || false;

			if (localstorage) {

				if (localstorage.get('logged') === true)
				this.set('logged',true);
			
				if (typeof(localstorage.get('uid')) !== undefined)
					this.set('uid',localstorage.get('uid'));

				if (typeof(localstorage.get('token')) !== undefined)
					this.set('token',localstorage.get('token'));	
				
			}

			
		},
		normalLogin : function(user,password,register,view) {
			$('#errcontainerlogin').hide();
			var that = this;
			$(view.overlay).show();
			var goTopics = register || false;
			this.loginModel.title =  Polyglo.t('login.entering');//'Autenticando na plataforma';
			this.loginModel.loading = true;
			this.loginModel.fetch({
				removeBearer: true,
				type: 'POST',
				data: '[{ "user": "'+user+'", "password":"'+password+'"}]',
				complete: function (xhr) {
					var statusCode = xhr.status;
				    if (statusCode == 200) 
				    {
				     	var userId = xhr.getResponseHeader('X-User-Id');
				     	var token = xhr.getResponseHeader('Bearer-Token');
						var refresh = xhr.getResponseHeader('Refresh-Token');
						var image = xhr.getResponseHeader('image');
						var languageCode = xhr.getResponseHeader('languageCode');
						var roles=xhr.getResponseHeader('User-Roles');
				     	localstorage.set('logged',true);
				     	localstorage.set('uid',userId);	
				     	localstorage.set('token',token);
						localstorage.set('refresh',refresh);
						localstorage.set('roles', roles);
						localstorage.set('image', image);
						localstorage.set('languageCode', languageCode);
						//console.log(localstorage.get('image'));
						that.loginModel.fetch({
							type: 'GET',
							url: '/ws/user/'+localstorage.get('uid'),
							beforeSend: function (xhr) {
								xhr.setRequestHeader('Accept', 'application/json');
								xhr.setRequestHeader('Content-Type', 'application/json');
								xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
							},
							complete: function (response) {
								if(response.status == 200 )
								{
									//console.log('entre aqui');
									var result = response.responseJSON.result;
									//console.log(result);
									//localstorage.set('image', result.relative_path);
									localstorage.set('user_name', result.firstName);
									localstorage.set('steps', result.steps);
									localstorage.set('reload',false);
									//console.log(localstorage.get('image'));
									//console.log(localstorage.get('user_name'));
									//console.log(localstorage.get('steps'));
									//console.log(localstorage.get('refresh'));
									//that.refresh();
									//setInterval(function(){
									//	that.refresh();
									//},60000);
									localstorage.set('reload',false);
									if(localstorage.get('steps') == 1){
										$(this.overlay).hide();
										window.location.href = '/#ca';
									}
									else
									{
										window.location.href = '/#steptostep';
									}
								}
						}});
				     	that.set('logged',true);
						that.set('uid',userId);
						that.set('token',token);
						//if(steps)
						//	window.location.href = '/#steptostep';
						//else
						//	window.location.href = '/#ca';

				    }
				    else
				    {
						$('#errcontainerlogin').show();
						$(view.overlay).hide();
				    	$('#errcontainerlogin').html(Polyglo.t('login.loginError'));//
				    }			    			    	
				}
			});

		},

		logout : function() {
			$(this.overlay).show();
			var token = localstorage.get('token');
			var that = this;
			//console.log(token);
			//console.log('Token removed');
			localstorage.set('logged', false);
			localstorage.set('uid', '');
			localstorage.set('token', '');
			localstorage.set('user_name', '');
			localstorage.set('steps', '');
			localstorage.set('image', '');
			localstorage.set('reload',false);
			localstorage.set('blog','');
			localstorage.set('date1',null);
			localstorage.set('date2',null);
			if(localstorage.get('msg') == null || localstorage.get('msg') == undefined)
				localstorage.set('msg',Polyglo.t('general.logout'));
			that.set('logged', false);
			that.set('uid', '');
			that.set('token', '');
			//console.log('todo borrado');
			window.location.href = '/';

		}
	});
	return sessionModel;
});