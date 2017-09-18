define(['views/baseView', 'doT', 'text!templatesFolder/checkblogs/checkblogs.html','models/sessionModel', 'bootstrap-select', 'moment','locale', 'datetimePicker'], function(BaseView, doT, LoginTemplate,SessionModel) {
    var RegistrationView = BaseView.extend({
        el : '#main-wrapper',
        template : doT.template(LoginTemplate),
        model : new SessionModel(),
        events: {
        },

        initialize:function(options){
            BaseView.prototype.initialize.call(this,options);
        },

        close: function(){
            this.$el.empty().off();
        },

        render : function(args){
            var that = this,
                email = args[2],
                lang = args[1];
            this.langView.setLanguage(lang);
            this.$el.html(this.template);
            console.log(email);
            $(that.overlay).show();
            this.model.fetch({
                removeBearer: true,
                type: 'PUT',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                },
                url: "ws/activateUser/",
                data: '[{ "email":"'+email+'"}]',
                complete: function (response) {
                    if(response.status == '200'){
                        $('.blogs').html(Polyglo.t('registration.sucess'));
                        $('.blogs').append('<br><br><a class="btn btn-warning" href="#login">'+Polyglo.t('login.login-a')+'</a>');
                        $(that.overlay).hide();
                    }
                }
            });
        }

    });

    return RegistrationView;
});



