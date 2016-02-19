(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($stateProvider){

    // TODO: add state permissions to each state.

    $stateProvider
    	.state( 'home', {
    		url: '/home',
    		templateUrl: 'app/components/home/home.tpl.html',
    		controller: 'HomeController as home',
    		data: {
    	        requireLogin: true
    	    },
            resolve: {
                settingsData: function(settings) {
                    return settings.getData();
                }
            }
    	})
        .state( 'dashboard', {
            url: '/dashboard',
            templateUrl: 'app/components/admin/dashboard.tpl.html',
            data: {
                requireLogin: true
            },
            resolve: {
                settingsData: function(settings) {
                    return settings.getData();
                }
            }
        })
    	.state( 'motion', {
    	    url: '/motion/:id',
    	    template: '<display-motion></display-motion>',
    	    data: {
    	        requireLogin: true,
                moduleMotion: true
    	    }
    	})
        .state('edit-motion', {
            url: '/edit-motion/:id',
            template: '<edit-motion></edit-motion>',
            data: {
                requireLogin: true,
                moduleMotion: true
            }
        })
        .state( 'create-motion', {
            url: '/create-motion',
            template: '<create-motion></create-motion>',
            data: {
                requireLogin: true,
                moduleMotion: true
            }
        })
        .state( 'pages', {
            url: '/page/:id',
            template: '<page-content></page-content>',
            data: {
                requireLogin: true
            }  
        })
        .state( 'edit-page', {
            url: '^/page/:id/edit',
            template: '<edit-page-content></edit-page-content>',
            data: {
                requireLogin: true
            }  
        })
       .state( 'create-page', {
            url: '/create-page',
            template: '<create-page-content></create-page-content>',
            data: {
                requireLogin: true
            }  
        })
        .state( 'user', {
            url: '/user/:id',
            templateUrl: 'app/components/user/partials/user-profile.tpl.html',
            controller: 'UserController as vm',
            data: {
                requireLogin: true
            }
        })
        // this is a good place for resolves
        .state( 'user.profile', {
            url: '/profile',
            data: {
                requireLogin: true
            }
        })
        .state( 'create-user', {
            url: '^/user/create',
            templateUrl: 'app/components/user/components/create-user/create-user.tpl.html',
            controller: 'CreateUserController as create',
            data: {
                requireLogin: true
            }
        })
        .state('show-user', {
            url: '/showuser',
            templateUrl: 'app/components/user/components/show-user/show-user.tpl.html',
            controller: 'CreateUserController as create',
            data: {
                requireLogin: true
            }
        })
    	.state('login', {
            url: '/login',
        	controller: 'loginController as login',
        	templateUrl: 'app/shared/auth/login/login.tpl.html',
            data: {
                requireLogin: false
            } 
    	})
    	.state('login.resetpassword', {
    		url: '/:resetpassword',
    		data: {
    			requireLogin: false
    		}
    	})
    	.state('permissionfail' , {
    		url: '/invalidentry',
        	controller: 'RedirectController as redirect',
        	templateUrl: 'app/shared/permissions/onfailure/permissionsfail.tpl.html',
            data: {
                requireLogin: false
            } 
    	});    
        	
	});

})();