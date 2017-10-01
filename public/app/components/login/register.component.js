(function() {
	
	angular
		.module('app.login')
		.component('registerComponent', {
      controller: RegisterController,
      controllerAs: 'register',
      require: { parent: '^^loginComponent' },
      templateUrl: 'app/components/login/register.tpl.html'
    });

  RegisterController.$inject = ['Register', 'CommunityResource'];

  function RegisterController(Register, CommunityResource) {
    var self = this;
    
    CommunityResource.getCommunities().then(function(results) {
      self.communityIndex = results.data.data; 
    });

    self.service = Register;
    self.values = Register.values; 
  }



})();