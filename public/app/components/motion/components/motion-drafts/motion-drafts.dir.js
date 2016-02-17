(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionDrafts', motionDrafts);

	function motionDrafts(motionObj, UserbarService){


		console.log('motiondrafts');


		function motionDraftController() {

			this.motionObj = motionObj;

			this.getDrafts = function(motion) {
				console.log(motion);
			}
		};

		return {

			controller: motionDraftController,
			controllerAs: 'draft',
			templateUrl: 'app/components/motion/components/motion-drafts/motion-drafts.tpl.html'

		}

	}

})();