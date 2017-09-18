define(['views/baseView', 'doT', 'models/sessionModel'], function(BaseView, doT, SessionModel) {
    var LogoutView = BaseView.extend({
        model : new SessionModel(),
        initialize:function(options){
            BaseView.prototype.initialize.call(this,options);
        },

        render : function(){
            this.model.logout();
        }

    });

    return LogoutView;
});



