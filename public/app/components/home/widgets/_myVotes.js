(function() {

	angular
		.module('iserveu')
		.directive('myVotes', ['$rootScope', 'homeResource', 'motionIndex', myVotes]);

	function myVotes($rootScope, homeResource, motionIndex) {

		function myVotesController() {

			var self = this;

			self.loading  = true;
			self.voteList = {};
			self.motionIndex = motionIndex;

			(function init() {

				if(!$rootScope.authenticatedUser) {
					self.loading = false;
					return false;
				}

				homeResource.getMyVotes().then(function(results){
					self.loading  = false;
					self.voteList = results.data.data;
				}, function(error) {
					self.loading = false;
					throw new Error("Unable to retrieve my votes.");
				});
			})();

		}


		return {
			controller: myVotesController,
			controllerAs: 'myVotes',
			templateUrl: 'app/components/home/widgets/myVotes.tpl.html'
		}


	}

})();