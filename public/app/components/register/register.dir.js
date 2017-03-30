(function() {
	
	angular
		.module('iserveu')
		.directive('registerForm', [
			'communityResource', 
			'loginService',
		registerForm]);

	function registerForm(communityResource, loginService) {

		return {
			controller: function() {
				var self = this;
				
				communityResource.getCommunities().then(function(results) {

					self.communityIndex = results.data.data; 
				});
			},
			controllerAs: 'register',
			templateUrl: 'app/components/register/register.tpl.html',
			link: function (scope, el, attrs) {
				scope.$on('$destroy', function() {
					loginService.creating = false;
				});
			}
		}

	}

})();