(function() {
	
	angular
		.module('iserveu')
		.directive('formatPublic', formatPublic);

	function formatPublic() {

		return {
			require: 'ngModel',
			link: function(scope, el, attrs, ngModelCtrl) {

				console.log('asfasfd');

				ngModelCtrl.$formatters.push(function(data) {

					if (data === 1)
						return "Public";
					else
						return "Private";
				});
			}
		}


	}

})();