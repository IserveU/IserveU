(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('spinner', spinner);

	function spinner(settings) {

		// TODO: make loading circle changeable from settings array

		return {
			transclude: true,
			scope: {
				'name': '=',
				'onLoaded': '&',
				'onHide': '=',
			},
			template: ['<md-button type="submit">',
						'<span ng-hide="onHide">{{name}}</span>',
            			'<md-icon md-svg-src="/themes/default/loading.svg" ng-show="onHide">',
            			'</md-icon></md-button>'].join('')
		}

	}



})();