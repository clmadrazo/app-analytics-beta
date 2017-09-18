define(['backbone', 'views/baseView', 'doT', 'text!templatesFolder/users/billing.html', 'text!templatesFolder/header/header.html'
        , 'models/subscription/subscriptionModel']
    , function (Backbone, BaseView, doT, billingTemplate
        , HeaderTemplate
        , susbcriptionModel) {
        BillingView = BaseView.extend({
            headerTemplate: doT.template(HeaderTemplate),
            template: doT.template(billingTemplate),
            model: new susbcriptionModel,
            //roleModel : new RoleModel(),
            events: {
                "click a.profile":"cProfile",
                "mousemove":"zerar"
            },
            zerar:function(){
                this.idleTime = 0;
            },
            cProfile:function(){
                $(this).attr('aria-expanded',true);
                $('.user').addClass('open');
            },
            close: function () {
                // overriding stuff here
                BaseView.prototype.close.call(this);
                clearInterval(this.idleInterval);
            },
            initialize: function (options) {
                // overriding stuff here
                BaseView.prototype.initialize.call(this, options); // calling super.initializeMethod()
                //$('#globalModal').modal();
                var self = this;
                //Increment the idle time counter every minute.
                this.idleInterval = setInterval(function() {
                    self.timerIncrement();
                }, 60000); // 1 minute
            },
            render: function () {
                var _this = this;
                if (localstorage.get('logged') === false)
                    window.location.href = '/';
                else {
                    //$(this.overlay).show();
                    this.$el.html(this.headerTemplate({}));
                    //to eliminate zombie
                    $('#main-wrapper').empty().append(this.$el);
                    $('.nav li.active').removeClass('active');
                    $('.nav li.li-nav-ex:nth-child(5)').addClass('active');
                    this.$el.append(this.template);
                }
            }
        });
        return BillingView;
    });
