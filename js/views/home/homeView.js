define(['backbone', 'doT', 'text!templatesFolder/home/home.html', 'models/home/homeModel', 'models/register/registerModel', 'models/login/loginModel', 'carouFredSel'],
	function(Backbone, doT, HomeTemplate, HomeModel, registerModel, LoginModel) {
	
	var HomeView = Backbone.View.extend({

		el : '#maincontent',
		template : doT.template(HomeTemplate),
		model : new HomeModel(),

		events: {
			"click .regbuttonev" : "_redirectRegisterPage",
			"click #register-buttonTop" : "_goregisterTop",
			"click #register-buttonBot" : "_goregisterBot",
			"click #changepasshomebutton" : "_changeHomePassword"
		},

		initialize : function() {
			this.regModel = new registerModel();
			this.loginModel = new LoginModel();
		},

		_addValidators : function() {
				
				$("#registerFormHomeOne").validate({ 
					rules: {
						name: "required",	
						lastname: "required",						
						password: {
							required: true,
							minlength: 6
						},
						passwordrepeat: {
							required: true,
							equalTo: "#password"
						},
						email: {
							required: true,
							email: true
						}
					},
					errorPlacement: function(error, element) {
						error.appendTo("#hometoperrors");
					},
					messages: {
						name: PORT[0].langData.validations.emptyName,
						lastname: PORT[0].langData.validations.emptyLastName,
						password: {
							required: PORT[0].langData.validations.emptyPassword,
							minlength: PORT[0].langData.validations.passMinLength
						},
						passwordrepeat: {
							required: PORT[0].langData.validations.repeatPassword,
							equalTo: PORT[0].langData.validations.equalPassword
						},
						email: PORT[0].langData.validations.emptyEmail
					}
				});

				$("#registerFormHomeTwo").validate({ 
					rules: {
						namebot: "required",	
						lastnamebot: "required",						
						passwordbot: {
							required: true,
							minlength: 6
						},
						passwordrepeatbot: {
							required: true,
							equalTo: "#passwordbot"
						},
						emailbot: {
							required: true,
							email: true
						}
					},
					messages: {
						namebot: PORT[0].langData.validations.emptyName,
						lastnamebot: PORT[0].langData.validations.emptyLastName,
						passwordbot: {
							required: PORT[0].langData.validations.emptyPassword,
							minlength: PORT[0].langData.validations.passMinLength
						},
						passwordrepeatbot: {
							required: PORT[0].langData.validations.repeatPassword,
							equalTo: PORT[0].langData.validations.equalPassword
						},
						emailbot: PORT[0].langData.validations.emptyEmail
					}
				});

				$("#resetpassform").validate({ 
					rules: {										
						newpasshome: {
							required: true,
							minlength: 6
						},
						newpasshomeval: {
							required: true,
							equalTo: "#newpasshome"
						}
					},
					messages: {
						newpasshome: {
							required: PORT[0].langData.validations.emptyPassword,
							minlength: PORT[0].langData.validations.passMinLength
						},
						newpasshomeval: {
							required: PORT[0].langData.validations.repeatPassword,
							equalTo: PORT[0].langData.validations.equalPassword
						}
					}
				});
		},

		_changeHomePassword : function() {
			var that = this;
			var tmpUrl = this.loginModel.url;
			var emailActual = $('#emailchangehome').val();
			var tokenActual = $('#tokenchangehome').val();

			this.loginModel.url = tmpUrl+'/validate-reset-code';

			if ($("#resetpassform").valid()) {

				this.loginModel.fetch({ 
					type: 'POST',
					data: '[{"email": "'+emailActual+'", "resetCode":"'+tokenActual+'"}]',
					complete: function (xhr) {
						if (xhr.status == '404') {
							$('#errcontainerhome').html(PORT[0].langData.validations.invalidToken);
							router.loader('hide');
						}
						else
						{
							that.loginModel.url = tmpUrl+'/change-password';
							that.loginModel.fetch({ 
								type: 'POST',
								data: '[{"email":"'+emailActual+'","resetCode":"'+tokenActual+'","password":"'+$('#newpasshome').val()+'"}]',
								complete: function () {						
									$('#resetpass-modal').modal('hide');
									router.loader('hide');							
							    }
							});
						}						
				    }
				});
			}

			this.loginModel.url = tmpUrl;
			return false;
		},

		_redirectRegisterPage : function() {
			router.navigate('register', {trigger:true});
		},

		_goregisterTop : function() {

			if ($("#registerFormHomeOne").valid()) {

				var name = $('#name').val(),
					lastname = $('#lastname').val(),
					email = $('#email').val(),
					password = $('#password').val();

				this.regModel.registerUser(name,lastname,email,password);
				return false;
			}
		},

		_goregisterBot : function() {

			if ($("#registerFormHomeTwo").valid()) {

				var name = $('#namebot').val(),
					lastname = $('#lastnamebot').val(),
					email = $('#emailbot').val(),
					password = $('#passwordbot').val();

				this.regModel.registerUser(name,lastname,email,password);
				return false;
			}
		},

	
		render : function(token,email) {
			
			var that = this;
			that.$el.html(that.template( {"lang" : PORT[0].langData} ));

			if (token != null && email != null) {
				$('#emailchangehome').val(email);
				$('#tokenchangehome').val(token);
				$('#resetpass-modal').modal('show');
			}

			var effects = {'#computer' : "slideUp", '#client-logo' : "fade-in", '#test-box' : "fade-in", '#pro-box' : "fade-in", '#reg-user' : "fade-in"}

			_.each(effects, function(value,index) {
				$(window).scroll(function() {
					$(index).each(function(){
						var imagePos = $(this).offset().top;
				
						var topOfWindow = $(window).scrollTop();
						if (imagePos < topOfWindow+600) {
							$(this).addClass(value);
						}
					});			
				});
			});

			$('#alertMe').click(function(e) {		
				e.preventDefault();				
				$('#successAlert').slideDown();				
			});
			
			$('a.pop').click(function(e) {
				e.preventDefault();
			});
			
			$('a.pop').popover();
			
			$('[rel="tooltip"]').tooltip();

		/*	$('#carousel').carouFredSel({
				width: 960,
				height: 115,
				
				items: 3,
				scroll: 1,
				
				auto: {
					duration: 1250,
					timeoutDuration: 2500
				},
				prev: '#prev',
				next: '#next',					
			});*/

			that._addValidators();
		}
	});
	return HomeView;
});