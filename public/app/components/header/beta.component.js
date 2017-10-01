(function() {
	
  'use strict';

	angular
		.module('app.header')
		.component('betaMessage',  {
    controller: betaMessageController,
    template: `
      <div class="notification" ng-show="$ctrl.showBetaMessage" 
        md-colors="{background: 'primary-700'}" ng-cloak>
        <div class="notification__beta">
          {{ $ctrl.text }}  
        </div>
      </div>`
  });

  betaMessageController.$inject = ['Settings'];

  function betaMessageController(Settings) {
    this.showBetaMessage = Settings.get('betaMessage.on');
    this.text = Settings.get('betaMessage.text');
  }

})();