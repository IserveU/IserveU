(function() {
	
	angular
		.module('iserveu')
		.directive('betaMessage', betaMessage);

	function betaMessage() {

		return {
			templateUrl: 'app/components/nav/betaMessage/betaMessage.tpl.html'
		}


	}

})();