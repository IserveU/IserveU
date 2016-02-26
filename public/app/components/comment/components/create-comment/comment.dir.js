(function(){

	'use strict';

	angular
		.module('iserveu')
		.directive('commentOnMotion', commentOnMotion);

	/** @ngInject */
	function commentOnMotion($stateParams, commentObj, voteObj, motionObj) {

		function commentController($scope) {

			var vm = this;

			vm.obj = commentObj;
			vm.vote = voteObj;

			$scope.$watch(voteObj.user, function(vote) {
				vm.vote.user = vote;
			});
		}

		return {
			controller: commentController,
			controllerAs: 'c',
			templateUrl: 'app/components/comment/partials/comment.tpl.html'
		}
	}

})();