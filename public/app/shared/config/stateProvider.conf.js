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
    	    }
    	})
    	.state( 'motion', {
    	    url: '/motion/:id',
    	    template: '<display-motion />',
    	    data: {
    	        requireLogin: true
    	    }
    	})
        .state( 'dashboard', {
            url: '/dashboard',
            templateUrl: 'app/components/admin/dashboard.tpl.html',
            data: {
                requireLogin: true
            }   
        })
        .state( 'pages', {
            url: '/page/:id',
            template: '<page-content />',
            data: {
                requireLogin: true
            }  
        })
        .state( 'edit-page', {
            url: '^/page/:id/edit',
            template: '<edit-page-content />',
            data: {
                requireLogin: true
            }  
        })
    	.state( 'create-motion', {
    	    url: '^/motion/create',
    	    templateUrl: '<create-motion />',
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
        .state( 'user.profile', {
            url: '/profile',
        })
        .state( 'create-user', {
            url: '^/user/create',
            templateUrl: 'app/components/user/components/create-user/create-user.tpl.html',
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