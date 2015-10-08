(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('vote', vote);

	function vote($resource, $q) {

		var Vote = $resource('api/vote/:id', {}, {
	        'update': { method:'PUT' }
	    });

		var MyVotes = $resource('api/user/:id/vote', {limit:'@limit'});

	    var MotionVote = $resource('api/motion/:id/vote');


	    // set into local storage array that updates
	    function getMyVotes(id, limit) {
			return MyVotes.get({id:id}, limit).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionVotes(id){
			return MotionVote.get({id:id}).$promise.then(function(results) {
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}

		function castVote(data) {
			return Vote.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

		function updateVote(data) {
			return Vote.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getUsersVotes() {
			return Vote.query().$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}


		return {
			getMyVotes: getMyVotes,
			getMotionVotes: getMotionVotes,
			castVote: castVote,
			updateVote: updateVote,
			getUsersVotes: getUsersVotes
		}

	}



}());