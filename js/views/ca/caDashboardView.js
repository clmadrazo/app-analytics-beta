define([
        'doT'
        , 'views/baseView'
        , 'text!templatesFolder/header/header_nav.html'
        , 'text!templatesFolder/ca/ca_1.html'
        ,'models/ca/caModel'
        , 'bootstrap-select'
        , 'moment'
        , 'locale'
        , 'datetimePicker'
    ]
    , function( doT, BaseView, HeaderNavTemplate,CaTemplate, CaModel) {

        var CaDashboardView = BaseView.extend({
            el : '#main-wrapper',
            headerNav : doT.template(HeaderNavTemplate),
            template : doT.template(CaTemplate),
            caModel : new CaModel(),
            date1: null,
            date2: null,
            blog: null,
            length: 10,
            events: {
                "change select.select_blogs":"_reloadTable",
                "dp.change #datetimepickerCalender1":"_reloadTable",
                "dp.change #datetimepickerCalender2":"_reloadTable",
                "click a.profile":"cProfile",
                "click a.top_author":"top",
                "click a.top_post":"top",
                "click a.top_topic":"top",
                "mousemove":"zerar",
            },
            top:function(){
                localstorage.set('blog',this.blog);
            },
            zerar:function(){
                this.idleTime = 0;
            },
            cProfile:function(){
                $(this).attr('aria-expanded',true);
                $('.user').addClass('open');
            },
            _reloadTable:function(){
                $(this.overlay).height('100%');
                $(this.overlay).height($(this.overlay).height()+380);
                $(this.overlay).show();
                this.buildAnalytics();
            },
            initialize: function(options) {
                // overriding stuff here
                BaseView.prototype.initialize.call(this,options); // calling super.initializeMethod()
                //this.loadClients();
                //this.campaignCollection.getCampaignList();
                var self = this;
                //Increment the idle time counter every minute.
                this.idleInterval = setInterval(function() {
                    self.timerIncrement();
                }, 60000); // 1 minute
            },
            loadBlogAndUser:function(){
                var that = this;
                this.caModel.fetch({
                    type : 'GET',
                    url : '/ws/blog/search/' + localstorage.get('uid'),
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    complete: function(response) {
                        //console.log(response.status);
                        if (response.status == 200) {
                            var result = response.responseJSON.result;
                            //console.log(result);
                            var idFirstBlog = result[0].id;
                            localstorage.set('id_blog',idFirstBlog);
                            for (var i=0; i<result.length; i++) {
                                $('select.select_blogs').append('<option value="'+result[i].id+'">'+result[i].name_updated+'</option>');
                            }
                            $('select.select_blogs').selectpicker();
                            $('.select_blogs button').on('click', function () {
                                $('#md-overlay1').height($(document).height());
                                $('#md-overlay1').fadeTo("fast", 0.8);
                                $(this).attr('aria-expanded',true);
                                $('.select_blogs').addClass('open');
                            });

                            that.buildView();
                        }
                        if (response.status == 401) {
                            var refresh = localstorage.get('refresh');
                            that.caModel.fetch({
                                type: 'POST',
                                beforeSend: function (xhr) {
                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                },
                                data: '[{ "refreshToken": "'+refresh+'"}]',
                                url: '/ws/authentication/refresh-token/ ',
                                complete: function (xhr) {
                                    localstorage.set('token',xhr.getResponseHeader('Bearer-Token'));
                                    localstorage.set('refresh',xhr.getResponseHeader('Refresh-Token'));
                                    location.reload();
                                }
                            });
                        }
                        if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                    }
                });
                //$('.user .avatar').append();
                //<div class="avatar"><img src="styles/images/avatar.png" alt="Rafael Santiago"></div>
                //    <div class="nombre dropdown" ><span>Rafael Santiago</span> <b class="caret" style="color:#000;"></b></div>
            },
            buildView:function() {

                var dateTimePicker1 = $('#datetimepickerCalender1').datetimepicker({
                    format: 'YYYY-MM-DD',
                    maxDate: new Date()
                });
                $('#datetimepickerCalender2').datetimepicker({
                    useCurrent: false,
                    format: 'YYYY-MM-DD',
                    maxDate: new Date()
                });
                $('.dtp-calendar1,.dtp-calendar2,td.day').on('click', function () {
                    $('#md-overlay2').height($(document).height());
                    $('#md-overlay2').fadeTo("fast", 0.8);
                });
                $('.dtp-calendar1').on('dp.change', function (e) {
                    $('#datetimepickerCalender2').data('DateTimePicker').minDate(e.date);
                    $("#md-overlay2").hide();
                });
                $('.dtp-calendar2').on('dp.change', function (e) {
                    $('#datetimepickerCalender1').data('DateTimePicker').maxDate(e.date);
                    $("#md-overlay2").hide();
                });

                $(".blog .dropdown-menu li").click(function () {
                    $("#md-overlay1").hide();
                });
                $("#md-overlay1").click(function () {
                    $("#md-overlay1").hide();
                });
                $("#md-overlay2").click(function () {
                    $("#md-overlay2").hide();
                });
                var d = new Date(),
                    hoy = this.buildDate(d);
                d.setDate(d.getDate() - 30);
                var back = this.buildDate(d);
                if(localstorage.get('date1') != null || localstorage.get('date1') != undefined)
                    $('input.dtp-calendar1').val(localstorage.get('date1'));
                else
                    $('input.dtp-calendar1').val(back);
                if(localstorage.get('date2') != null || localstorage.get('date2') != undefined)
                    $('input.dtp-calendar2').val(localstorage.get('date2'));
                else
                    $('input.dtp-calendar2').val(hoy);
                this.buildAnalytics();
            },
            buildDate:function(d){
                var year = d.getFullYear(),
                    month = d.getMonth()+ 1,
                    day = d.getDate();
                if(month < 10)
                    month = "0"+month;
                if(day < 10)
                    day = "0"+day;
                return year + "-" + (month) + "-" + day;
            },
            buildAnalytics : function(){
                var that = this;
                date1 = $('input.dtp-calendar1').val();
                localstorage.set('date1',date1);
                date2 = $('input.dtp-calendar2').val();
                localstorage.set('date2',date2);
                this.blog = $('select.select_blogs').val();
                $('.analytics .numero_grande').empty();
                $('.top_author .table-post tbody').empty();
                $('.top_post .table-post tbody').empty();
                $('.top_topic .table-post tbody').empty();
                this.caModel.fetch({
                    type: 'POST',
                    url: '/ws/blog-post/analytics/',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    data: '[{ "key_api": "'+localstorage.get('uid')+'-'+this.blog+'", "date1": "'+localstorage.get('date1')+'","date2": "'+localstorage.get('date2')+'"}]',
                    complete: function (response) {
                        //console.log(response);
                        if (response.status == 200) {
                            var result = response.responseJSON.result;
                            $('.analytics .time_total .numero_grande').append(result.total_time);
                            $('.analytics .total_persons .numero_grande').append(result.total_persons);
                            $('.analytics .tt_person .numero_grande').append(result.tt_person);
                            $('.analytics .ttx500 .numero_grande').append(result.tt_500);
                            that.buildTable();
                        }
                        else if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                    }});
            },
            buildTable : function(){
                //buildTableAuthor
                $(this.overlay).height($(this.overlay).height()+$('.analytics .time_total .numero_grande').height());
                var that = this;
                this.caModel.fetch({
                    type: 'POST',
                    url: '/ws/blog-post/top-author/',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    data: '[{ "key_api": "'+localstorage.get('uid')+'-'+this.blog+'", "date1": "'+localstorage.get('date1')+'","date2": "'+localstorage.get('date2')+'"}]',
                    complete: function (response) {
                        //console.log(response);

                        var result = response.responseJSON.result;
                        if(result.length < 10)
                            that.length = result.length;
                        else
                            that.length = 10;
                        if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                        else if(response.status == 200) {
                            for (var i = 0; i < that.length; i++) {
                                $('.top_author .table-post tbody').append(
                                    '<tr>' +
                                    '<td>' + result[i].author + '</td>' +
                                    '<td>' + result[i].title + '</td>' +
                                    '<td>' + result[i].facebook + '</td>' +
                                        //'<td>'+result[i].twitter+'</td>'+
                                    '<td>' + result[i].google_plus + '</td>' +
                                    '<td>' + result[i].linkedin + '</td>' +
                                    '<td>' + result[i].shares + '</td>' +
                                    '<td>' + result[i].shares_posts + '</td>' +
                                    '</tr>'
                                );
                            }
                        }
                        else if(response.status == 404) {
                            $('.top_author .table-post tbody').append('<tr><td>'+Polyglo.t('general.norecords')+'</td></tr>');
                        }
                        that.buildTablePost();
                }});
            },
            buildTablePost : function(){
                var that = this;
                $(this.overlay).height($(this.overlay).height()+$('.top_author .table-post tbody').height());
                this.caModel.fetch({
                    type: 'POST',
                    url: '/ws/blog-post/top-post/',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    data: '[{ "key_api": "'+localstorage.get('uid')+'-'+this.blog+'", "date1": "'+localstorage.get('date1')+'","date2": "'+localstorage.get('date2')+'"}]',
                    complete: function (response) {
                        //console.log(response);

                        var result = response.responseJSON.result;
                        if(result.length < 10)
                            that.length = result.length;
                        else
                            that.length = 10;
                        if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                        if (response.status == 200) {
                            for (var i=0; i<that.length; i++) {
                                $('.top_post .table-post tbody').append(
                                    '<tr>'+
                                        '<td>'+result[i].title+'</td>'+
                                        '<td>'+result[i].date.date.substring(0, 10)+'</td>'+
                                        '<td>'+result[i].time+'</td>'+
                                        '<td>'+result[i].words+'</td>'+
                                        '<td>'+result[i].facebook+'</td>'+
                                        //'<td>'+result[i].twitter+'</td>'+
                                        '<td>'+result[i].google_plus+'</td>'+
                                        '<td>'+result[i].linkedin+'</td>'+
                                        '<td>'+result[i].shares+'</td>'+
                                        '<td>'+result[i].view+'</td>'+
                                    '</tr>'
                                );
                            }
                        }
                        else if(response.status == 404) {
                            $('.top_post .table-post tbody').append('<tr><td>'+Polyglo.t('general.norecords')+'</td></tr>');
                        }
                        that.buildTableTopic();
                    }});
            },
            buildTableTopic : function(){
                var that = this;
                $(this.overlay).height($(this.overlay).height()+$('.top_post .table-post tbody').height());
                this.caModel.fetch({
                    type: 'POST',
                    url: '/ws/blog-post/top-topic/',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    data: '[{ "key_api": "'+localstorage.get('uid')+'-'+this.blog+'", "date1": "'+localstorage.get('date1')+'","date2": "'+localstorage.get('date2')+'"}]',
                    complete: function (response) {
                        //console.log(response);

                        var result = response.responseJSON.result;
                        if(result.length < 10)
                            that.length = result.length;
                        else
                            that.length = 10;
                        if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                        else if (response.status == 200) {
                            for (var i = 0; i < that.length; i++) {

                                $('.top_topic .table-post tbody').append(
                                    '<tr>' +
                                    '<td>' + result[i].category + '</td>' +
                                    '<td>' + result[i].total_posts + '</td>' +
                                    '<td>' + result[i].facebook + '</td>' +
                                        //'<td>'+result[i].twitter+'</td>'+
                                    '<td>' + result[i].google_plus + '</td>' +
                                    '<td>' + result[i].linkedin + '</td>' +
                                    '<td>' + result[i].shares + '</td>' +
                                    '<td>' + result[i].shares_posts + '</td>' +
                                    '</tr>'
                                );
                            }
                        }
                        else if (response.status == 404) {
                            $('.top_topic .table-post tbody').append('<tr><td>'+Polyglo.t('general.norecords')+'</td></tr>');
                        }
                        $(that.overlay).hide();
                        $('#page').css('height','auto');
                        //$('.cob').css('height','auto');
                    }});
            },
            render : function(){
                if (localstorage.get('logged') === false)
                    window.location.href = '/';
                else {
                    //$(this.overlay).height($('#page').height());
                    //console.log($(this.overlay).height());
                    $(this.overlay).height('100%');
                    $(this.overlay).height($(this.overlay).height()+380);
                    $(this.overlay).show();
                    BaseView.prototype.render.call(this);
                    this.$el.html(this.template);
                    $('nav.navbar-fixed-top').html(this.headerNav);
                    $('.sel').addClass('active');
                    $('.sel').html('<a href="#analytics"><i class="fa fa-fw fa-dashboard"></i><img src="img/pie.png" alt=""> Analytics</a>');
                    $('.div_back').html('<a href="#ca"><i class="fa fa-arrow-left"></i>Analytics</a>');
                    this.loadBlogAndUser();
                }
            },
            close: function(){

                this.$el.empty().off();


            },

            /*   _makeDraggable : function() {

             var options = {
             cell_height: 225,
             vertical_margin: 10
             };

             $('.grid-stack').gridstack(options);
             $('.box-dashboard-content ul').append('<div class="clear"></div>');
             $('.last').after('<div class="clear"></div>');
             }*/

        });


        return CaDashboardView;

    });