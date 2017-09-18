define(['models/baseModel'], function(BaseModel) {
    var clientModel = BaseModel.extend({
        title: (Polyglo)?Polyglo.t('genereal.savingChanges'):"Salvando alterações",
        loading: true,
        image:'',
        _isValid : function(client){
            return !(client=='');
        },
        _Validade: function(obj){
            if (!this._isValid(obj.name)){
                this.note = new PNotify({
                    title: Polyglo.t("general.error"),
                    text: Polyglo.t("addClient.EnterClientName"),
                    styling: 'bootstrap3',
                    type: 'error',
                    buttons: {closer: true, sticker: true},
                    icon: true
                });
                $('#clientName').focus();
                return false;
            }
            return true;
        },
        _save :function(Url,Data){
        },
        _deleteClient:function(doAfter){
            var Data = new Object();
            Data.clientId = $('input[name="formClientId"]:hidden').val();
            var Url ='ws/client/delete';
            var that = this;
            var JSONData = '[' + JSON.stringify(Data) +']';

            return that.fetch({
                    url : Url,
                    type: 'POST',
                    data:JSONData,
                    error: function () {
                        try {
                            alert(arguments[1].responseJSON.result.errors);
                        }
                        catch(e){
                            alert(arguments[1].responseJSON.error);
                        }
                    },
                    success: function (xhr) {
                        //$('.globalModal').modal('hide');
                        if (xhr.status == 401) {
                            router.navigate('login', {trigger: true});
                            return;
                        }
                        // that.note.remove();
                        that.note = new PNotify({
                            title: Polyglo.t('profile.allRight'),
                            text: Polyglo.t('addClient.DeleteClient') ,
                            styling: 'bootstrap3',
                            type: 'success',
                            buttons: {closer: true, sticker: true},
                            icon: true
                        });
                        $('#myModalClose').click();
                        doAfter();
                    }
                });

        },
        _saveClient : function(doAfter) {
            var Data = new Object();
            
            var ClientId = $('input[name="formClientId"]:hidden').val();
            var clientName = $('#clientName').val();
            var facebookAccountId = $('#fbk-account-id').val();
            var twitterToken = $('#twitter-token').val();
            var twitterSecret = $('#twitter-secret').val();
            var twitterName = $('#twitter-name').val();
            this.image = '';
            var file = $('#fileClient').get(0).files[0];
            if (ClientId !=''){
                var Url = 'ws/client/edit';
                msg = Polyglo.t('addClient.ChangedClient')
                Data.clientId = ClientId;
            }
            else{
                msg = Polyglo.t('addClient.CreatedClient')
                var Url = 'ws/client/add';
            }
            Data.name = clientName;

            that = this;
            fn = function () {
                var obj = {name:clientName};
                //if is not valid
                if (!that._Validade(obj)) {
                    return false;
                }
                var that2 = that;
                var JSONData = '[' + JSON.stringify(Data) +']';

                f =  that2.fetch({
                    url: Url,
                    type: 'POST',
                    data: JSONData,
                    error: function () {
                        try {
                            alert(arguments[1].responseJSON.result.errors);
                        }
                        catch(e){
                            alert(arguments[1].responseJSON.error);
                        }
                    },
                    success: function (xhr) {
                        //$('.globalModal').modal('hide');
                        if (xhr.status == 401) {
                            router.navigate('login', {trigger: true});
                            return;
                        }
                       // that.note.remove();
                        that.note = new PNotify({
                            title: Polyglo.t('profile.allRight'),
                            text: msg,
                            styling: 'bootstrap3',
                            type: 'success',
                            buttons: {closer: true, sticker: true},
                            icon: true
                        });
                        $('#myModalClose').click();
                    }
                });
                f.done(function(){
                    doAfter();
                });
            };
            if (facebookAccountId != 'undefined' && facebookAccountId !== '') {
                Data.facebook_account_id = facebookAccountId;
                var selector = '#fbk-account-token-' + facebookAccountId;
                Data.facebook_token = $(selector).val();
            }
            if (twitterToken != 'undefined' && twitterToken !== '') {
                Data.twitter_token = twitterToken;
                Data.twitter_secret = twitterSecret;
                Data.twitter_name = twitterName;
            }
            if (file) {
                var reader = new FileReader();
                reader.onload = function(readerEvt) {
                    //if ImageFile was loaded
                    var binaryString = readerEvt.target.result;
                    image = btoa(binaryString);
                    Data.image = image;
                    return fn();
                };
                reader.readAsBinaryString(file);
            }
            else
                return fn();
        },
        parse : function(response) {
            return response.result;
        }
    });
    return clientModel;
});