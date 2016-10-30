(function() {

	angular
		.module('iserveu')
		.service('incompleteProfileService', ['userResource', incompleteProfileService]);

	function incompleteProfileService(userResource) {

		this.check = checkUserForNullFields;


		/**
		*	This function checks the passed in user whether or not they are missing
		*	these required fields. This should probably be handled by the API
		*   to be honest. But we can reconcile it nonetheless.
		*/
		function checkUserForNullFields (userData) {

			userData = userData ? userData : userResource.self;

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