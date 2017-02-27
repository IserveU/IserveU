(function() {
	
  'use strict';

	angular
		.module('iserveu')
		.directive('betaMessage',[
      'settings',
      betaMessage
  ]);
	function betaMessage(settings) {
      
  		function betaMessageController() {
        this.text = settings.get('betaMessage.text');
  		}
      
  		return {
  			controller: betaMessageController,
  			controllerAs: 'message',
        templateUrl: 'app/components/navigation/betaMessage.tpl.html'
  		}
    }

})();