(function() {
	
	angular
		.module('iserveu')
		.directive('formatBirthday', birthday);

	function birthday() {

		return {
		    require: 'ngModel',
		    link: function(scope, element, attrs, ngModelController) {

				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				});

      			ngModelController.$formatters.push(function(data) {
					var d = new Date(data);
					return new Date(d.setTime( d.getTime() + d.getTimezoneOffset()*60000 ));
			    });
		    }
		};
	}

})();