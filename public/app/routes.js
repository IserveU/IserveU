(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$stateProvider', 'SETTINGS_JSON',

	function($stateProvider, SETTINGS_JSON){

    $stateProvider
    	.state( 'home', {
    		url: '/home',
    		templateUrl: 'app/components/home/home.tpl.html',
    		controller: 'HomeController as home',
    		data: {
    	        requireLogin: true
    	    }
    	})
        .state('edit-home', {
            url: '/edit-home',
            template: '<edit-home>',
            data: {
                requireLogin: true,
                requirePermissions: ['create-motions', 'delete-users']
            }
        })
        .state( 'dashboard', {
            url: '/dashboard',
            templateUrl: 'app/components/admin.dash/admin.dash.html',
            data: {
                requireLogin: true,
                requirePermissions: ['create-motions', 'delete-users']
            }
        })
    	.state( 'motion', {
    	    url: '/'+SETTINGS_JSON.jargon.en.motion.toLowerCase()+'/:id',
    	    template: '<display-motion></display-motion>',
    	    data: {
    	        requireLogin: true,
                moduleMotion: true
    	    }
    	})
        .state('edit-motion', {
            url: '/edit-'+SETTINGS_JSON.jargon.en.motion.toLowerCase()+'/:id',
            template: '<motion-form></motion-form>',
            data: {
                requireLogin: true,
                moduleMotion: true,
                requirePermissions: ['administrate-motions']
            }
        })
        .state( 'create-motion', {
            url: '/create-'+SETTINGS_JSON.jargon.en.motion.toLowerCase(),
            template: '<motion-form></motion-form>',
            data: {
                requireLogin: true,
                moduleMotion: true,
                requirePermissions: ['create-motions']
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
                requireLogin: true,
                requirePermissions: ['administrate-motions']
            }  
        })
       .state( 'create-page', {
            url: '/create-page',
            template: '<create-page-content></create-page-content>',
            data: {
                requireLogin: true,
                requirePermissions: ['create-motions']
            }  
        })
        .state( 'user', {
            url: '/user/:id',
            template: '<display-profile></display-profile>',
            data: {
                requireLogin: true
            },
            resolve: {
                profile: ['user', '$stateParams', function(user, $stateParams) {
                    var profile;
                    return user.getUser($stateParams.id)
                        .then(function(r) {
                            return profile = r; });
                }]
            },
            controller: ['$scope', 'profile', function($scope, profile) {
                $scope.profile = profile;
            }]
        })
        .state( 'user.profile', {
            url: '/profile',
            data: {
                requireLogin: true
            }
        })
        .state('edit-user', {
            url: '/edit-user/:id',
            template: '<edit-user></edit-user>',
            data: {
                requireLogin: true,
                // requirePermissions: ['administrate-users'] this won't work because of shared state with my profile
            },  
            resolve: {
                profile: ['user', '$stateParams', function(user, $stateParams) {
                    var profile;
                    return user.getUser($stateParams.id)
                        .then(function(r) {
                            return profile = r; });
                }],
                communityIndex: ['$http', function($http) {
                    var community;
                    return $http.get('/api/community')
                        .success(function(r){
                            return community = r;
                    });
                }]
            },
            controller: ['$scope', 'profile', 'communityIndex', function($scope, profile, communityIndex) {
                $scope.profile = profile;
                $scope.communities = communityIndex.data;
            }]
        }) 
        .state( 'create-user', {
            url: '^/user/create',
            templateUrl: 'app/components/user/components/create-user/create-user.tpl.html',
            controller: 'CreateUserController as create',
            data: {
                requireLogin: true,
                requirePermissions: ['create-users']
            }
        })
        .state('login', {
            url: '/login',
        	controller: 'loginController as login',
        	templateUrl: 'app/shared/auth/login/login.tpl.html',
            data: {
                requireLogin: false
            },
            resolve: {
                communityIndex: ['$http', function($http) {
                    var community;
                    return $http.get('/api/community')
                        .success(function(r){
                            return community = r;
                    });
                }]
            } 
    	})
    	.state('login.resetpassword', {
    		url: '/:token',
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
        	
	}]);

})();