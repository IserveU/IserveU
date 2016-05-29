(function() {
	
	angular
		.module('iserveu')
		.directive('topMotions', ['homeResource', 'homePageService', topMotions]);

	function topMotions(homeResource, homePageService) {

		function topMotionsController() {
			
			var self = this;

			self.loading = true;

			function init() {

				if(homePageService.topMotions.length > 0){
					self.loading = false;
					self.motionList = homePageService.topMotions;
					return true;
				}

				homeResource.getTopMotion().then(function(results) {
					self.loading = false;
					self.motionList = homePageService.topMotions = results.data;
				}, function(error) {
					self.loading = false;
					throw new Error("Unable to retrieve top motion.");
				});
			}

			init();

		}


		return {
			controller: topMotionsController,
			controllerAs: 'topMotions',
			templateUrl: 'app/components/home/widgets/topMotions.tpl.html'
		}


	}

})();