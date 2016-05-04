(function(){



angular
  .module('iserveu')
  .config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {

  	 cfpLoadingBarProvider.includeSpinner = false;
  	 cfpLoadingBarProvider.latencyThreshold = 500;

  }]);


})();