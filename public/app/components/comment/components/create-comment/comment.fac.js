(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('comment', ['$resource', '$q', comment]);

	/** @ngInject */
	function comment($resource, $q) {

		var Comment = $resource('api/comment/:id', {}, {
	        'update': { 
	        	method:'PUT',
		        ignoreLoadingBar: true 
	        },
        	query: {
			    method: 'GET',
		        params: {},
		        isArray: true,
		        ignoreLoadingBar: true
	    	}
	    });

	    var Restore = $resource('api/comment/:id/restore');

	    var MotionComment = $resource('api/motion/:id/comment', {}, 
		    {
		    	query: {
				    method: 'GET',
			        params: {},
			        isArray: true,
			        ignoreLoadingBar: true,
		    	}
		    }
    	);

	    var MyComments =  $resource('api/user/:id/comment', {}, {
	      query: {
	        method: 'GET',
	        params: {},
	        isArray: true,
	        ignoreLoadingBar: true,
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

	    var motion_comments = [];

		function getComment() {
			return Comment.query().$promise.then(function(results) {
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}

		function saveComment(data) {
			return Comment.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateComment(data) {
			return Comment.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteComment(id) {
			return Comment.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function restoreComment(id){
			return Restore.get({id:id}).$promise.then(function(success) {
				return success;
			}, function(error){
				return $q.reject(error);
			})
		}

		function getMyComments(id) {
			return MyComments.query({id:id}).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionComments(id) {
			return MotionComment.get({id:id}).$promise.then(function(result) {
				motion_comments = result;
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function saveLocalMotionComments(data) {
			motion_comments = data;
		}


		return {
			saveComment: saveComment,
			updateComment: updateComment,
			deleteComment: deleteComment,
			restoreComment: restoreComment,
			getComment: getComment,
			getMyComments: getMyComments,
			getMotionComments: getMotionComments,
			saveLocalMotionComments: saveLocalMotionComments,
		}
	}
})();