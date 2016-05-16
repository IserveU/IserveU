(function() {
	
	angular
		.module('iserveu')
		.directive('topComments', ['homeResource', 'homePageService', topComments]);

	function topComments(homeResource, homePageService) {

		function topCommentsController() {
			
			var self = this;

			self.loading = true;

			function init() {

				if(homePageService.topComments.length > 0) {
					self.loading = false;
					self.commentList = homePageService.topComments;
					return true;
				}

				homeResource.getTopComments().then(function(results){
					self.loading = false;
					self.commentList = homePageService.topComments = results;
				}, function(error){
					self.loading = false;
					throw new Error("Unable to retrieve top comments.");
				});
			}

			init();

		}


		return {
			controller: topCommentsController,
			controllerAs: 'topComments',
			templateUrl: 'app/components/home/widgets/topComments.tpl.html'
		}


	}

})();