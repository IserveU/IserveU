(function() {
	
	angular
		.module('iserveu')
		.directive('homeIntroduction', ['SETTINGS_JSON', homeIntroduction]);

	function homeIntroduction(SETTINGS_JSON) {

		function homeIntroductionController() {
			
			this.introduction = SETTINGS_JSON.home.introduction;

		}


		return {
			controller: homeIntroductionController,
			controllerAs: 'homeIntroduction',
			templateUrl: 'app/components/home/widgets/homeIntroduction.tpl.html'
		}


	}

})();