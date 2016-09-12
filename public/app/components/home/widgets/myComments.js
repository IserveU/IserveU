(function() {
	
	angular
		.module('iserveu')
		.directive('myComments', ['homeResource', myComments]);

	function myComments(homeResource) {

		function myCommentsController() {
			
			var self = this;

			self.loading = true;
			self.commentList = {};

			(function init() {
				
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