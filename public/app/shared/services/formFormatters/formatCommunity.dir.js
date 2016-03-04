(function() {
	
	angular
		.module('iserveu')
		.directive('formatCommunity', formatCommunity);

	function formatCommunity() {

		console.log('foo');

		return {
			require: 'ngModel',
			link: function(scope, el, attrs, ngModelCtrl) {
				ngModelCtrl.$formatters.push(function(data) {

					if (!data)
						return "What community do you reside in?";
					else
						for(var i in scope.communities) {
							if (data === scope.communities[i].id)
							return scope.communities[i].name;
						};

				});
			}
		}


	}

})();