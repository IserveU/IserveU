(function() {
	
	angular
		.module('iserveu')
		.directive('myVotes', ['homeResource', 'homePageService', myVotes]);

	function myVotes(homeResource, homePageService) {

		function myVotesController() {
			
			var self = this;

			self.loading = true;

			function init() {

				if(homePageService.myVotes.length > 0) {
					self.loading = false;
					self.voteList = homePageService.myVotes;
					return true;
				}

				homeResource.getMyVotes().then(function(results){
					self.loading = false;
					self.voteList = homePageService.myVotes = results.data;
				}, function(error) {
					self.loading = false;
					throw new Error("Unable to retrieve my votes.");
				});
			}

			init();

		}


		return {
			controller: myVotesController,
			controllerAs: 'myVotes',
			templateUrl: 'app/components/home/widgets/myVotes.tpl.html'
		}


	}

})();