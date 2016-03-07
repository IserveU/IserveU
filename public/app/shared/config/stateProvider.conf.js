(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$stateProvider',

     /** @ngInject */
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
        .state('edit-home', {
            url: '/edit-home',
            template: '<edit-home>',
            data: {
                requireLogin: true
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
            template: '<display-profile></display-profile>',
            data: {
                requireLogin: true
            },
            resolve: {
                profile: function(user, $stateParams) {
                    var profile;
                    return user.getUser($stateParams.id)
                        .then(function(r) {
                            return profile = r; });
                }
            },
            controller: function($scope, profile) {
                $scope.profile = profile;
            }
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
                requireLogin: true
            },  
            resolve: {
                profile: function(user, $stateParams) {
                    var profile;
                    return user.getUser($stateParams.id)
                        .then(function(r) {
                            return profile = r; });
                },
                communityIndex: function($http) {
                    var community;
                    return $http.get('/api/community')
                        .success(function(r){
                            return community = r;
                    });
                }
            },
            controller: function($scope, profile, communityIndex) {
                $scope.profile = profile;
                $scope.communities = communityIndex.data;
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
        	
	}]);

})();