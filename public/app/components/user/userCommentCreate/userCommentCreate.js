(function() {
	
	angular
		.module('app.user')
		.directive('userCommentCreate', userCommentCreate);

	function userCommentCreate() {

		function userCommentCreateController() {
			
			var self = this; // global context for this

		}

		return {
			controller: userCommentCreateController,
			controllerAs: 'userCommentCreate',
			templateUrl: 'app/components/userCommentCreate/userCommentCreate.tpl.html'
		}


	}

})();