(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionDrafts', motionDrafts);

	function motionDrafts(motionObj, UserbarService){


		function motionDraftController() {

            UserbarService.setTitle('Drafts');

			this.motionObj = motionObj;

			this.item = 'adsf';

			this.getDrafts = function(motion) {
				console.log(motion);

			}

		};


		function motionDraftLink() {
            UserbarService.title = 'Motion Drafts';

		};


		return {

			controller: motionDraftController,
			controllerAs: 'c',
			templateUrl: 'app/components/motion/components/motion-drafts/motion-drafts.tpl.html'

		}

	}

})();