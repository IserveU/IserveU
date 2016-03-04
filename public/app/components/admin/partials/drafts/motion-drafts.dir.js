(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionDrafts', motionDrafts);

	 /** @ngInject */
	function motionDrafts(motionObj, UserbarService){

		function motionDraftController() {

			this.motionObj = motionObj;

		};

		return {

			controller: motionDraftController,
			controllerAs: 'draft',
			templateUrl: 'app/components/admin/partials/drafts/motion-drafts.tpl.html'

		}

	}

})();