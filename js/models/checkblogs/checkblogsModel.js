define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
    var checkblogsModel = BaseModel.extend({
        url : '/ws/blog/search/',

        parse : function(response) {
            return response;
        }
    });
    return checkblogsModel;
});