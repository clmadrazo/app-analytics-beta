define(['backbone', 'views/baseView', 'doT', 'text!templatesFolder/checkblogs/blogs.html', 'text!templatesFolder/header/header.html'
        , 'models/checkblogs/checkblogsModel']
    , function (Backbone, BaseView, doT,blogsTemplate
        , HeaderTemplate
        , CheckBlogsModel) {
        blogsView = BaseView.extend({
            headerTemplate: doT.template(HeaderTemplate),
            template: doT.template(blogsTemplate),
            model: new CheckBlogsModel,
            //roleModel : new RoleModel(),
            events: {
                "click #buttonUserInvite":"doMethod",
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
        doMethod: function(e) {
            e.preventDefault();
            var id = $('select.select-blogs').val(),
                name = $('input.new-name').val(),
                that = this;
            $(that.overlay).show();
            this.model.fetch({
                type: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                },
                url: '/ws/blog/update/' + id,
                data: '[{"id":"'+id+'","name_updated": "'+name+'"}]',
                complete: function (response) {
                    if(response.status = 200){
                        $('#errcontainerblog').html(Polyglo.t('general.updateBlogSuccess'));
                        $(that.overlay).hide();
                    }
                    if (response.status == 400) {
                        localstorage.set('msg',Polyglo.t('general.logoutE'));
                        window.location.href = '/#logout';
                    }
                }
            });
        },

        initialize:function(options){
            BaseView.prototype.initialize.call(this,options);
            var self = this;
            //Increment the idle time counter every minute.
            this.idleInterval = setInterval(function() {
                self.timerIncrement();
            }, 60000); // 1 minute
        },

        close: function(){

            this.$el.empty().off();
            clearInterval(this.idleInterval);
        },

        loadInfo: function() {
            $(that.overlay).show();
            this.model.fetch({

                type: 'GET',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                },
                url: this.model.url + localstorage.get('uid'),
                complete: function (response) {
                    //console.log(response.status);
                    var result = response.responseJSON.result;
                    if(response.status == 200) {
                        //console.log('actualizando steps');
                        for (var i=0; i<result.length; i++) {
                            $('select.select-blogs').append('<option value="'+result[i].id+'">'+result[i].name_updated+'</option>');
                        }
                        $(that.overlay).hide();
                    }
                    if (response.status == 400) {
                        localstorage.set('msg',Polyglo.t('general.logoutE'));
                        window.location.href = '/#logout';
                    }
                }
            });

            //<ul>
            //<li><a href="#upgrade" data-toggle="modal">Blog 1</a></li>
            //<li>Blog 2</li>
            //</ul>
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
                $('.nav li.li-nav-ex:nth-child(6)').addClass('active');
                this.$el.append(this.template);
                this.loadInfo();
            }
        }

    });

    return blogsView;
});



