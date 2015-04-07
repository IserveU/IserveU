(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motion', motion);

	function motion($resource) {

		var Motion = $resource('api/motions/:id');

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