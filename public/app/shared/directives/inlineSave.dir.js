(function() {
	
	angular
		.module('iserveu')
		.directive('isuInlineSave', isuInlineSave);

	function isuInlineSave() {

		return {
			transclude: true,
			scope: {
				'isuEdit': '&',
				'isuSave': '&',
				'isuDestroy': '&',
				'isuSaving': '=',
				'isuSuccess': '=',
				'isuShow': '='
			},
			template: ['<div ng-transclude></div><div class="inline-save-icons" layout="row">',
					   '<md-icon class="mdi mdi-pencil" ng-click="isuEdit()" ng-hide="isuShow"></md-icon>',
					   '<md-icon class="mdi mdi-content-save" ng-click="isuEdit(); isuSave()" ng-show="isuShow"></md-icon>',
					   '<md-progress-circular md-diameter="35px" md-mode="indeterminate" ng-show="isuSaving && !isuSuccess">',
					   '</md-progress-circular>',
					   '<md-icon class="mdi mdi-check" ng-show="isuSuccess && !isuShow"></md-icon>',
					   '<md-icon class="mdi mdi-delete" ng-click="isuDestroy()"></md-icon>',
					   '</div>'].join('')
		}


	}

})();