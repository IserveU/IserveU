(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('homeResource', ['$resource', '$q', homeResource]);

	function homeResource($resource, $q) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

	    var MyComments =  $resource('api/user/:id/comment', {}, {
		    query: {
		        method: 'GET',
		        params: {},
		        isArray: true,
		        transformResponse: function(data, header){
		          //Getting string data in response
		          var jsonData = JSON.parse(data); //or angular.fromJson(data)
				  var comments = [];

		          angular.forEach(jsonData, function(comment){
		            comments.push(comment);
		          });

		          return comments;
		        }
		    }
	    });

		var MyVotes = $resource('api/user/:id/vote', {limit:'@limit'}, {
			query: {
		        method: 'GET',
		        params: {},
		        isArray: true,
		        ignoreLoadingBar: true
			}
		});


	    var TopMotion = $resource('api/motion/', {}, { 
			query: {
		        method: 'GET',
		        params: {
		        	rank_greater_than: 0,
		        	take: 1
		        },
		        isArray: true,
				ignoreLoadingBar: true
			}
		});

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/
	    
		function getMyComments(id) {
			return MyComments.query({id:id}).$promise.then(function(results) {
				return results;
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


		function getTopMotion() {
			return TopMotion.get().$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			getMyComments: getMyComments,
			getMyVotes: getMyVotes,
			getTopMotion: getTopMotion
		}
	}

}());