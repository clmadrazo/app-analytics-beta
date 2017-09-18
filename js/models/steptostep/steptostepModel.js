define(['backbone', 'models/baseModel'], function(Backbone, BaseModel) {
    var steptostepModel = BaseModel.extend({

        parse : function(response) {
            return response;
        }
    });
    return steptostepModel;
});