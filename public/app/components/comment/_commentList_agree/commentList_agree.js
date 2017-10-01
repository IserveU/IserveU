(function() {
	
	angular
		.module('iserveu')
		.directive('commentListAgree', commentListAgree);

	function commentListAgree() {

		function commentListAgreeController() {
			
			// do something

		}

		return {
			controller: commentListAgreeController,
			controllerAs: 'commentListAgree',
			templateUrl: 'app/components/commentList_agree/commentList_agree.tpl.html'
		}


	}

})();