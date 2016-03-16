(function() {
	
	angular
		.module('iserveu')
		.directive('isuInlineSave', isuInlineSave);

	function isuInlineSave() {

		function isuInlineSaveController() {
			
			// do something

		}


		return {
			controller: isuInlineSaveController,
			transclude: true,
			scope: {
				'isuEdit': '&',
				'isuSave': '&',
				'isuDestroy': '&',
				'isuSaving': '=',
				'isuSuccess': '=',
				'isuShow': '='
			},
			templateUrl: 'app/shared/directives/inline-save/buttons.tpl.html'
		}


	}

})();