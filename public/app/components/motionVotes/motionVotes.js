(function() {
	
	angular
		.module('iserveu')
		.directive('motionVotes', motionVotes);

	function motionVotes() {

		function motionVotesController() {
			
			// do something

		}


		return {
			controller: motionVotesController,
			controllerAs: '',
			templateUrl: 'app/components/motionVotes/motionVotes.tpl.html'
		}


	}

})();