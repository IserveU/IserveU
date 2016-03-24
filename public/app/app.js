(function() {

	'use strict';


	var iserveu = angular
		.module('iserveu', [
			'ngCookies',
			'ngResource',
			'ngMaterial',
			'ngMessages',
			'ngSanitize', 
			'satellizer',
			'textAngular',
			'ui.router',
			'flow',
            'infinite-scroll',
			'pascalprecht.translate',
			'mdColorPicker'
		])
		.run(['$rootScope', '$auth', '$window', '$timeout', 'redirect', 'globalService', 
			function($rootScope, $auth, $window, $timeout, redirect, globalService) {
				
				$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	


			    	$rootScope.pageLoading = true;

					/**
					*	Taken out for Localized Economies which does not require this level
					*   of authentication. Must also allow user's to view things.
					*/

					// redirect.onLogin(toState, toParams, fromState);

					// redirect.ifNotAuthenticated(
					// 			event,
					// 			toState.data.requireLogin,
					// 			$auth.isAuthenticated(),
					// 			toState.name,
					// 			fromState.name
					// 		);

					globalService.checkUser();
					globalService.setState( toState );
			    	
				});

			    $rootScope.$on('$viewContentLoaded',function(){
			    	$timeout(function() {
			    		$rootScope.pageLoading = false;
			    	}, 500);
			    });

		        globalService.init();

				$window.onbeforeunload = function(e) {
					var publicComputer = localStorage.getItem('public_computer');
					if(JSON.parse(publicComputer) == true) 
						return localStorage.clear();
				};

		}]);

	fetchData().then(bootstrapApplication);

	function fetchData() {
        var initInjector = angular.injector(['ng']);
        var $http = initInjector.get('$http');

        return $http.get('settings').then(function(response) {
			localStorage.setItem('settings', JSON.stringify(response.data));
            iserveu.constant('SETTINGS_JSON', response.data);
        }, function(errorResponse) {
            // Handle error case
            console.log('error');
        });
    }

    function bootstrapApplication() {
        angular.element(document).ready(function() {
            angular.bootstrap(document, ['iserveu']);
        });
    }
		
}());