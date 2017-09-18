 require.config({
  	baseUrl : 'js',
	shim: {
		'jquery' : {
			exports: '$'
		},
		'underscore':{
			exports: '_'
		},
		'backbone':{
			deps : ['underscore', 'jquery'],
			exports: 'Backbone'			
		},
		'bootstrap':{
			deps : ['jquery']
	    },
		'polyglot': {
	      exports: 'Polyglot'
	    },
	    'jqueryStorage' : {
	    	deps : ['jquery'],
	    	exports: 'Storage'
	    },
	    'master' : {
	    	deps : ['jquery']
	    },
		'bootstrap-select' : {
			deps : ['bootstrap','jquery']
		},
	    'jqueryUi' : {
	    	deps : ['jquery']
	    },
        'moment':{
            deps:['jquery']
        },
		'locale':{
			deps:['jquery','moment']
		},
		'datetimePicker':{
			deps:['jquery']
		},
		'main':{
			deps:['jquery']
		},
		'steps':{
			deps:['jquery']
		},
		'linkPreview' : {
			deps : ['jquery']
		},
		'linkPreviewRetrieve' : {
			deps : ['jquery']
		},
		'text' : {
			deps : ['jquery']
		},
		'steps' : {
			deps : ['jquery']
		}
    },
	paths: {
		'jquery': 'lib/jquery-2.1.4.min',
		'underscore': 'lib/underscore1.6.0/underscore-min',
		'backbone': 'lib/backbone1.1.2/backbone-min',
		'doT' : 'lib/doT1.0.0/doT.min',
		'bootstrap' : 'lib/bootstrap.min',
		'steps' : 'lib/jquery-steps/jquery.steps.min',
		'locale' : 'lib/locale/pt-br',
		'text' : 'lib/text/text',
		'pnotify': 'lib/pnotify/pnotify.custom.min',
		'pnotify.buttons': 'lib/pnotify/pnotify.custom.min',
		'polyglot' : 'lib/polyglot/polyglot.min',
		'modernizr' : 'lib/modernizr2.6.2/modernizr-2.6.2.min',
		'jqueryStorage': 'lib/jquery-storage1.7.2/jquery.storageapi.min',
		'templatesFolder' : '../templates',
		'master' : 'lib/master',
		'linkPreview' : 'lib/Facebook-Link-Preview-master/js/linkPreview',
		'linkPreviewRetrieve' : 'lib/Facebook-Link-Preview-master/js/linkPreviewRetrieve',
		'main' : 'lib/ca-main',
		'bootstrap-select' : 'lib/bootstrap-select',
		'jqueryUi' : 'lib/jquery-ui-1.11.4/jquery-ui.min',
        'moment': 'lib/moment-develop/moment',
        'datetimePicker':'lib/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min'
	},
	waitSeconds: 0
});

require(["jquery","backbone","router","polyglot", "master", "bootstrap", "jqueryUi", "jqueryStorage", 'pnotify', 'pnotify.buttons'], function($, Backbone, Router,Polyglot, Master) {

	//Create namespace and instance for localstorage
    ns = $.initNamespaceStorage('socialplatform');
    localstorage = ns.localStorage;
	router = new Router();
    PNotify.prototype.options.styling = "bootstrap3";

	// Tells Backbone to start watching for hashchange events
	Backbone.history.start();

});
