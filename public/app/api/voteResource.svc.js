(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('voteResource', ['$resource', '$q', voteResource]);

	function voteResource($resource, $q) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

		var MyVotes = $resource('api/user/:id/vote', {limit:'@limit'}, {
			query: {
		        method: 'GET',
		        params: {},
		        isArray: true,
		        ignoreLoadingBar: true
			}
		});

		var Vote = $resource('api/vote/:id', {ignoreLoadingBar:'@true'}, {
	        'update': {
	        	 method:'PUT',  
	        	 ignoreLoadingBar: true 
        	}
	    });

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/


	    /**
	    *	
	    * 	@params: {user_id: number, motion_id: number, position: number}
	    */
		function castVote(data) {
			return Vote.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

	    /**
	    *	
	    * 	@params: {id: number, user_id: number, motion_id: number, position: number}
	    */
		function updateVote(data) {
			return Vote.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

	    function getMyVotes(id, limit) {
			return MyVotes.get({id:id}, limit).$promise.then(function(results) {
				return results;
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
			castVote: castVote,
			updateVote: updateVote,
			getMyVotes: getMyVotes,
			getUsersVotes: getUsersVotes
		}

	}



}());