(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFabToolbar', motionFabToolbar);

	function motionFabToolbar(){


		return {
			templateUrl: 'app/components/motion/partials/motion-fab-toolbar.tpl.html'
		}

	}

})();