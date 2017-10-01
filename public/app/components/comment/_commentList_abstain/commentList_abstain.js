(function() {
	
	angular
		.module('iserveu')
		.directive('commentListAbstain', commentListAbstain);

	function commentListAbstain() {

		function commentListAbstainController() {
			
			// do something

		}


		return {
			controller: commentListAbstainController,
			controllerAs: 'commentListAbstain',
			templateUrl: 'app/components/commentList_abstain/commentList_abstain.tpl.html'
		}


	}

})();