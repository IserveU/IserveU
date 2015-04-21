(function() {

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