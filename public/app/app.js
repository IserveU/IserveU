(function() {

	'use strict';

	angular
		.module('iserveu', ['ngResource', 'ui.bootstrap', 'ui.router', 'ngSanitize'])
		.config(function($provide, $stateProvider, $urlRouterProvider) {

			$urlRouterProvider.otherwise('/');
			
			$stateProvider
				.state('motion', {
					url: 'motion/:motionId',
					templateUrl: 'app/components/motion/motionView.detail.html',
					controller: 'motionController as motion'
				});
		});
})();