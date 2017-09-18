define(['views/baseView', 'doT', 'text!templatesFolder/login/login.html','models/sessionModel', 'bootstrap-select', 'moment','locale', 'datetimePicker'], function(BaseView, doT, LoginTemplate,SessionModel) {
	var LoginView = BaseView.extend({
		el : '#main-wrapper',
		template : doT.template(LoginTemplate),
		model : new SessionModel(),
		events: {
            "click #normal-login": "_makeLogin"
			//,"click .continue-finish-register": "breakRegister",
			//"click #normal-register": "_makeRegister"
			//,"click .tabs li a": "tabs",
			//"click .reset-password": "normalReset",
			//"click #normal-reset": "_makeReset"
        },
		initialize:function(options){
			BaseView.prototype.initialize.call(this,options);
		},
		_makeLogin : function() {

        	var user = $('#email').val(),
        		password = $('#password').val();
			if(user.length < 1 || password.length < 1)
				$('#errcontainerlogin').html(Polyglo.t('general.emptyFields'));
			else
				this.model.normalLogin(user,password,null,this);
        	return false;

        },
        close: function(){
            this.$el.empty().off();
        },
			
		render : function(args){
			lang = args[1];
			this.langView.setLanguage(lang);
            this.$el.html(this.template);
			if(localstorage.get('msg') != null || localstorage.get('msg') != undefined)
				$('#errcontainerlogin').html(localstorage.get('msg'));
			localstorage.set('msg',null);
		}

	});

	return LoginView;
});



