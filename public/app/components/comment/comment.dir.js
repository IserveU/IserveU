(function(){

	'use strict';

	angular
		.module('iserveu')
		.directive('commentOnMotion', commentOnMotion);

	function commentOnMotion($stateParams, commentObj, motionObj) {

		function commentController($scope) {

			var vm = this;

			vm.obj = commentObj;
			vm.commentText = '';
			vm.userVote = '';
			vm.closed = false;

			vm.close = function () {
				vm.closed = !vm.closed;
			}

			$scope.$watch( function() { return motionObj.getMotionObj($stateParams.id); },
				function(motion) {
	                if( motion != null )
	                    vm.userVote   = motion.user_vote;
				}, true
			);

		}

		return {
			controller: commentController,
			controllerAs: 'c',
			templateUrl: 'app/components/comment/partials/comment-production.tpl.html'
		}
	}

})();