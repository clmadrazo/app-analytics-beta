define([
        'doT'
        , 'views/baseView'
        , 'text!templatesFolder/header/header_nav.html'
        , 'text!templatesFolder/top/top_topic.html'
        ,'models/ca/caModel'
        , 'bootstrap-select'
        , 'moment',
        , 'locale'
        , 'datetimePicker'
    ]
    , function( doT, BaseView, HeaderNavTemplate,TopTopicTemplate, CaModel) {

        var CaView = BaseView.extend({
            el : '#main-wrapper',
            headerNav : doT.template(HeaderNavTemplate),
            template : doT.template(TopTopicTemplate),
            idleInterval: 0,
            caModel : new CaModel(),
            events: {
                "dp.change #datetimepickerCalender1":"_reloadTable",
                "dp.change #datetimepickerCalender2":"_reloadTable",
                "click a.profile":"cProfile",
                "keyup #search-input" : "_searchPosts",
                "mousemove":"zerar",
            },
            _searchPosts: function(event){
                var searchString=$('#search-input').val();
                if( searchString != "")
                {
                    // Show only matching TR, hide rest of them
                    $(".top_topic_1 .table-post tbody>tr").hide();
                    $(".top_topic_1 .table-post td:contains('" + searchString + "')").parent("tr").show();
                }
                else
                {
                    // When there is no input or clean again, show everything back
                    $(".top_topic_1 .table-post tbody>tr").show();
                }

                // jQuery expression for case-insensitive filter
                $.extend($.expr[":"],
                    {
                        "contains": function(elem, i, match, array)
                        {
                            return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                        }
                    });
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
            buildView:function() {

                var dateTimePicker1 = $('#datetimepickerCalender1').datetimepicker({
                    format: 'YYYY-MM-DD',
                    maxDate: new Date()
                });
                $('#datetimepickerCalender2').datetimepicker({
                    useCurrent: false, //Important! See issue #1075
                    format: 'YYYY-MM-DD',
                    maxDate: new Date()
                });
                $('#datetimepickerCalender1,#datetimepickerCalender2,td.day').on('click', function () {
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
                    blog = localstorage.get('blog');
                date1 = $('input.dtp-calendar1').val();
                localstorage.set('date1',date1);
                date2 = $('input.dtp-calendar2').val();
                localstorage.set('date2',date2);
                this.caModel.fetch({
                    type: 'POST',
                    url: '/ws/blog-post/top-topic/',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('Bearer-Token', localstorage.get('token'));
                    },
                    data: '[{ "key_api": "'+localstorage.get('uid')+'-'+blog+'", "date1": "'+localstorage.get('date1')+'","date2": "'+localstorage.get('date2')+'"}]',
                    complete: function (response) {
                        if (response.status == 400) {
                            localstorage.set('msg',Polyglo.t('general.logoutE'));
                            window.location.href = '/#logout';
                        }
                        else if (response.status == 200) {
                            $('.top_topic_1 .table-post tbody').empty();
                            var result = response.responseJSON.result;
                            for (var i = 0; i < result.length; i++) {
                                $('.top_topic_1 .table-post tbody').append(
                                    '<tr>'+
                                        '<td>' + result[i].category + '</td>'+
                                        '<td>' + result[i].total_posts + '</td>'+
                                        '<td>' + result[i].facebook + '</td>'+
                                        //'<td>'+result[i].twitter+'</td>'+
                                        '<td>' + result[i].google_plus + '</td>'+
                                        '<td>' + result[i].linkedin + '</td>'+
                                        '<td>' + result[i].shares + '</td>'+
                                    '</tr>'
                                );
                            }
                            $(that.overlay).hide();
                            $('#page').css('height', 'auto');
                            //$('.cob').css('height','auto');
                        }
                        else if(response.status == 404) {
                            $(that.overlay).hide();
                            $('.top_topic_1 .table-post tbody').append('<tr><td>'+Polyglo.t('general.norecords')+'</td></tr>');
                        }
                    }});
            },
            render : function(){
                if (localstorage.get('logged') === false)
                    window.location.href = '/';
                else if(localstorage.get('blog') == undefined || localstorage.get('blog') == '')
                    window.location.href = '/#analytics';
                else {
                    //$(this.overlay).height($('#page').height());
                    //console.log($(this.overlay).height());
                    $(this.overlay).height('100%');
                    $(this.overlay).height($(this.overlay).height()+85);
                    $(this.overlay).show();
                    BaseView.prototype.render.call(this);
                    this.$el.html(this.template);
                    $('nav.navbar-fixed-top').html(this.headerNav);
                    $('.div_back').html('<a href="#analytics"><i class="fa fa-arrow-left"></i>Top topic</a>');
                    this.buildView();
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