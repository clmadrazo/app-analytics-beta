define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
    var subscriptionModel = BaseModel.extend({
        url : '/ws/blog/search/',

        parse : function(response) {
            return response;
        }
    });
    return subscriptionModel;
});