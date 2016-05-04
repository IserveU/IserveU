(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionDrafts', ['motion', 'errorHandler', motionDrafts]);

	 /** @ngInject */
	function motionDrafts(motion, errorHandler){

		function motionDraftController() {

			var self = this; // global context for 'this'

			motion.getMotionByStatus([0,1]).then(function(r){
				self.motions = r.data.data;
			}, function(e){
				errorHandler(e);
			});

		};

		return {

			controller: motionDraftController,
			controllerAs: 'draft',
			templateUrl: 'app/components/admin.dash/drafts/motion-drafts.tpl.html'

		}

	}

})();