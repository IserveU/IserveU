(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($stateProvider){

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
            // cache: true,
    	    url: '/motion/:id',
    	    templateUrl: 'app/components/motion/motion.tpl.html',
    	    controller: 'MotionController as motion',
    	    data: {
    	        requireLogin: true,
                motionOpen: null,
                overallPosition: null,
                userVote: null
    	    },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'motion';
            }]
    	})
    	.state( 'motion.components', {
    		url: '/',
    		views: {
    			'editmotion': {
    				templateUrl: 'app/components/motion/edit-motion.tpl.html'
    			},
    			'votes': {
    				templateUrl: 'app/components/vote/vote.tpl.html',
                    controller: 'VoteController as vm',
    			},
    			'comments': {
		    	    templateUrl: 'app/components/comment/templates/comment.tpl.html',
		    	    controller: 'CommentController as vm',
    			}
    		},
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'motion';
            }]
    	})
    	.state( 'createmotion', {
    	    url: '/createmotion',
    	    templateUrl: 'app/components/motion/createmotion/createmotion.tpl.html',
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
            templateUrl: 'app/components/role/roles.tpl.html',
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
            templateUrl: 'app/components/user/user.tpl.html',
            controller: 'UserController as user',
            data: {
                requireLogin: true
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'user';
            }]
        })
        .state( 'user.details', {
            url: '/profile',
            views: {
                'details': {
                    templateUrl: 'app/components/user/edittemplates/edit-user-details.tpl.html'
                },
                'editname': {
                    templateUrl: 'app/components/user/edittemplates/edit-user-name.tpl.html'
                },
                'roles': {
                    templateUrl: 'app/components/user/edittemplates/edit-roles.tpl.html',
                },
                'address': {
                    templateUrl: 'app/components/user/edittemplates/edit-user-address.tpl.html',
                    controller: 'PropertyController as property'
                }
            },
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'user';
            }]
        })
        .state( 'createuser', {
            url: '/create/user',
            templateUrl: 'app/components/user/createuser/createuser.tpl.html',
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
        	templateUrl: 'app/shared/login/login.tpl.html',
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
    	.state('property' , {
    		url: '/property',
        	controller: 'PropertyController as property',
        	templateUrl: 'app/components/property/property.tpl.html',
            data: {
                requireLogin: true
            }, 
            onEnter: ['$rootScope', function($rootScope) {
                $rootScope.currentState = 'property';
            }]  
    	})
    	.state('backgroundimage', {
            url: '/upload',
        	controller: 'BackgroundImageController as background',
        	templateUrl: 'app/components/backgroundimage/backgroundimage.tpl.html',
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
        	templateUrl: 'app/components/backgroundimage/preview_image.tpl.html',
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