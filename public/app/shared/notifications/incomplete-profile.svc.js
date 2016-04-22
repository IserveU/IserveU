(function() {
	
	angular
		.module('iserveu')
		.service('incompleteProfileService', ['user', incompleteProfileService]);

	function incompleteProfileService(user) {

		this.check = function(userData) {

			userData = userData ? userData : user.self;

			for( var i in userData )
				if ( 
					// i === 'date_of_birth' || not included in localized_economies
					//  i === 'street_name'   ||
					//  i === 'postal_code'   ||
					 i === 'community_id' )

			return (userData[i] === null ? true : false);

		}
	}

})();