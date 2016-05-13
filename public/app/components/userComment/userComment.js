(function() {
	
	angular
		.module('iserveu')
		.directive('userComment', userComment);

	function userComment() {

		function userCommentController() {
			
			// do something

		}


		return {
			controller: userCommentController,
			controllerAs: 'userComment',
			templateUrl: 'app/components/userComment/userComment.tpl.html'
		}


	}

})();