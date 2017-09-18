define(['backbone', 'models/baseModel'], function (Backbone, BaseModel) {
    var userModel = BaseModel.extend({
        url: '/ws/registration',
        defaults: {
            'registrationCode': '',
            'name': '',
            'userId': '',
            'lastName': '',
            'password': '',
            'confirmPassword': '',
            'language_id':'',
            'image':'',
        },
        getUser : function(){
            return this.fetch({
                type: 'GET',
                url:'/ws/user/'+localstorage.get('uid'),
                complete: function (response) {
                    if (response.responseJSON.errors) {
                        alert(response.responseJSON.errors[0]);
                    }
                }
            });

        },
        _Post : function(Data,view){
            that = this;
            debug = '';
            var p = that.fetch({
                removeBearer: true,
                url:'/ws/registration'+debug,
                type: 'POST',
                data: Data ,
                complete: function (response) {
                    if (response.status == "404") {
                        alert(response.responseJSON.errors[0]);
                    } else if (response.status == "200"){
                        fn = function (msg ){
                            $('#errcontainerprofile').html(Polyglo.t('profile.allRight')+': '+msg);
                        };

                        if (that.attributes.registrationCode)
                            fn(Polyglo.t('profile.userCreated'))
                        else
                            fn(Polyglo.t('profile.userChanged'));
                    }
                    $(view.overlay).hide();
                }
            });
            p.done(function(a){
                $('#imageUser').attr('src', a.absolutePath);
                localstorage.set('image', a.absolutePath);
            });
        },
        _LoadClientImage :function(file,data,view){
            that = this;
            if (file) {
                var reader = new FileReader();
                reader.onload = function(readerEvt) {
                    var binaryString = readerEvt.target.result,
                        imageBase64 = btoa(binaryString);
                    imageBase64 = 'data:'+file.type+';base64,'+imageBase64;
                    that._Post(data+',"image":"'+imageBase64+'"}]',view);
                };
                reader.readAsBinaryString(file);
            }
            else{
                that._Post(data+',"image":""}]',view);
            }
        },
        validate: function (attrs) {
            console.log(attrs);
            if (attrs.name == '') {
                $('#errcontainerprofile').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.enterName'));
                return false;
            }
            if (attrs.password.length<6) {
                $('#errcontainerprofile').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.neeed6caracteres'));
                return false;
            }
            if (attrs.password == '') {
                $('#errcontainerprofile').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.enterPassword'));
                return false;
            }
            if (attrs.confirmPassword == '') {
                $('#errcontainerprofile').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.confirmPassword'));
                return false;
            }
            if (attrs.confirmPassword != attrs.password) {
                $('#errcontainerprofile').html(Polyglo.t('profile.fillProperly')+': '+Polyglo.t('profile.passwordNotMatch'));
                return false;
            }
            return true;
        },
        save: function (view) {
            $(view.overlay).show();
            var res = this.validate(this.attributes);
            if (!res) {
                return false;
            } else {
                that = this;
                var file = $('#image').get(0).files[0];

                var data = '[{ "registrationCode": "' + this.attributes.registrationCode + '"' +
                    ', "name": "' + this.attributes.name
                    + '", "lastName":"'+ this.attributes.lastName+
                    '", "password": "' + this.attributes.password+'"'+
                    ', "language_id": "' + this.attributes.language_id+'"';
                if (this.attributes.userId !='')
                    data += ', "userId":"'+ this.attributes.userId+'"';
                this._LoadClientImage(file,data,view);
            }
        },
        parse: function (response) {
            return response.result;
        }
    });
    return userModel;
});