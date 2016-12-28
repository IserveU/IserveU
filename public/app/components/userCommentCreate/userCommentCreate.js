(function() {
	
	angular
		.module('iserveu')
		.directive('userCommentCreate', ['Comment', 'utils', userCommentCreate]);

	function userCommentCreate(Comment, utils) {

		function userCommentCreateController($scope, $attrs) {
			
			var self = this; // global context for this

		}

		return {
			controller: ['$scope', '$attrs', userCommentCreateController],
			controllerAs: 'userCommentCreate',
			templateUrl: 'app/components/userCommentCreate/userCommentCreate.tpl.html'
		}


	}

})();