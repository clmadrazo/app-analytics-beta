define(['views/baseView', 'doT', 'text!templatesFolder/login/login.html','models/sessionModel', 'bootstrap-select', 'moment','locale', 'datetimePicker'], function(BaseView, doT, LoginTemplate,SessionModel) {
    var ResetView = BaseView.extend({
        el : '#main-wrapper',
        template : doT.template(LoginTemplate),
        model : new SessionModel(),
        events: {
            //"click #normal-login": "_makeLogin",
            //"click .continue-finish-register": "breakRegister",
            //"click #normal-register": "_makeRegister",
            //"click .tabs li a": "tabs",
            "click .reset-password": "normalReset",
            "click #normal-reset": "_makeReset"
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
        normalReset:function(){
            var email = $('#email-reset').val(),
                code = $('.form-code-reset-2').val(),
                password = $('.form-password-reset').val(),
                repassword = $('.form-repassword-reset').val(),
                that = this;
            $(that.overlay).show();
            $('#errcontainerreset').hide();
            if(password.length < 1 || repassword.length < 1){
                $('#errcontainerreset').html(Polyglo.t('general.emptyFields'));
                $('#errcontainerreset').show();
            }
            else if(password != repassword){
                $('#errcontainerreset').html(Polyglo.t('profile.passwordNotMatch'));
                $(this.overlay).hide();
                $('#errcontainerreset').show();
            }
            else if(password.length < 6){
                $('#errcontainerregister').html(Polyglo.t('profile.neeed6caracteres'));
                $('#errcontainerreset').show();
            }
            else {
                this.model.fetch({
                    removeBearer: true,
                    type: 'POST',
                    url: '/ws/authentication/validate-reset-password',
                    data: '[{ "email": "'+email+'","resetCode": "'+code+'"}]',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                    },
                    complete: function (xhr) {
                        if (xhr.status == 200)
                        {
                            that.model.fetch({
                                removeBearer: true,
                                type: 'POST',
                                url: '/ws/authentication/set-password',
                                data: '[{ "email":"'+email+'","password":"'+password+'"}]',
                                beforeSend: function (xhr) {
                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                },
                                complete: function (xhr) {
                                    var statusCode = xhr.status;
                                    if (statusCode == 200)
                                    {
                                        $('#errcontainerreset').html(Polyglo.t('reset_password.upgrade'));
                                        $(that.overlay).hide();
                                        $('#errcontainerreset').show();
                                    }
                                    else {
                                        $('#errcontainerreset').html(Polyglo.t('general.error'));
                                        $(that.overlay).hide();
                                        $('#errcontainerreset').show();
                                    }
                                }
                            });
                        }
                        else{
                            $('#errcontainerreset-form').html(Polyglo.t('reset_password.codeInvalid'));
                            $(that.overlay).hide();
                            $('#errcontainerreset').show();
                        }

                    }
                });
            }

        },
        //breakRegister:function(){
        //    $('#modal-register').toggleClass('pauseInterval');
        //    location.reload();
        //},
        _makeReset:function(){
            var email = $('#email-reset').val(),
                that = this;
            $('#errcontainerreset').hide();
            if(email.length < 1){
                $('#errcontainerreset').html(Polyglo.t('general.emptyFields'));
                $('#errcontainerreset').show();
            }
            else {
                $(this.overlay).show();
                this.model.fetch({
                    removeBearer: true,
                    type: 'POST',
                    url: '/ws/authentication/forgot-password',
                    data: '[{ "email": "'+email+'"}]',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                    },
                    complete: function (xhr) {
                        if (xhr.status == 200)
                        {
                            $('.message-code').show();
                            $(".message-code").html(Polyglo.t('reset_password.message'));
                            $('#email-reset').prop('disabled', true);
                            $('.form-code-reset').val(Polyglo.t('reset_password.code')+": "+xhr.responseJSON.result.resetCode);
                            $('.form-code-reset-2').val(Polyglo.t(xhr.responseJSON.result.resetCode));
                            $('.form-code-reset').show();
                            $('.form-password-reset').show();
                            $('.form-repassword-reset').show();
                            $('.reset-password').show();
                            $('#normal-reset').hide();
                            $('#errcontainerreset').hide();
                        }
                        else if (xhr.status == 404){
                            $('#errcontainerreset').html(Polyglo.t('reset_password.emailError'));
                            $('#errcontainerreset').show();
                        }
                        else {
                            $('#errcontainerreset').html(Polyglo.t('general.error'));
                            $('#errcontainerreset').show();
                        }
                        $(that.overlay).hide();

                    }
                });
            }
        },
        //tabs : function(e) {
        //    //e.preventDefault();
        //    $('#dateOfBirth-register').datetimepicker({
        //        format: 'YYYY-MM-DD',
        //        maxDate: new Date()
        //    });
        //    $('#dateOfBirth-register-a').datetimepicker({
        //        format: 'YYYY-MM-DD',
        //        maxDate: new Date()
        //    });
        //    var $this = $(e.currentTarget);
        //    var $tab = $( $this ),
        //        href = $tab.attr( 'href' );
        //    $( '.tabs .active' ).removeClass( 'active' );
        //    $tab.addClass( 'active' );
        //
        //    $( '.show' )
        //        .removeClass( 'show' )
        //        .addClass( 'hide' )
        //        .hide();
        //
        //    $(href)
        //        .removeClass( 'hide' )
        //        .addClass( 'show' )
        //        .hide()
        //        .fadeIn( 550 );
        //    if(href == '#reset'){
        //        $('#errcontainerreset').hide();
        //        $('#email-reset').prop('disabled', false);
        //        $('.form-code-reset').hide();
        //        $('.message-code').hide();
        //        $('.form-password-reset').hide();
        //        $('.form-repassword-reset').hide();
        //        $('.reset-password').hide();
        //        $('#normal-reset').show();
        //    }
        //},
        //_makeRegister : function() {
        //    $('#modal-register').removeClass('pauseInterval');
        //    var name = $('#name').val(),
        //        lastname = $('#lastname').val(),
        //        username = $('#username').val(),
        //        dateOfBirth = $('#dateOfBirth-register').val(),
        //        email = $('#email-register').val(),
        //        password = $('#password-register').val(),
        //        repassword = $('#re-password-register').val();
        //    if(localstorage.get('languageCode') == 'en')
        //        languageId = 3;
        //    else if(localstorage.get('languageCode') == 'es')
        //        languageId = 2;
        //    else
        //        languageId = 1;
        //    if(name.length < 1 || lastname.length < 1 || username.length < 1 || dateOfBirth.length < 1 || email.length < 1 || password.length < 1)
        //        $('#errcontainerregister').html(Polyglo.t('general.emptyFields'));
        //    else if(password != repassword){
        //        $('#errcontainerregister').html(Polyglo.t('profile.passwordNotMatch'));
        //    }
        //    else if(password.length < 6){
        //        $('#errcontainerregister').html(Polyglo.t('profile.neeed6caracteres'));
        //    }
        //    else {
        //        this.normalRegister(name,lastname,username,dateOfBirth,email,password,languageId);
        //
        //    }
        //    return false;
        //},
        //normalRegister: function(name,lastname,username,dateOfBirth,email,password,languageId){
        //    var that = this;
        //    $('#errcontainerregister').hide();
        //    $(this.overlay).show();
        //    this.model.fetch({
        //        removeBearer: true,
        //        type: 'POST',
        //        url: '/ws/registration',
        //        data: '[{ "name": "'+name+'", "lastName":"'+lastname+'","username":"'+username+'","dateOfBirth":"'+dateOfBirth+'","email":"'+email+'","password":"'+password+'","language_id":"'+languageId+'"}]',
        //        beforeSend: function (xhr) {
        //            xhr.setRequestHeader('Content-Type', 'application/json');
        //        },
        //        complete: function (xhr) {
        //            var statusCode = xhr.status;
        //            if (statusCode == 200)
        //            {
        //                $('#modal-register').addClass('in');
        //                $('#modal-register').show();
        //                $('body').append('<div class="modal-backdrop fade in"></div>');
        //                $('.reset-password').hide();
        //                $('.continue-finish-reset').show();
        //                var boolInterval = false;
        //                setInterval(function() {
        //                    if(!boolInterval){
        //                        if(!$('#modal-register').hasClass('pauseInterval')) {
        //                            that.model.normalLogin(email,password,false,that);
        //                            $('.modal-backdrop').remove();
        //                            $('#modal-register').toggleClass('pauseInterval');
        //                        }
        //                        boolInterval =true;
        //                    }
        //                }, 10000); // 10 segundos
        //                $(that.overlay).hide();
        //            }
        //            else
        //            {
        //                //console.log(xhr.responseJSON.errors.toString());
        //                $('#errcontainerregister').html(xhr.responseJSON.errors.toString());
        //                $(that.overlay).hide();
        //                $('#errcontainerregister').show();
        //            }
        //        }
        //    });
        //},
        //_makeLogin : function() {
        //
        //    var user = $('#email').val(),
        //        password = $('#password').val();
        //    if(user.length < 1 || password.length < 1)
        //        $('#errcontainerlogin').html(Polyglo.t('general.emptyFields'));
        //    else
        //        this.model.normalLogin(user,password,null,this);
        //    return false;
        //
        //},
        close: function(){
            this.$el.empty().off();
            clearInterval(this.idleInterval);
        },

        render : function(args){
            lang = args[1];
            this.langView.setLanguage(lang);
            this.$el.html(this.template);
            $( '.tabs .active' ).removeClass( 'active' );
            $( '.tab-reset' ).addClass( 'active' );
            $( '.show' )
                .removeClass( 'show' )
                .addClass( 'hide' )
                .hide();
            $('#reset')
                .removeClass( 'hide' );
            $('#errcontainerreset').hide();
            $('#email-reset').prop('disabled', false);
            $('.form-code-reset').hide();
            $('.message-code').hide();
            $('.form-password-reset').hide();
            $('.form-repassword-reset').hide();
            $('.reset-password').hide();
            $('#normal-reset').show();
        }

    });

    return ResetView;
});



