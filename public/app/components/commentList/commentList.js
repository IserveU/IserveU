(function() {
	
	angular
		.module('iserveu')
		.directive('commentList', commentList);

	function commentList() {


		function commentListController() {
			
			// do something

		}


		return {
			controller: commentListController,
			controllerAs: 'commentList',
			templateUrl: 'app/components/commentList/commentList.tpl.html'
		}


	}

})();