(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motion', motion);

	function motion($resource) {

		var Motion = $resource('api/motion/:id');
		var Comment = $resource('api/motion/getcomments/:id');
		var Vote = $resource('api/vote/:id')

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
			return Comment.query({id:id}).$promise.then(function(result) {
				return result;
			});
		}

		function castVote(data) {
			return Vote.save(data).$promise.then(function(success) {
				console.log(success);
			}, function(error) {
				console.log(error);
			});
		}

		function getUsersVotes() {
			return Vote.query().$promise.then(function(result) {
				return result;
			}, function(error) {
				return error;
			});
		}

		return {
			getMotions: getMotions,
			getMotion: getMotion,
			getMotionComments: getMotionComments,
			castVote: castVote,
			getUsersVotes: getUsersVotes
		}
	}
})();