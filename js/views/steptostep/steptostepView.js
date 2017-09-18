define(['views/baseView', 'doT', 'text!templatesFolder/header/header-step.html','text!templatesFolder/steptostep/steptostep.html','models/steptostep/steptostepModel','steps'], function(BaseView, doT, HeaderTemplate,StepToStepTemplate,StepToStepModel) {
    var StepToStepView = BaseView.extend({
        el : '#main-wrapper',
        header : doT.template(HeaderTemplate),
        template : doT.template(StepToStepTemplate),
        model : new StepToStepModel(),
        initialize:function(options){
            BaseView.prototype.initialize.call(this,options);
        },

        buildForm:function() {
            var form = $("#step-form");
            form.children("div").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    return true;
                },
                onFinishing: function (event, currentIndex) {
                    window.location = "#checkblogs";
                    $('body > .overlay').hide();
                }
            });
        },

        render : function(){
            if (localstorage.get('logged') === false)
                window.location.href = '/';
            else{
                if(localstorage.get('steps') == 1){
                    window.location.href = '/#ca';
                }
                else
                {
                    this.$el.html(this.template);
                    $('.header-step').html(this.header);
                    this.buildForm();
                    $(that.overlay).hide();
                }
            }
        }

    });

    return StepToStepView;
});