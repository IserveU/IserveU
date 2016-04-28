(function() {

	'use strict';

	// /** @ngInject */
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
			'mdColorPicker',
			'isu-form-sections'
		])
		.run(['$rootScope', '$auth', '$window', '$timeout', '$globalProvider',
			function($rootScope, $auth, $window, $timeout, $globalProvider) {
				
				$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	

			    	$rootScope.pageLoading = true;

					$globalProvider.checkUser();
					$globalProvider.checkPermissions( event, toState.data.requirePermissions );
					$globalProvider.setState( toState );
					
				});

			    $rootScope.$on('$viewContentLoaded',function(){
			    	$timeout(function() {
			    		$rootScope.pageLoading = false;
			    	}, 500);
			    });

		        $globalProvider.init();

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
            angular.bootstrap(document, ['iserveu'], {strictDi: true});
        });
    }
		
}());