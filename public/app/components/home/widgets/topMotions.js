(function() {
	
	angular
		.module('iserveu')
		.directive('topMotions', ['homeResource', 'utils', topMotions]);

	function topMotions(homeResource, utils) {

		function topMotionsController() {
			
			var self = this;

			self.loading = true;
			self.motionList = {};

			(function init() {
				homeResource.getTopMotion().then(function(results) {
					var motions = results.data.data;					
					self.loading = false;
					self.motionList = utils.objectIsEmpty(motions) ? false : motions;
				}, function(error) {
					self.loading = false;
					throw new Error("Unable to retrieve top motion.");
				});
			})();
		}


		return {
			controller: topMotionsController,
			controllerAs: 'topMotions',
			templateUrl: 'app/components/home/widgets/topMotions.tpl.html'
		}


	}

})();