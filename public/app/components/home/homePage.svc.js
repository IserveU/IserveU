(function() {
	
	angular
		.module('iserveu')
		.factory('homePageService', homePageService);

	function homePageService() {

		var homePageService = {

			myComments: [],

			myVotes: [],

			topComments: [],

			topMotions: []

		}

		return homePageService;
	}


})();

