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
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'motion';
            }]
    	})
    	.state( 'motion', {
    	    url: '/motion/:id',
    	    templateUrl: 'app/components/motion/partials/motion.tpl.html',
    	    controller: 'MotionController as motion',
    	    data: {
    	        requireLogin: true
    	    },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'motion';
            }]
    	})
        .state( 'dashboard', {
            url: '/dashboard',
            template: '<admin-dashboard />',
            data: {
                requireLogin: true
            },   
        })
        .state( 'drafts', {
            url: '/drafts',
            template: '<motion-drafts />',
            data: {
                requireLogin: true
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'motion';
            }]
        })
    	.state( 'createmotion', {
    	    url: '/createmotion',
    	    templateUrl: 'app/components/motion/components/createmotion/createmotion.tpl.html',
    	    controller: 'CreateMotionController as create',
    	    data: {
    	        requireLogin: true
    	    },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'motion';
            }]
    	})
        .state( 'userlist', {
            url: '^/userlist',
            templateUrl: 'app/components/user/components/role/roles.tpl.html',
            controller: 'UserController as user',
            data: {
                requireLogin: true
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'user';
            }]
        })
        .state( 'user', {
            url: '/user/:id',
            templateUrl: 'app/components/user/partials/user-profile.tpl.html',
            controller: 'UserController as vm',
            data: {
                requireLogin: true
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'user';
            }]
        })
        .state( 'user.profile', {
            url: '/profile',
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'user';
            }]
        })
        .state( 'createuser', {
            url: '/create/user',
            templateUrl: 'app/components/user/componentscreateuser/createuser.tpl.html',
            controller: 'CreateUserController as create',
            data: {
                requireLogin: true
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'user';
            }]
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
    	.state('department' , {
    		url: '/departments/:id',
        	controller: 'DepartmentController as department',
        	templateUrl: 'app/components/department/department.tpl.html',
            data: {
                requireLogin: true
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'department';
            }]    
    	})
    	.state('backgroundimage', {
            url: '/upload',
        	controller: 'BackgroundImageController as background',
        	templateUrl: 'app/components/backgroundimage/partials/backgroundimage.tpl.html',
            data: {
                requireLogin: true
            }, 
           onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'backgroundimage';
            }] 
    	})
    	.state('backgroundimage.preview', {
            url: '^/preview/:id',
        	controller: 'PreviewImageController as preview',
        	templateUrl: 'app/components/backgroundimage/components/preview_image/preview_image.tpl.html',
            data: {
                requireLogin: true
            }, 
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'backgroundimage';
            }]
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