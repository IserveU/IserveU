(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFabToolbar', motionFabToolbar);

	 /** @ngInject */
	function motionFabToolbar($state, $stateParams, motion, motionObj, fabLink, ToastMessage){

		function motionFabToolbarController() {

			this.deleteMotion = deleteMotion;
			this.isOpen = false;

	        function deleteMotion() {
	        	ToastMessage.destroyThis("motion", function(){
                    motion.deleteMotion($stateParams.id).then(function(r) {
                        $state.go('home');
                        motionObj.getMotions();
                    }, function(e) { ToastMessage.report_error(e); });
	        	});
	        };
		}

		function motionFabLink(scope,el,attrs) {
			fabLink(el);
		}


		return {
			controller: motionFabToolbarController,
			controllerAs: 'fab',
			link: motionFabLink,
			templateUrl: 'app/components/motion/components/motion-fab/motion-fab-toolbar.tpl.html'
		}

	}

})();