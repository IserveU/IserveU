(function() {
	
	angular
		.module('iserveu')
		.service('incompleteProfileService', incompleteProfileService);

	function incompleteProfileService(user) {

		this.check = function(userData) {

			userData = userData ? userData : user.self;

			for( var i in userData )
				if ( i === 'date_of_birth' ||
					 i === 'street_name'   ||
					 i === 'postal_code'   ||
					 i === 'community_id' )

			return (userData[i] === null ? true : false);

		}
	}

})();