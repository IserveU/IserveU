(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('commentVoteResource', [
			'$resource',
			'$q',
			'$http',
			'utils',
		commentVoteResource]);

	function commentVoteResource($resource, $q, $http, utils) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

	    var _userCommentVoteIndex = {};

		var CommentVote = $resource('api/comment_vote/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    var SaveCommentVote = $resource('api/comment/:id/comment_vote',  {ignoreLoadingBar:'@true'});

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/

		function getUserCommentVotes(data) {
			
			console.log('getUserCommentVotes@commentVoteResource');

			if(!utils.objectIsEmpty( _userCommentVoteIndex )) {
				console.log('_userCommentVoteIndex isEmpty@commentVoteResource');
				return $q.when({data: _userCommentVoteIndex });
			}

			return $http({
				method: 'GET',
				url: 'api/user/'+data.user_id+'/comment_vote',
				params: {
					ignoreLoadingBar: true
				}
			}).success(function(results){
				console.log('_userCommentVoteIndex Success!');
				_userCommentVoteIndex = results.data || results;
				return results;
			}).error(function(error){
				return error.data || error;
			})
		}

		function saveCommentVote(data) {
			return SaveCommentVote.save({id:data.comment_id}, {position: data.position})
					.$promise.then(successHandler).catch(errorHandler);
		}

		function updateCommentVote(data) {
			return CommentVote.update({id:data.id}, { position: data.position }).$promise.then(successHandler).catch(errorHandler);
		}

		function deleteCommentVote(data) {
			return CommentVote.delete({id:data.id}).$promise.then(function(results){
				successHandler(results, data.id)
			}).catch(errorHandler);
		}

		function successHandler(res, id) {
			var body = res.data || res;

			if(!utils.objectIsEmpty( _userCommentVoteIndex )) {

				for(var i in _userCommentVoteIndex) {
					if( body.id === _userCommentVoteIndex[i].id ) {
						if(id !== 'undefined') {
							delete _userCommentVoteIndex[i];
						} else {
							_userCommentVoteIndex[i] = body;
						}
					}				
				}
			}

			return body;
		}

		function errorHandler(error) {
			return $q.reject(error);
		}

		return {
			_userCommentVoteIndex: _userCommentVoteIndex,
			getUserCommentVotes: getUserCommentVotes,
			saveCommentVote: saveCommentVote,
			updateCommentVote: updateCommentVote,
			deleteCommentVote: deleteCommentVote
		}
	}

}());