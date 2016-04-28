(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$provide',

  	 /** @ngInject */
	function($provide){

		$provide.decorator('taOptions', ['taRegisterTool', '$delegate', function(taRegisterTool, taOptions){

			taOptions.forceTextAngularSanitize = false; 
	        return taOptions;
	    }]);

	}]);


})();