(function() {
	
	angular
		.module('iserveu')
		.directive('motionVoteStatusbar', motionVoteStatusbar);

	function motionVoteStatusbar() {

		function motionVoteStatusbarController() {
			
			// do something

		}


		return {
			controller: motionVoteStatusbarController,
			controllerAs: 'motionVoteStatusbar',
			templateUrl: 'app/components/motionVoteStatusbar/motionVoteStatusbar.tpl.html'
		}


	}

})();