(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('formatAddress', formatAddress);

  	 /** @ngInject */
	function formatAddress($filter, community) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				function toTitleCase(str)
				{
				    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
				}

      			ngModelController.$formatters.push(function(data) {

      				var address = '';

      				if( !data.street_name )
      					return "Enter your address";
      				else if ( !data.unit_number && !data.street_number)
      					address = toTitleCase(data.street_name);
      				else if( !data.unit_number )
      					address = data.street_number + ' ' + toTitleCase(data.street_name);
      				else if ( !data.street_number )
      					address = "Unit #" + data.unit_number + ' ' + toTitleCase(data.street_name);
      				else
						address = data.unit_number + '-' + data.street_number + ' ' + toTitleCase(data.street_name);
      				
					if(data.community_id)
						for(var i in scope.communities)
							if (data.community_id === scope.communities[i].id)
								return address + ', ' + scope.communities[i].name;
					else return address;

			    });
			}
		}
	}

}());

