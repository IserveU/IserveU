(function() {
	
	angular
		.module('iserveu')
		.directive('registerForm', [
			'communityResource', 
			'registerService',
		registerForm]);

	function registerForm(communityResource, registerService) {
    
    
    function register() {
			var self = this;
			
			communityResource.getCommunities().then(function(results) {

				self.communityIndex = results.data.data; 
			});

      self.service = registerService;
      self.values = registerService.values;

		}
  
		return {
			controller: register,
			controllerAs: 'register',
			templateUrl: 'app/components/register/register.tpl.html'
		}

	}

})();