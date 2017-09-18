define(['backbone', 'views/baseView', 'doT', 'text!templatesFolder/users/registration.html'
        , 'text!templatesFolder/header/header.html'
        , 'text!templatesFolder/users/userForm.html'
        , 'text!templatesFolder/image/image.html'
        , 'models/user/userModel']
    , function (Backbone, BaseView, doT, RegistrationTemplate
        , HeaderTemplate
        , UserFormTemplate
        , ImageTemplate
        , UserModel) {
        var ProfileView = BaseView.extend({
            headerTemplate: doT.template(HeaderTemplate),
            template: doT.template(RegistrationTemplate),
            userModel: new UserModel(),
            userFormTemplate : doT.template(UserFormTemplate),
            imageTemplate : doT.template(ImageTemplate),
            userId :'',
            registrationCode : '',
            userEmail: '',
            userName : '',
            userLastName :'',
            userPassword : '',
            userConfirmPassword : '',
            userLanguageId:'',
            events: {
                "click #register": "_register",
                "click a.profile":"cProfile",
                "mousemove":"zerar"
            },
            zerar:function(){
                this.idleTime = 0;
            },
            cProfile:function(){
                $(this).attr('aria-expanded',true);
                $('.user').addClass('open');
            },
            initialize : function(options) {
                // overriding stuff here
                BaseView.prototype.initialize.call(this,options); // calling super.initializeMethod()
                var self = this;
                //Increment the idle time counter every minute.
                this.idleInterval = setInterval(function() {
                    self.timerIncrement();
                }, 60000); // 1 minute
            },
            render : function( code){
                if (localstorage.get('logged') === false)
                    window.location.href = '/';
                else {
                    $(this.overlay).show();
                    that = this;
                    this.$el.html(this.headerTemplate({}));
                    //to eliminate zombie
                    $('#main-wrapper').empty().append(this.$el);
                    this.$el.append(this.template);
                    $('#InfoText').empty();
                    $('#CreateAccountTitle').empty();
                    $('#register-internal-title').html('<h2>' + Polyglo.t('profile.changeAccount') + '</h2>');
                    p = this.userModel.getUser();
                    p.done(function (a) {
                        $('#imageTemplate').empty().append(that.imageTemplate({'data': a.result}));
                        $('#formUserTemplate').empty().append(that.userFormTemplate({'data': a.result}));
                        $('#txtUserId').val(localstorage.get('uid'));
                        if (a.result.absolutePath != '')
                            $('#defaultImage').attr('src', a.result.absolutePath);
                        $(that.overlay).hide();
                    });
                    //$('#for-remove-facebook').html('');
                }
            },
            close: function(){
                BaseView.prototype.close.call(this); // calling super.closeMethod()
                clearInterval(this.idleInterval);
            },

            afterRender : function() {
                // overriding stuff here
                BaseView.prototype.afterRender.call(this); // calling super.afterRenderMethod()
                //remove facebook button
            },
            _register: function () {
                var that = this;
                $('#errcontainerprofile').empty();
                $(this.overlay).show();
                this.userId = $('#txtUserId').val();
                this.userLastName = $('#txtLastName').val();
                this.userName = $('#txtName').val();
                this.userPassword = $('#txtPass').val();
                this.userConfirmPassword = $('#txtConfirmPass').val();
                this.userLanguageId = $('#txtLanguageId option:selected').val();
                switch(this.userLanguageId){
                    case '1':
                        localstorage.set('languageCode','pt-br');
                        break;
                    case '2':
                        localstorage.set('languageCode','es');
                        break;
                    case '3':
                        localstorage.set('languageCode','en');
                        break;
                }
                this.userModel.fetch({
                    type: 'GET',
                    url: '/ws/userInvitation/'+localstorage.get('uid'),
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    complete: function (xhr) {
                        var statusCode = xhr.status;
                        if (statusCode == 200)
                        {
                            that.registrationCode = $('#registrationCode').val();
                        }
                        if (statusCode == 400) {
                            window.location.href = '/#logout';
                        }
                        that.userModel.set({ userId: that.userId});
                        that.userModel.set({ registrationCode: that.registrationCode });
                        that.userModel.set({ name: that.userName });
                        that.userModel.set({ lastName: that.userLastName });
                        that.userModel.set({ email: that.userEmail });
                        that.userModel.set({ password: that.userPassword });
                        that.userModel.set({ confirmPassword: that.userConfirmPassword });
                        that.userModel.set({ language_id: that.userLanguageId });

                        //call send user invitation service
                        that.userModel.save(that);
                        $(that.overlay).hide();
                    }
                });

            },
        });

        return ProfileView;
    });