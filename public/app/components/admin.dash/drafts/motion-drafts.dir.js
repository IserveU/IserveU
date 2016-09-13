(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionDrafts', ['motionResource', 'errorHandler', motionDrafts]);

	 /** @ngInject */
	function motionDrafts(motionResource, errorHandler){

		function motionDraftController() {

			var self = this; // global context for 'this'

			motionResource.getDrafts(['draft', 'review']).then(function(r){
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