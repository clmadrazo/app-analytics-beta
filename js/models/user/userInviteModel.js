define(['backbone', 'models/baseModel'], function (Backbone, BaseModel) {
    var userInviteModel = BaseModel.extend({
        url: '/ws/user/send-invitations',
        _validade: function(email) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (!re.test(email)) {
                $('#errcontainerinvite').html(Polyglo.t('profile.email_invalid'));
                $('#txtEmail').focus();
                return false;
            };
            
            return true;
        },
        sendInvite: function (email, role,view) {
            $(view.overlay).show();
            var _view = view;
            if (!this._validade(email)) {
                return false;
            }
            return this.fetch({
                type: 'POST',
                data: '[{ "invitations": "[{\\"email\\": \\"' + email + '\\", \\"role\\": \\"' + role + '\\"}]" }] ',
                complete: function (response) {
                    if (response.responseJSON.errors) {
                        $('#errcontainerinvite').html('Problemas! '+response.responseJSON.errors);
                    } else {
                        $('#errcontainerinvite').html(Polyglo.t('manageTeam.invitationSent'));
                    }
                    $(_view.overlay).hide();
                }
            });



        },
        parse: function (response) {
            return response;
        }
    });
    return userInviteModel;
});