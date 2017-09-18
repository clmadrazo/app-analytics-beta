define(['backbone'], function(Backbone) {
    var BaseCollection = Backbone.Collection.extend({
        note:null,
        loading:false,
        initialize: function(options) {
            //only to build the method afterRender
            _.bindAll(this, 'beforeFetch','afterFetch', 'fetch');
            that = this;
            //only to build the method afterRender
            this.fetch = _.wrap(this.fetch, function(fetch) {
                this.beforeFetch();
                b= this;
                a = fetch(arguments[1]);
                a.done(function(){
                    b.afterFetch();
                });
                return a;
            });

        },
        afterFetch:function(){
            if (this.loading)
                this.note.remove();
        },
        beforeFetch: function(){
            if (this.loading)
                this.note = new PNotify({
                    title: "Carregando",//Polyglo.t('general.loading'),
                    text:  this.title,
                    styling: 'bootstrap3',
                    type: 'info',
                    buttons: {closer: true, sticker: true},
                    icon: true
                });
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
    return BaseCollection;
});