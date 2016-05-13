(function() {
	
	angular
		.module('iserveu')
		.directive('formatCommunity', formatCommunity);

	function formatCommunity() {

		return {
			require: 'ngModel',
			link: function(scope, el, attrs, ngModelCtrl) {

				var communityId;

				var unbindScope = scope.$watch('communities.index', function(value) {
					if(value && value.length > 0) {
						for(var i in value) {
							if(communityId == value[i].id) {
								ngModelCtrl.$setViewValue(value[i].name); 
								ngModelCtrl.$commitViewValue();
								ngModelCtrl.$render();
								unbindScope();
							}
						}
					}
				});


				ngModelCtrl.$formatters.push(function(data) {

					if (!data)
						return "What community do you reside in?";
					else 
						communityId = data;
					
				});
			}
		}


	}

})();