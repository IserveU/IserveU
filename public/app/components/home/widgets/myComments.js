(function() {
	
	angular
		.module('iserveu')
		.directive('myComments', ['homeResource', 'homePageService', myComments]);

	function myComments(homeResource, homePageService) {

		function myCommentsController() {
			
			var self = this;

			self.loading = true;

			function init() {
				
				if(homePageService.myComments.length > 0) {
					self.loading = false;
					self.commentList = homePageService.myComments;
					return true;
				}

				homeResource.getMyComments().then(function(results) {
					self.loading = false;
					self.commentList = homePageService.myComments = results;
				}, function(error) {
					self.loading = false;
					throw new Error("Unable to retreive my comments.");
				});

			}

			init();

		}


		return {
			controller: myCommentsController,
			controllerAs: 'myComments',
			templateUrl: 'app/components/home/widgets/myComments.tpl.html'
		}


	}

})();