(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('showFooter', footer);

	function footer() {

		return {
			template: ['<footer layout="row" layout-align="end end" ng-cloak>',
    				  		'<md-button class="md-primary md-raised" terms-and-conditions', 
    				  		'ng-click="ctrl.showTermsAndConditions($event, false)" flex-sm="50" flex-md="25" flex-gt-md="25">',
        			  		'Terms &amp; Conditions',
    				  		'</md-button>',
					    '</footer>'].join('')
		}

	}

}());