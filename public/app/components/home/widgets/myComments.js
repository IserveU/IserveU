(function() {
	
	angular
		.module('iserveu')
		.directive('myComments', ['$rootScope', 'homeResource', myComments]);

	function myComments($rootScope, homeResource) {

		function myCommentsController() {
			
			var self = this;

			self.loading = true;
			self.commentList = {};

			(function init() {
				
				if(!$rootScope.authenticatedUser) {
					self.loading = false;
					return false;
				}

				homeResource.getMyComments().then(function(results) {
					self.loading = false;
					self.commentList = results.data;
				}, function(error) {
					self.loading = false;
					throw new Error("Unable to retreive my comments.");
				});

			})();
		}


		return {
			controller: myCommentsController,
			controllerAs: 'myComments',
			templateUrl: 'app/components/home/widgets/myComments.tpl.html'
		}


	}

})();