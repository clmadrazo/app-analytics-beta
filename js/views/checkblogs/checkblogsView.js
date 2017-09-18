define(['views/baseView', 'doT', 'text!templatesFolder/header/header-step.html','text!templatesFolder/checkblogs/checkblogs.html','models/checkblogs/checkblogsModel'], function(BaseView, doT, HeaderTemplate, CheckblogsTemplate,CheckBlogsModel) {
    var checkblogsView = BaseView.extend({
        el : '#main-wrapper',
        header : doT.template(HeaderTemplate),
        template : doT.template(CheckblogsTemplate),
        checkBlogsModel : new CheckBlogsModel(),
        events: {
            "submit form.blogs-update": "doMethod",
            "mousemove":"zerar",
        },
        zerar:function(){
            this.idleTime = 0;
        },
        doMethod: function(e) {
            e.preventDefault();
            $(that.overlay).width('100%');
            $(that.overlay).height('100%');
            $(that.overlay).show();
            var id = $('select.select-blogs').val();
            var name = $('input.new-name').val();
            this.checkBlogsModel.fetch({
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
                        location.reload()
                    }
                    if (response.status == 401) {
                        var refresh = localstorage.get('refresh');
                        this.checkBlogsModel.fetch({
                            type: 'POST',
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader('Content-Type', 'application/json');
                            },
                            data: '[{ "refreshToken": "'+refresh+'"}]',
                            url: '/ws/authentication/refresh-token/ ',
                            complete: function (response) {
                                localstorage.set('token',response.getResponseHeader('Bearer-Token'));
                                localstorage.set('refresh',response.getResponseHeader('Refresh-Token'));
                                this.checkBlogsModel.fetch({
                                    type: 'POST',
                                    beforeSend: function (xhr) {
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.setRequestHeader('Content-Type', 'application/json');
                                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                                    },
                                    url: '/ws/blog/update/' + id,
                                    data: '[{"id":"' + id + '","name_updated": "' + name + '"}]',
                                    complete: function (response) {
                                        location.reload()
                                    }
                                });
                            }
                        });
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

        refresh : function() {
        	var refresh = localstorage.get('refresh');
        	this.checkBlogsModel.fetch({
        		type: 'POST',
        		removeBearer: true,
        		beforeSend: function (xhr) {
        			xhr.setRequestHeader('Content-Type', 'application/json');
        		},
        		data: '[{ "refreshToken": "'+refresh+'"}]',
        		url: '/ws/authentication/refresh-token/',
        		complete: function (resp) {
        			localstorage.set('token',resp.getResponseHeader('Bearer-Token'));
        			localstorage.set('refresh',resp.getResponseHeader('Refresh-Token'));
        			//console.log(localstorage.get('refresh'));
        			//console.log(resp);
        		}
        	});
        },

        close: function(){

            this.$el.empty().off();
            clearInterval(this.idleInterval);
        },
        //doMethod: function(e) {
        //    console.log('form fired');
        //},

        loadInfo: function() {
            $(that.overlay).width('100%');
            $(that.overlay).height('100%');
            $(that.overlay).show();
            this.checkBlogsModel.fetch({

                type: 'GET',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                },
                url: this.checkBlogsModel.url + localstorage.get('uid'),
                complete: function (response) {
                    //console.log(response.status);
                    if (response.status == 401) {
                        var refresh = localstorage.get('refresh');
                        this.checkBlogsModel.fetch({
                            type: 'POST',
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader('Content-Type', 'application/json');
                            },
                            data: '[{ "refreshToken": "'+refresh+'"}]',
                            url: '/ws/authentication/refresh-token/',
                            complete: function (response) {
                                localstorage.set('token',response.getResponseHeader('Bearer-Token'));
                                localstorage.set('refresh',response.getResponseHeader('Refresh-Token'));
                                location.reload();
                            }
                        });
                    }
                    if (response.status == 200) {
                        var result = response.responseJSON.result;
                        that.checkBlogsModel.fetch({
                            type: 'PUT',
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader('Content-Type', 'application/json');
                                xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                            },
                            data: '[{ "userId": "'+localstorage.get('uid')+'", "steps": "1"}]',
                            url: '/ws/update/user/',
                            complete: function (response) {
                                //console.log(response.status);
                                if(response.status == 200) {
                                    //console.log('actualizando steps');
                                    $('.blogs').html('<p class="have">'+Polyglo.t('general.check_blog')+'<br><br>');

                                    $('.blogs').append('<table class="table table-blogs"><thead><tr><th>ID BLog</th><th>Blog</th></tr></thead><tbody></tbody></table><br>');
                                    for (var i=0; i<result.length; i++) {
                                        $('select.select-blogs').append('<option value="'+result[i].id+'">'+result[i].name_updated+'</option>');
                                        $('table.table-blogs').append('<tr><td>'+result[i].id+'</td><td>'+result[i].name_updated+'</td></tr>');
                                    }
                                    $('.blogs').append('<a class="btn btn-warning" href="#upgrade" data-toggle="modal">'+Polyglo.t('general.update_blog')+'</a><br><br><p>'+Polyglo.t('general.continue_check')+' <a href="#ca" class="continue">'+Polyglo.t('general.continue')+'</a>.</p>');
                                    $(that.overlay).hide();
                                }
                            }
                        });

                    }
                    if (response.status == 404) {
                        $('.blogs').html('<p class="nothing">'+Polyglo.t('general.noblogs')+' <a href="#steptostep">'+Polyglo.t('general.step')+'</a> '+Polyglo.t('general.again')+'</p>');
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
        render : function(args){

            if (localstorage.get('logged') === false)
                window.location.href = '/';
            else {
                if(localstorage.get('steps') == 1){
                    window.location.href = '/#ca';
                }
                else{
                    this.$el.html(this.template);
                    $('.header-step').html(this.header);
                    this.loadInfo();
                }
            }
        }

    });

    return checkblogsView;
});



