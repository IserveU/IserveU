(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('focusInput', focusInput);

  	 /** @ngInject */
	function focusInput($timeout) {

	    return {
	        restrict : 'A',
	        link : function($scope,$element,$attr) {
	            $scope.$watch ($attr.focusInput,function(_focusVal) {
	                $timeout(function() {
	                    _focusVal ? $element.focus() :
	                        $element.blur();
	                });
	            });
	        }
	    }


	}

})();