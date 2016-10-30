
(function() {

	angular
		.module('iserveu')
		.directive('homePage', ['$state', homePage]);

	function homePage($state) {

		function homePageController() {
			/* function exports */
			this.edit = edit;

			function edit(home) {
				$state.go('edit-page', {id: home.slug});
			}
		}

		return {
			controller: homePageController,
			controllerAs: 'homePage',
			templateUrl: 'app/components/home/homePage.tpl.html'
		}


	}

})();