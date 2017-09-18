define(['backbone', 'views/baseView', 'doT', 'text!templatesFolder/users/subscription.html', 'text!templatesFolder/header/header.html'
        , 'models/subscription/subscriptionModel']
    , function (Backbone, BaseView, doT, susbcriptionTemplate
        , HeaderTemplate
        , susbcriptionModel) {
        SubscriptionView = BaseView.extend({
            headerTemplate: doT.template(HeaderTemplate),
            template: doT.template(susbcriptionTemplate),
            model: new susbcriptionModel,
            //roleModel : new RoleModel(),
            events: {
                "click a.profile":"cProfile",
                "click #buttonSubscrive": "subscrive",
                "mousemove":"zerar"
            },
            subscrive:function(){
                $(this.overlay).show();
                var that = this,
                    email = $('#txtEmail').val(),
                    name = $('#txtName').val(),
                    lastname = $('#txtLastName').val(),
                    regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/,
                    type = $('#tipo').val();
                $('#errcontainerinvite').empty();
                if (email == '') {
                    $('#errcontainerinvite').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.paypal'));
                    $(this.overlay).hide();
                }
                else if(!regex.test(email)){
                    $('#errcontainerinvite').html(Polyglo.t('profile.email_invalid'));
                    $(this.overlay).hide();
                }
                else if (name == '') {
                    $('#errcontainerinvite').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.name_paypal'));
                    $(this.overlay).hide();
                }
                else if (lastname == '') {
                    $('#errcontainerinvite').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.lastname_paypal'));
                    $(this.overlay).hide();
                }
                else {
                    this.model.fetch({
                        type: 'POST',
                        url : '/ws/paypal/getVerifiedStatus',
                        data: '[{ "email": "'+email+'","firstName": "'+name+'","lastName": "'+lastname+'"}]',
                        complete: function (response) {
                            if(response.status == 200){
                                $('#errcontainerinvite').html(Polyglo.t('profile.paypal_verified'));
                            }
                            else if(response.status == 404){
                                $('#errcontainerinvite').html(Polyglo.t('profile.paypal_notverified'));
                            }
                            else if (response.status == 400) {
                                localstorage.set('msg',Polyglo.t('general.logoutE'));
                                window.location.href = '/#logout';
                            }
                            $(that.overlay).hide();
                        }
                    });
                }

            },
            zerar:function(){
                this.idleTime = 0;
            },
            cProfile:function(){
                $(this).attr('aria-expanded',true);
                $('.user').addClass('open');
            },
            close: function () {
                // overriding stuff here
                BaseView.prototype.close.call(this);
                clearInterval(this.idleInterval);
            },
            initialize: function (options) {
                // overriding stuff here
                BaseView.prototype.initialize.call(this, options); // calling super.initializeMethod()
                //$('#globalModal').modal();
                var self = this;
                //Increment the idle time counter every minute.
                this.idleInterval = setInterval(function() {
                    self.timerIncrement();
                }, 60000); // 1 minute
            },
            typeSubscription: function () {
                var that = this;
                this.model.fetch({
                    type: 'GET',
                    url : '/ws/list/suscription_type',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    complete: function (response) {
                        if (response.status == 200) {
                            var result = response.responseJSON.result;

                            for (var i=0; i<result.length; i++) {
                                $('select#tipo').append('<option value="'+result[i].id+'">'+result[i].description+' R$ '+result[i].cost+'</option>');
                            }
                            $(that.overlay).hide();
                        }
                        if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                    }
                });
            },
            render: function () {
                var _this = this;
                if (localstorage.get('logged') === false)
                    window.location.href = '/';
                else {
                    $(this.overlay).show();
                    this.$el.html(this.headerTemplate({}));
                    //to eliminate zombie
                    $('#main-wrapper').empty().append(this.$el);
                    $('.nav li.active').removeClass('active');
                    $('.nav li.li-nav-ex:nth-child(4)').addClass('active');
                    this.$el.append(this.template);
                    this.typeSubscription();
                }
            }
        });
        return SubscriptionView;
    });
