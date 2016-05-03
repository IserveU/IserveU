(function() {
	
	angular
		.module('iserveu')
		.service('incompleteProfileService', ['user', incompleteProfileService]);

	function incompleteProfileService(user) {

		this.check = checkUserForNullFields;


		function checkUserForNullFields (userData) {

			userData = userData ? userData : user.self;

			 var nullField = false;
  
  
		    angular.forEach(userData, function(e, o) {
		      
		      if(o === 'date_of_birth' || o === 'community_id' || o === 'postal_code') {
		        
		        if (!e || e === null)
		          nullField = true;

		      }
		      
		    });
		  
		    return nullField;





		}
	}

})();