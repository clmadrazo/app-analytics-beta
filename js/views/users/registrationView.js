define(['views/baseView', 'doT', 'text!templatesFolder/login/login.html','models/sessionModel', 'bootstrap-select', 'moment','locale', 'datetimePicker'], function(BaseView, doT, LoginTemplate,SessionModel) {
    var RegistrationView = BaseView.extend({
        el : '#main-wrapper',
        template : doT.template(LoginTemplate),
        model : new SessionModel(),
        events: {
            //"click #normal-login": "_makeLogin",
            "click .continue-finish-register": "breakRegister",
            "click #normal-register": "_makeRegister"
            ,"mousemove":"zerar",
        },
        zerar:function(){
            this.idleTime = 0;
        },
        initialize:function(options){
            BaseView.prototype.initialize.call(this,options);
            var self = this;
            //Increment the idle time counter every minute.
            this.idleInterval = setInterval(function() {
                self.timerIncrement();
            }, 60000); // 1 minute
        },
        breakRegister:function(){
            $('#modal-register').toggleClass('pauseInterval');
            location.reload();
        },
        _makeRegister : function() {
            $('#modal-register').removeClass('pauseInterval');
            var name = $('#name').val(),
                lastname = $('#lastname').val(),
                username = $('#username').val(),
                registrationCode = $('#registrationCode').val(),
                dateOfBirth = $('#dateOfBirth-register').val(),
                email = $('#email-register').val(),
                password = $('#password-register').val(),
                repassword = $('#re-password-register').val();
            if(localstorage.get('languageCode') == 'en')
                languageId = 3;
            else if(localstorage.get('languageCode') == 'es')
                languageId = 2;
            else
                languageId = 1;
            if(name.length < 1 || lastname.length < 1 || username.length < 1 || dateOfBirth.length < 1 || email.length < 1 || password.length < 1)
                $('#errcontainerregister').html(Polyglo.t('general.emptyFields'));
            else if(password != repassword){
                $('#errcontainerregister').html(Polyglo.t('profile.passwordNotMatch'));
            }
            else if(password.length < 6){
                $('#errcontainerregister').html(Polyglo.t('profile.neeed6caracteres'));
            }
            else {
                this.normalRegister(registrationCode,name,lastname,username,dateOfBirth,email,password,languageId);

            }
            return false;
        },
        normalRegister: function(registrationCode,name,lastname,username,dateOfBirth,email,password,languageId){
            var that = this;
            $('#errcontainerregister').hide();
            $(this.overlay).show();
            this.model.fetch({
                removeBearer: true,
                type: 'POST',
                url: '/ws/registration',
                data: '[{ "registrationCode": "'+registrationCode+'","name": "'+name+'", "lastName":"'+lastname+'","username":"'+username+'","dateOfBirth":"'+dateOfBirth+'","email":"'+email+'","password":"'+password+'","language_id":"'+languageId+'"}]',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Content-Type', 'application/json');
                },
                complete: function (xhr) {
                    var statusCode = xhr.status;
                    if (statusCode == 200)
                    {
                        $('#modal-register').addClass('in');
                        $('#modal-register').show();
                        $('body').append('<div class="modal-backdrop fade in"></div>');
                        $('.reset-password').hide();
                        $('.continue-finish-reset').show()
                        $(that.overlay).hide();
                    }
                    else if (statusCode == 404)
                    {
                        //console.log(xhr.responseJSON.errors.toString());
                        if(xhr.responseJSON.errors != undefined)
                            $('#errcontainerregister').html(xhr.responseJSON.errors.toString());
                        else
                            $('#errcontainerregister').html(Polyglo.t('general.emailError'));
                        $(that.overlay).hide();
                        $('#errcontainerregister').show();
                    }
                }
            });
        },
        close: function(){
            this.$el.empty().off();
            clearInterval(this.idleInterval);
        },

        render : function(args){
            code = args[2];
            lang = args[1];
            this.langView.setLanguage(lang);
            this.$el.html(this.template);
            if(code != null)
                $('#registrationCode').val(code);
            $('#dateOfBirth-register').datetimepicker({
                        format: 'YYYY-MM-DD',
                        maxDate: new Date()
                    });
                    $('#dateOfBirth-register-a').datetimepicker({
                        format: 'YYYY-MM-DD',
                        maxDate: new Date()
                    });
            $( '.tabs .active' ).removeClass( 'active' );
            $( '.tab-register' ).addClass( 'active' );
            $( '.show' )
                .removeClass( 'show' )
                .addClass( 'hide' )
                .hide();
            $('#register')
                .removeClass( 'hide' )
                .addClass( 'show' )
                .show();
        }

    });

    return RegistrationView;
});



