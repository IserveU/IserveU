(function() {

	'use strict';

	angular
		.module('iserveu', ['ngResource', 'ngMaterial', 'ui.router', 'ngSanitize'])
		.config(function($provide, $stateProvider, $urlRouterProvider, $httpProvider) {

			$urlRouterProvider.otherwise('/home');
			
			$stateProvider
				.state('home', {
					url: 'home',
					templateUrl: 'app/components/home/homeView.html',
					controller: 'homeController as home',
					data: {
						requireLogin: false
					}
				})
				.state('motion', {
					url: 'motion/:motionId',
					templateUrl: 'app/components/motion/motionView.detail.html',
					controller: 'motionController as motion',
					data: {
						requireLogin: false
					}
				})
				.state('profile', {
					url: 'profile',
					templateUrl: 'app/components/user/profileView.html',
					controller: 'userController as user',
					data: {
						requireLogin: true
					}
				})
				.state('login', {
					url: 'login',
					templateUrl: 'app/components/login/loginView.html',
					controller: 'loginController as login'
				});

			$httpProvider.interceptors.push(function ($timeout, $q, $injector) {
			    var loginModal, $http, $state;

			    // this trick must be done so that we don't receive
			    // `Uncaught Error: [$injector:cdep] Circular dependency found`
			    $timeout(function () {
			      loginModal = $injector.get('loginModal');
			      $http = $injector.get('$http');
			      $state = $injector.get('$state');
			    });

			    return {
			      responseError: function (rejection) {
			        if (rejection.status !== 401) {
			          return rejection;
			        }

			        var deferred = $q.defer();

			        loginModal()
			          .then(function () {
			          	deferred.resolve( $http(rejection.config) );
			          })
			          .catch(function () {
			            $state.go('welcome');
			            deferred.reject(rejection);
			          });

			        return deferred.promise;
			      }
			    };
			  });
			
		})
		.run(function($rootScope, $state, loginModal, auth) {

			auth.isLoggedIn().success(function(user) {
				if(user != "not logged in") {
					$rootScope.userIsLoggedIn = true;
					$rootScope.currentUser = user;
				}
				else {
					$rootScope.userIsLoggedIn = false;
					$rootScope.currentUser = undefined;
				}
			});

			$rootScope.$on('$stateChangeStart', function(event, toState, toParams) {
				var requireLogin = toState.data.requireLogin;

				if(requireLogin && typeof $rootScope.currentUser === 'undefined') {
					event.preventDefault();

					loginModal().then(function() {
						return $state.go(toState.name, toParams);
					})
					.catch(function() {
						return $state.go('home');
					});
				}
			})
		});
})();

(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('auth', auth);

	function auth($resource, $http, $sanitize, session, CSRF_TOKEN) {

		var loginError = function(response) {
			
			console.log(response);
		};

		var sanitizeCredentials = function(credentials) {
			return {
		    	email: $sanitize(credentials.email),
		    	password: $sanitize(credentials.password),
		    	csrf_token: CSRF_TOKEN
		  	};
		};

		return {
			login: function(credentials) {
		    	var login = $http.post("/auth/login", sanitizeCredentials(credentials));
		    	return login;
		  	},
		  	logout: function() {
		    	var logout = $http.get("/auth/logout");
		    	return logout;
		  	},
		  	isLoggedIn: function() {
		    	var isLoggedIn = $http.get("api/user/loggedin");
		    	return isLoggedIn;
		  	}
		};
	}

	
})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.controller('homeController', home);

	function home() {

		var vm = this;


	}

})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($scope, $http, $rootScope, $location, auth, $modal, loginModal, $state) {

		var vm = this;

		vm.loginError = false;

		vm.logUserIn = function(email, password) {

			var credentials = { email: email, password: password };

			auth.login(credentials).success(function(data) {
				vm.loginError = false;
				$rootScope.userIsLoggedIn = true;
			}).error(function(data) {
				console.log('There was an error logging in');
			});			
		}

		vm.logUserOut = function() {

			auth.logout().success(function() {
				$rootScope.userIsLoggedIn = false;
				$state.go('home');
				$rootScope.currentUser = undefined;

			});			
		}

		vm.openModal = function() {
			$modal.open({
				templateUrl: 'app/components/login/loginModalTemplate.html'
			});
		}
		
	}

})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginModalController', loginModalController);

	function loginModalController($scope, auth, $rootScope, $state) {

		var vm = this;

		vm.showLoginError = false;

		vm.submit = function(email, password) {

			var credentials = { email:email, password:password };

			auth.login(credentials).success(function(data, status) {
				auth.isLoggedIn().success(function(user) {
					if(user != "not logged in") {
						$rootScope.userIsLoggedIn = true;
						$rootScope.currentUser = user;
						$scope.$close(user);
					}
					else {
						$rootScope.userIsLoggedIn = false;
						$rootScope.currentUser = undefined;
						vm.showLoginError = true;
					}
				});		
			});
		}

		vm.cancel = function() {
			$scope.$dismiss();
			$state.go('home');
		}
	}
})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('loginModal', loginModal);

	function loginModal($modal, $rootScope) {

		function assignCurrentUser(user) {
			$rootScope.currentUser = user;
			return user;
		}

		return function() {
			var instance = $modal.open({
				templateUrl: 'app/components/loginModal/loginModalTemplate.html',
				controller: 'loginModalController',
				controllerAs: 'loginModal'
			});

			return instance.result.then(assignCurrentUser);
		}
	}
})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('motionController', motion);

	function motion(motion, $stateParams, $sce, auth) {

		var vm = this;

		vm.motionDetail = [];
		vm.loggedInUser;

		function getMotion(id) {
			vm.motionDetail = motion.getMotion(id);		
		}

		function getLoggedInUser(id) {
			auth.getLoggedInUser(id).then(function(result) {
				vm.loggedInUser = result;
				console.log("Logged in user is: " + vm.loggedInUser);
			},function(error){
				// a 404 error
			});
			
		}		

		getMotion($stateParams.motionId);
		
	}

})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motion', motion);

	function motion($resource) {

		var Motion = $resource('api/motion/:id');

		function getMotions() {
			return Motion.query().$promise.then(function(results) {
				return results
			}, function(error) {
				console.log(error);
			});
		}

		function getMotion(id) {
			return Motion.query({id:id}, function(result) {
				return result;
			});
		}

		return {
			getMotions: getMotions,
			getMotion: getMotion
		}
	}
})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('session', session);


	function session() {
		return {
		    get: function(key) {
		    	return sessionStorage.getItem(key);
		    },
		    set: function(key, val) {
		    	return sessionStorage.setItem(key, val);
		    },
		    unset: function(key) {
		    	return sessionStorage.removeItem(key);
		    }
		}
	}
})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('sidebarController', sidebar);

	function sidebar(motion, $stateParams) {

		var vm = this;

		vm.motions = [];

		function getMotions() {
			motion.getMotions().then(function(results) {
				vm.motions = results;
			}, function(error) {
				console.log(error);
			});
		}

		getMotions();
	}
})();
(function(){

	'use strict';

	angular
		.module('iserveu')
		.factory('sidebarService', sidebar);

	function sidebar($resource) {

		var Motion = $resource('api/motion');

		function getMotions() {
			Motion.query().$promise.then(function(results) {
				return results
			}, function(error) {
				console.log(error);
			});
		}
		return {
			getMotions: getMotions
		}
	}
})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('userController', user);

	function user($scope, auth) {

		var vm = this;

		
	}
})();
//# sourceMappingURL=iserveu-app.js.map