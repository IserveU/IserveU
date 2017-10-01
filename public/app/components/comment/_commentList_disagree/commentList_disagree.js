(function() {
	
	angular
		.module('iserveu')
		.directive('commentListDisagree', commentListDisagree);

	function commentListDisagree() {

		function commentListDisagreeController() {
			
			// do something

		}


		return {
			controller: commentListDisagreeController,
			controllerAs: 'commentListDisagree',
			templateUrl: 'app/components/commentList_disagree/commentList_disagree.tpl.html'
		}


	}

})();