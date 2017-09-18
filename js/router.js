// Application Router
// =============

// Includes file dependencies
define([ "jquery","backbone" ,"views/checkblogs/blogsView", "views/manageTeam/manageTeamView"
        , "views/calendar/calendarView" , "views/users/userInviteView" , "views/users/registrationView", "views/users/resetView", "views/users/activationView", "views/privacy/privacyView"
        , "views/subscription/subscriptionView","views/users/billingView", "views/post/postView", 'views/login/loginView','views/steptostep/steptostepView', 'views/checkblogs/checkblogsView','views/ca/caView'
        ,'views/top/topPostView','views/top/topAuthorView','views/top/topTopicView'
        ,'views/ca/caDashboardView',"views/logout/logoutView","views/manageCampaings/managecampaignsView","views/search/searchView","views/profile/profileView"

    ],
    function( $, Backbone,blogsView,manageTeamView
        , calendarView, userInviteView, registrationView, resetView, activationView, privacyView
        , subscriptionView, billingView,postView, loginView, steptostepView, checkblogsView,caView,
              topPostView,topAuthorView,topTopicView,
              caDashboardView,logoutView, manageView, searchView, profileView) {

    var AppRouter = Backbone.Router.extend({ 

        // Backbone.js Routes
        routes: {
            // When there is no hash bang on the url, the default method is called
            "(login)(/:lang)": "login",
            "(reset)(/:lang)": "reset",
            "(activation)(/:email/:lang)" : "activation",
            //"calendar" : "calendar",
            "userInvite" : "userInvite",
            "profile" : "profile",
            "blogs" : "blogs",
            "(registration)(/:lang/:code)" : "registration",
            "subscription" : "subscription",
            "steptostep" : "steptostep",
            "billing" : "billing",
            "checkblogs" : "checkblogs",
            "ca" : "ca",
            "top_post" : "top_post",
            "top_author" : "top_author",
            "top_topic" : "top_topic",
            "analytics" : "analytics",
            //"manageteam" : "manageteam",
            "post/:id" : "post",
            //"search/:searchString" : "search",
            //"lang/:id": "redirectLang",
            "logout": "logout",
            //"manage": "manageCampaigns",
            //"privacy" : "privacy"

        },
        blogs : function() {
            if (this.currentBlogsView) this.currentBlogsView.close();
            var blogs = new blogsView();
            this.currentBlogsView = blogs;
            blogs.render();
        },
        billing : function() {
            if (this.currentBillingView) this.currentBillingView.close();
            var billing = new billingView();
            this.currentBillingView = billing;
            billing.render();
        },
        logout : function() {
            if (this.currentLogoutView) this.currentLogoutView.close();
            var logout = new logoutView();
            this.currentLogoutView = logout;
            logout.render();
        },
        profile : function(){
            if (this.currentProfileView)
                this.currentProfileView.close();
            var profile = new profileView();
            this.currentProfileView = profile;
            profile.render();
        },
        //manageteam : function(){
        //    if (this.currentManageTeam)
        //        this.currentManageTeam.close();
        //    var manageTeam = new manageTeamView();
        //    this.currentManageTeam = manageTeam;
        //    manageTeam.render();
        //},
        //calendar : function() {
        //    if (this.currentCalendarView){
        //        if (this.currentCalendarView.filtersView)
        //            this.currentCalendarView.filtersView.close();
        //        this.currentCalendarView.close();
        //    }
        //    if (this.currentDashboardView){
        //        if (this.currentDashboardView.filtersView)
        //            this.currentDashboardView.filtersView.close();
        //        this.currentDashboardView.close();
        //    }
        //
        //    var calendar = new calendarView();
        //    this.currentCalendarView = calendar;
        //    calendar.render();
        //
        //},

        userInvite : function() {
            if (this.currentUserInviteView)
                this.currentUserInviteView.close();
            var userInvite = new userInviteView();
            this.currentUserInviteView = userInvite;
            userInvite.render();
        },

        registration: function(code,lang) {
            if (this.currentRegistrationView) {
                this.currentRegistrationView.close();
            }
            var registration = new registrationView();
            this.currentRegistrationView = registration;
            if (!lang)
                if (localstorage.get('languageCode'))
                    lang = localstorage.get('languageCode');
            if (!code)
                code = null;
            registration.render(lang,code);
        },

        activation: function(lang,email) {
            if (this.currentActivationView) {
                this.currentActivationView.close();
            }
            var activation = new activationView();
            this.currentActivationView = activation;
            if (!lang)
                if (localstorage.get('languageCode'))
                    lang = localstorage.get('languageCode');
            activation.render(email,lang);
        },

        subscription : function(){
            if (this.currentSubscriptionView) {
                this.currentSubscriptionView.close();
            }
            var subscription = new subscriptionView();
            this.currentSubscriptionView = subscription;
            subscription.render();
        },
        post : function(id){
            if (this.currentPostView) this.currentPostView.close();
            var post = new postView();
            this.currentPostView=post;
            post.render(id);
        
        },
        login : function(lang){
            if (this.currentLoginView) this.currentLoginView.close();
            var login = new loginView();
            this.currentLoginView=login;
            if (!lang)
                if (localstorage.get('languageCode'))
                    lang = localstorage.get('languageCode');
            login.render(lang);
        },
        reset : function(lang){
            if (this.currentResetView) this.currentResetView.close();
            var reset = new resetView();
            this.currentResetView=reset;
            if (!lang)
                if (localstorage.get('languageCode'))
                    lang = localstorage.get('languageCode');
            reset.render(lang);
        },
        steptostep : function(lang){
            if (this.currentSteptostepView) this.currentSteptostepView.close();
            var steptostep = new steptostepView();
            this.currentSteptostepView=steptostep;
            steptostep.render();
        },

        ca : function(lang){
            if (this.currentCaView) this.currentCaView.close();
            var ca = new caView();
            this.currentCaView=ca;
            ca.render();
        },
        top_post : function(lang){
            if (this.currentTopPostView) this.currentTopPostView.close();
            var topPost = new topPostView();
            this.currentTopPostView=topPost;
            topPost.render();
        },
        top_author : function(lang){
            if (this.currentTopAuthorView) this.currentTopAuthorView.close();
            var topAuthor = new topAuthorView();
            this.currentTopAuthorView=topAuthor;
            topAuthor.render();
        },
        top_topic : function(lang){
            if (this.currentTopTopicView) this.currentTopTopicView.close();
            var topTopic = new topTopicView();
            this.currentTopTopicView=topTopic;
            topTopic.render();
        },
        analytics : function(lang){
            if (this.currentCaDashboardView) this.currentCaDashboardView.close();
            var analytics = new caDashboardView();
            this.currentCaDashboardView=analytics;
            analytics.render();
        },
        checkblogs : function(lang){
            if (this.currentCheckBlogsView) this.currentCheckBlogsView.close();
            var checkBlogs = new checkblogsView();
            this.currentCheckBlogsView=checkBlogs;
            checkBlogs.render();
        },

        //manageCampaigns : function() {
        //    if (this.currentCampaignView) this.currentCampaignView.close();
        //    var manage = new manageView();
        //    this.currentCampaignView=manage;
        //    manage.render();
        //},
        //privacy : function() {
        //    if (this.currentPrivacyView) {
        //        this.currentPrivacyView.close();
        //    }
        //    var privacy = new privacyView();
        //    this.currentPrivacyView = privacy;
        //    privacy.render();
        //},
        //search : function(searchString) {
        //    var ResultView = new dashboardView();
        //
        //    ResultView.searchPosts(decodeURI(searchString));
        //}







    }); 

    return AppRouter;
    
       
});