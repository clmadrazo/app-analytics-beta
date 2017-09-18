var Polyglo;
define(['backbone', 'models/langModel', 'polyglot'],function(Backbone, LangModel, Polyglot) {
	var LangView = Backbone.View.extend({

		model : new LangModel(),

		setLanguage : function(lang) {
			
			var locale = this.model.get('locale'),
				userlang = this.model.get('userlang'),
				phrases = '',
				that = this;

			if (lang)
				this.model.url = 'js/i18n/'+lang +'.json';
			else if(localstorage)
				this.model.url = 'js/i18n/'+localstorage.get('languageCode') +'.json';
			else
				this.model.url = 'js/i18n/pt-br.json';
			this.model.fetch( {

				async: false		

			} ).complete(function(data) {
				var phrases = data.responseJSON;
				that.model.set('langData', phrases);
				that.polyglot = new Polyglot({ phrases: phrases, locale: locale });
				that._LoadLanguage();
			});
		},

		_LoadLanguage : function() {
			Polyglo = this.polyglot;
		},

		changeAppText : function (locale,userlang) {
			this.model.set('locale',locale);
			this.model.set('userlang',userlang);
			this.setLanguage();
		}
	});
	return LangView;
});
