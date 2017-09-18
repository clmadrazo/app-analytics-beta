var _this;
var userInvite;
var UserInviteView;

define(['backbone', 'views/baseView', 'doT', 'text!templatesFolder/users/userInvite.html'
    , 'text!templatesFolder/users/userInviteForm.html', 'text!templatesFolder/listUsers/listRoles.html'
    , 'collections/users/rolesCollection', 'text!templatesFolder/header/header.html'
    , 'models/user/userInviteModel']
    , function (Backbone, BaseView, doT, UserInviteTemplate
        , UserInviteFormTemplate , ListRolesTemplate
        , RolesCollection, HeaderTemplate
        , UserInviteModel) {
    UserInviteView = BaseView.extend({
        headerTemplate: doT.template(HeaderTemplate),
        template: doT.template(UserInviteTemplate),
        userInviteTemplate: doT.template(UserInviteFormTemplate),
        template1: doT.template(ListRolesTemplate),
        rolesCollection: new RolesCollection,
        model: new UserInviteModel,
        //roleModel : new RoleModel(),
        events: {
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
        _openModal: function () {
            $('#formUserTemplate').empty().append(this.userInviteTemplate);
            this.renderRoles();
        },
        renderRoles: function () {
            $('#role').empty().append(this.template1);
            var that = this;
            this.model.fetch({
                type: 'GET',
                url : '/ws/list/role',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                },
                complete: function (response) {
                    //console.log(response);
                    if (response.status == 200) {
                        var result = response.responseJSON.result;

                        for (var i=0; i<result.length; i++) {
                            $('select#sel1').append('<option value="'+result[i].id+'">'+result[i].title+'</option>');
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
        _sendInvite: function () {
            //call send user invitation service
            $('#errcontainerinvite').empty();
            this.model.sendInvite($('#txtEmail').val(), $('#sel1').val(),this);
        },
        close: function () {
            // overriding stuff here
            BaseView.prototype.close.call(this); // calling super.closeMethod()
            this.$('#client').empty();
            clearInterval(this.idleInterval);
        },
        initialize: function (options) {
            // overriding stuff here
            BaseView.prototype.initialize.call(this, options); // calling super.initializeMethod()
            this.rolesCollection.getRolesList();
                //$('#globalModal').modal();
            var self = this;
            //Increment the idle time counter every minute.
            this.idleInterval = setInterval(function() {
                self.timerIncrement();
            }, 60000); // 1 minute
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
                $('.nav li.li-nav-ex:nth-child(3)').addClass('active');
                this.$el.append(this.template);
                this._openModal();
                $("#buttonUserInvite").html(Polyglo.t('profile.invite'));
                $("#buttonUserInvite").click(function () {
                    _this._sendInvite();
                });
            }
        }
    });
    return UserInviteView;
});
