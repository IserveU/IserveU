(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFabToolbar', motionFabToolbar);

	function motionFabToolbar($state, $stateParams, motion, motionObj, ToastMessage){

		function motionFabToolbarController() {

			this.deleteMotion = deleteMotion;

	        function deleteMotion() {
	        	ToastMessage.destroyThis("motion", function(){
                    motion.deleteMotion($stateParams.id).then(function(r) {
                        $state.go('home');
                        motionObj.getMotions();
                    }, function(e) { ToastMessage.report_error(e); });
	        	});
	        };
		}




		return {
			controller: motionFabToolbarController,
			controllerAs: 'fab',
			templateUrl: 'app/components/motion/components/motion-fab/motion-fab-toolbar.tpl.html'
		}

	}

})();