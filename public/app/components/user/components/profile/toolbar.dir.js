(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('profileToolbar', profileToolbar);

	function profileToolbar() {


		return {
			templateUrl: 'app/components/user/components/profile/toolbar.tpl.html'
		}

	}

})();