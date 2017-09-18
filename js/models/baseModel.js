define(['backbone'], function(Backbone) {
	var BaseModel = Backbone.Model.extend({
        note:null,
        initialize: function(options) {
            //only to build the method afterRender
            _.bindAll(this, 'beforeFetch','afterFetch', 'fetch');
            _this = this;
            //only to build the method afterRender
            this.fetch = _.wrap(this.fetch, function(fetch) {
                this.beforeFetch();
                b= this;
                a = fetch(arguments[1]);
                a.done(function(){
                    b.afterFetch();
                });
                return a;
                //_this.afterFetch();
            });
        },
        afterFetch:function(){
         /*   if (this.loading)
                this.note.remove();*/
        },
        beforeFetch: function(){
         //   vtext = (Polyglo)?Polyglo.t('general.sendInformations'):"Enviando informações";
         //   vTitle =  (Polyglo)?Polyglo.t('general.loading'):"Carregando";
           /* if (this.loading)
                this.note = new PNotify({
                    title: vTitle,
                    text: (this.title==undefined)?vtext:this.title,
                    styling: 'bootstrap3',
                    type: 'info',
                    buttons: {closer: true, sticker: true},
                    icon: true
                });*/
        },
        fetch: function(arg) {
                options = arg;
                options.beforeSend = function(xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    if (options.removeBearer !== true) {
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token')); // This needs to be updated with the correct token
                    }
                };

                //Call Backbone's fetch
                return Backbone.Collection.prototype.fetch.call(this, options);
            }
	});
	return BaseModel;
});