(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('spinner', spinner);

  	 /** @ngInject */
	function spinner(settings) {

		// TODO: make loading circle changeable from settings array

		return {
			transclude: true,
			scope: {
				'name': '=',
				'onLoaded': '&',
				'onHide': '=',
				'formName': '='
			},
			template: ['<md-button ng-disabled="formName.$invalid" type="submit">',
						'<span ng-hide="onHide">{{name}}</span>',
            			'<md-icon md-svg-src="/themes/default/loading.svg" ng-show="onHide">',
            			'</md-icon></md-button>'].join('')
		}

	}



})();