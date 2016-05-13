(function() {
	
	angular
		.module('iserveu')
		.directive('betaMessage', betaMessage);

	function betaMessage() {

		return {
			templateUrl: 'app/components/navigation/betaMessage.tpl.html'
		}


	}

})();