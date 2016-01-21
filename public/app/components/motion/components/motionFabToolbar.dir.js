(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFabToolbar', motionFabToolbar);

	function motionFabToolbar(){

		console.log('foo');
		return {
			templateUrl: 'app/components/motion/partials/motion-fab-toolbar.tpl.html'
		}

	}

})();