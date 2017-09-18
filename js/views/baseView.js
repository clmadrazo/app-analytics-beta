define(['backbone','views/langView','models/baseModel'], function(Backbone,LangView,BaseModel) {
    var BaseView = Backbone.View.extend({
        langView : new LangView(),
        cleanModal: true,
        baseModel: new BaseModel(),
        idleTime: 0,
        getLang: true,
        close: function() {
            this.$el.empty().off();
        },
        afterRender:function(){
            //before afterRender
            //to solve zumbie problems with Modal
            if (this.cleanModal) {
                MOD = $(".modal-dialog").html();
                $(".modal-dialog").empty().append(MOD);
                $('.modal-footer').show();
            }
            $('#imageUser').attr('src', localstorage.get('image'));
        },
        beforeRender:function(){
            if (this.cleanModal) {
                MOD = $(".modal-dialog").html();
                $(".modal-dialog").empty().append(MOD);
                $('.modal-footer').show();
            }
            if (this.getLang) {
                this.langView.setLanguage();
            }
        },
        timerIncrement:function(){
            this.idleTime = this.idleTime + 1;
            //console.log(this.idleTime);
            if (this.idleTime > 3) { // 4 minutes
                var refresh = localstorage.get('refresh');
                this.baseModel.fetch({
                    type: 'POST',
                    removeBearer: true,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                    },
                    data: '[{ "refreshToken": "'+refresh+'"}]',
                    url: '/ws/authentication/refresh-token/',
                    complete: function (resp) {
                        //console.log(resp);
                        //console.log(resp.getResponseHeader('Bearer-Token'));
                        localstorage.set('token',resp.getResponseHeader('Bearer-Token'));
                        localstorage.set('refresh',resp.getResponseHeader('Refresh-Token'));
                        //console.log(localstorage.get('refresh'));
                        //console.log(resp);
                    }
                });
            }
        },
        initialize: function(options) {
            //only to build the method afterRender
            _.bindAll(this, 'beforeRender', 'render', 'afterRender','timerIncrement');
            obj = this;
            _this = this;
            that = this;
            //only to build the method afterRender
            this.render = _.wrap(this.render, function(render) {
                this.beforeRender();
                render(arguments);
                //////////////////////////
                $('#imageUser').attr('src', localstorage.get('image'));
                this.afterRender();
                ////////////////////////////////////////////////
                return this;
            });


            if(!$('body').find('.overlay').length)
            {
                $('body').prepend('<div class="overlay"><div class="loader"></div></div>');
            }
            this.overlay = $('body > .overlay');
            var left = (parseInt($(window).width()) / 2) - (parseInt($('.loader').width()) / 2);
            var top = (parseInt($(window).height()) / 2) - (parseInt($('.loader').height()) / 2);

            $(this.overlay).width('100%');
            $(this.overlay).height('100%');
            $(this.overlay).height($(this.overlay).height()+85);
            $(this.overlay).find('.loader').css('left', left + 'px');
            $(this.overlay).find('.loader').css('top', top + 'px');


          //  $(overlay).show();

        }

    });
    return BaseView;
});