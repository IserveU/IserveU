(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('spinner', ['settings', spinner]);

  	 /** @ngInject */
	function spinner(settings) {

		// TODO: make loading circle changeable from settings array

		return {
			restrict: 'E',
			transclude: true,
			scope: {
				'name': '=',
				'onLoaded': '&',
				'primaryAction': '&',
				'onHide': '=',
				'formName': '=',
				'secondaryBtn': '='
			},
			template: ['<md-button ng-disabled="formName.$invalid" type="submit" ng-click="primaryAction()">',
						'<span ng-hide="onHide">{{name}}</span>',
            			'<md-icon md-svg-src="/themes/default/loading.svg" ng-show="onHide">',
            			'</md-icon></md-button>',
            			'<md-button ng-if="secondaryBtn" ng-click="secondaryBtn.control()">',
            			'{{secondaryBtn.name}}',
            			'</md-button>'
            			].join('')
		}

	}



})();