(function() {

	angular
		.module('iserveu')
		.directive('topComments', ['homeResource', topComments]);

	function topComments(homeResource) {

		function topCommentsController() {

			var self = this;

			self.loading = true;
			self.motionList = {};

			(function init() {
				homeResource.getTopComments().then(function(results){
					self.loading = false;
					self.commentList = results.data;
				}, function(error){
					self.loading = false;
					throw new Error("Unable to retrieve top comments.");
				});
			})();
		}


		return {
			controller: topCommentsController,
			controllerAs: 'topComments',
			templateUrl: 'app/components/home/widgets/topComments.tpl.html'
		}


	}

})();