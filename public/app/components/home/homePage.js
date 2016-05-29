(function() {
	
	angular
		.module('iserveu')
		.directive('homePage', homePage);

	function homePage() {

		function homePageController() {
			
			// do something

		}


		return {
			controller: homePageController,
			controllerAs: 'homePage',
			templateUrl: 'app/components/home/homePage.tpl.html'
		}


	}

})();