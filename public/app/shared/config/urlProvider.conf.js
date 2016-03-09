(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$urlRouterProvider', '$authProvider', '$compileProvider',

	function($urlRouterProvider, $authProvider, $compileProvider){

		$compileProvider.debugInfoEnabled(false); // speeds up the app, the debug info are for {{}}

		$authProvider.loginUrl = '/authenticate';

	    // the overall default route for the app. If no matching route is found, then go here
	    $urlRouterProvider.otherwise('/home');			
		$urlRouterProvider.when("/user/:id", "/user/:id/profile"); // for displaying sub-url

	}]);


})();