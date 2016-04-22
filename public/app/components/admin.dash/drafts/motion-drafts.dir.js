(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionDrafts', ['motionObj', 'UserbarService', motionDrafts]);

	 /** @ngInject */
	function motionDrafts(motionObj, UserbarService){

		function motionDraftController() {

			this.motionObj = motionObj;

		};

		return {

			controller: motionDraftController,
			controllerAs: 'draft',
			templateUrl: 'app/components/admin.dash/drafts/motion-drafts.tpl.html'

		}

	}

})();