define([
        'doT'
        , 'views/baseView'
        , 'text!templatesFolder/header/header_nav.html'
        , 'text!templatesFolder/ca/ca.html'
        ,'models/ca/caModel'
        , 'bootstrap-select'
        , 'moment'
        , 'locale'
        , 'datetimePicker'
    ]
    , function( doT, BaseView, HeaderNavTemplate,CaTemplate, CaModel) {

        var CaView = BaseView.extend({
            el : '#main-wrapper',
            headerNav : doT.template(HeaderNavTemplate),
            template : doT.template(CaTemplate),
            idleInterval: 0,
            caModel : new CaModel(),
            events: {
                "change select.select_blogs":"_reloadTable",
                "dp.change #datetimepickerCalender1":"_reloadTable",
                "dp.change #datetimepickerCalender2":"_reloadTable",
                "click a.profile":"cProfile",
                "mousemove":"zerar",
            },
            zerar:function(){
                this.idleTime = 0;
            },
            cProfile:function(){
                $(this).attr('aria-expanded',true);
                $('.user').addClass('open');
            },
            _reloadTable:function(){
                var that = this;
                $(this.overlay).height($(document).height());
                $(this.overlay).show();
                this.buildTable();
            },
            initialize: function(options) {
                // overriding stuff here
                BaseView.prototype.initialize.call(this,options); // calling super.initializeMethod()
                var self = this;
                //Increment the idle time counter every minute.
                this.idleInterval = setInterval(function() {
                    self.timerIncrement();
                }, 60000); // 1 minute
            },

            loadBlogAndUser:function(){
                var that = this;
                //console.log(localstorage.get('token'));
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
                                //$('#md-overlay1').height($('#md-overlay1').height()+85);
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
                $('#datetimepickerCalender1,#datetimepickerCalender2,td.day').on('click', function () {
                    $('#md-overlay2').height($(document).height());
                    //$('#md-overlay2').height($('#md-overlay2').height()+85);
                    $('#md-overlay2').fadeTo("fast", 0.8);
                });
                $('#datetimepickerCalender1').on('dp.change', function (e) {
                    $('#datetimepickerCalender2').data('DateTimePicker').minDate(e.date);
                    $("#md-overlay2").hide();
                });
                $('#datetimepickerCalender2').on('dp.change', function (e) {
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
                this.buildTable();
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
            buildTable : function(){
                var that = this,
                    blog = $('select.select_blogs').val();
                date1 = $('input.dtp-calendar1').val();
                localstorage.set('date1',date1);
                date2 = $('input.dtp-calendar2').val();
                localstorage.set('date2',date2);
                this.caModel.fetch({
                    type: 'POST',
                    url: '/ws/blog-post/search/',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    data: '[{ "key_api": "'+localstorage.get('uid')+'-'+blog+'", "date1": "'+localstorage.get('date1')+'","date2": "'+localstorage.get('date2')+'"}]',
                    complete: function (response) {
                        $('.table-post-d tbody').empty();
                        var result = response.responseJSON.result;
                        if (response.status == 400) {
                            window.location.href = '/#logout';
                        }
                        for (var i=0; i<result.length; i++) {
                            $('.table-post-d tbody').append(
                                '<tr>'+
                                    '<td>'+result[i].title+'</td>'+
                                    '<td>'+result[i].date_publishing.date.substring(0, 10)+'</td>'+
                                    '<td>'+result[i].social_count_facebook+'</td>'+
                                    //'<td>'+result[i].social_count_twitter+'</td>'+
                                    '<td>'+result[i].social_count_google_plus+'</td>'+
                                    '<td>'+result[i].social_count_linkedin+'</td>'+
                                    '<td>'+result[i].total_social_count+'</td>'+
                                '</tr>'
                            );
                        }
                        if(result.errors) {
                            $('.table-post-d tbody').append('<tr><td>'+Polyglo.t('general.norecords')+'</td></tr>');
                        }
                        $(that.overlay).hide();
                        $('#page').css('height','auto');
                        //$(this.overlay).height($(document).height()+85);
                        //$('.cob').css('height','auto');
                }});
            },
            render : function(){
                if (localstorage.get('logged') === false)
                    window.location.href = '/';
                else {
                    //console.log(localstorage.get('reload'));
                    //if(localstorage.get('reload') == true)
                    //    $(this.overlay).height('100%');
                    //if(localstorage.get('steps') == 1 && localstorage.get('reload') == false)
                    //{
                    //    $(this.overlay).height('100%');
                    //    localstorage.set('reload','true');
                    //}
                    //if(localstorage.get('steps') == 0 && localstorage.get('reload') == false)
                    //    $(this.overlay).height('100%');
                    $(this.overlay).height('100%');
                    $(this.overlay).height($(this.overlay).height()+85);
                    $(this.overlay).show();
                    BaseView.prototype.render.call(this);
                    this.$el.html(this.template);
                    $('nav.navbar-fixed-top').html(this.headerNav);
                    this.loadBlogAndUser();
                }
            },
            close: function(){

                this.$el.empty().off();
                clearInterval(this.idleInterval);

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


        return CaView;

    });