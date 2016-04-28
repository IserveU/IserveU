(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$httpProvider',

	function($httpProvider){

		$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"; // for AJAX
		$httpProvider.defaults.headers.common['X-CSRFToken'] = localStorage.getItem('satellizer_token');

		/** 
		/* This is being unused ... it's a good concept that will come in handy later as an error handler
		/* as well as a trigger for 200 triggers!
		*/
		
		// $httpProvider.interceptors.push(function($timeout, $q, $injector, $rootScope) {
		// 	var $state, $http;

		// 	$timeout(function() {
		// 		$http = $injector.get('$http');
		// 		$state = $injector.get('$state');
		// 	});
		

		// 	return {
		// 		responseError: function(rejection) {
		// 			if(rejection.status === 401)
		// 				// $state.go('permissionfail');

		// 			if(rejection.status === 403)
		// 				// access forbidden
		
		// 			return $q.reject(rejection);
		// 		}
		// 	}
		// });





	}]);


})();