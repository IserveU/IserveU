(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('homeResource', ['$resource', '$q', '$rootScope', homeResource]);

	function homeResource($resource, $q, $rootScope) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

	    var MyComments =  $resource('api/user/:id/comment', {}, {
		    query: {
		        method: 'GET',
		        params: {
		        	id: $rootScope.authenticatedUser.id || null
		        },
		        isArray: true,
		        ignoreLoadingBar: true,
		        transformResponse: function(data, header){
		          var jsonData = JSON.parse(data);
				  var comments = [];

		          angular.forEach(jsonData, function(comment){
		          	comments.push(comment);
		          });

		          return comments;
		        }
		    }
	    });

		var MyVotes = $resource('api/user/:id/vote', {}, {
			query: {
		        method: 'GET',
		        params: {
		        	id: $rootScope.authenticatedUser.id || null,
		        	limit: 5
		        },
		        ignoreLoadingBar: true
			}
		});

	    var TopComments = $resource('api/comment/:id', {}, {
        	query: {
			    method: 'GET',
		        params: {},
		        ignoreLoadingBar: true,
		        isArray: true,
		      	transformResponse: function(data, header){
		      		var comments = [];
		      		if(data.length < 0)
						comments = data.slice(0,5);
			        return comments;
		        }

	    	}
	    });

	    var TopMotion = $resource('api/motion/', {}, { 
			query: {
		        method: 'GET',
		        params: {
		        	rank_greater_than: 0,
		        	take: 1
		        },
				ignoreLoadingBar: true
			}
		});

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/
	    
		function getMyComments() {
			return MyComments.query().$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

	    function getMyVotes() {
			return MyVotes.query().$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getTopComments() {
			return TopComments.query().$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getTopMotion() {
			return TopMotion.query().$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			getMyComments: getMyComments,
			getMyVotes: getMyVotes,
			getTopComments: getTopComments,
			getTopMotion: getTopMotion,
		}
	}

}());