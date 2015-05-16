(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motion', motion);

	function motion($resource) {

		var Motion = $resource('api/motion/:id');
		var MotionComments = $resource('api/motion/getcomments/:id');

		function getMotions() {
			return Motion.query().$promise.then(function(results) {
				return results
			}, function(error) {
				console.log(error);
			});
		}

		function getMotion(id) {
			return Motion.get({id:id}).$promise.then(function(result) {
				return result;
			});
		}

		function getMotionComments(id) {
			return MotionComments.query({id:id}).$promise.then(function(result) {
				return result;
			});
		}

		return {
			getMotions: getMotions,
			getMotion: getMotion,
			getMotionComments: getMotionComments
		}
	}
})();