var oneall_subdomain = 'portfolioapp';

/* Asynchronously load the library */
var oa = document.createElement('script');
oa.type = 'text/javascript'; oa.async = true;
oa.src = '//' + oneall_subdomain + '.api.oneall.com/socialize/library.js'
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(oa, s);

/* Initialise the asynchronous queue */
var _oneall = _oneall || [];

var _socialLoginRedirect = function(args) {
    
   session.socialLogin(args.connection.connection_token);   
   return false;
    
}
    
/* Social Login Example */
_oneall.push(['social_login', 'set_providers', ['facebook', 'linkedin']]);
_oneall.push(['social_login', 'set_grid_sizes', [1,3]]);
_oneall.push(['social_login', 'set_custom_css_uri', 'https://oneallcdn.com/css/api/socialize/themes/buildin/connect/large-v1.css']);
_oneall.push(['social_login', 'set_event', 'on_login_redirect', _socialLoginRedirect ]);
_oneall.push(['social_login', 'do_render_ui', 'social_login']);
_oneall.push(['social_login', 'do_render_ui', 'social_login_bottom']);



