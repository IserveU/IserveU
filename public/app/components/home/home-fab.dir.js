(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('homeFab', ['fabLink', homeFab]);

	/** @ngInject */
	function homeFab(fabLink) {
		return {
			controller: function() {
				this.isOpen = false;
			},
			controllerAs: 'fab',
			link: function(scope, el, attrs){
				fabLink(el);
			},
			template: [ '<md-fab-speed-dial has-permission="administrate-motions"',
						'class="md-scale fab-tool-animate" md-open="fab.isOpen"',
						'ng-mouseenter="fab.isOpen=true" ng-mouseleave="fab.isOpen=false"',
						'md-hover-full md-direction="down"',
						 'style="position:fixed; right: 15px">',
						  '<md-fab-trigger>',
						    '<md-button aria-label="menu" class="md-fab md-primary">',
						      '<md-icon class="mdi mdi-menu"></md-icon>',
						    '</md-button>',
						  '</md-fab-trigger>',
						  '<md-fab-actions>',
						    '<md-button aria-label="Edit" class="md-fab md-raised md-mini" ui-sref="edit-home">',
						      '<md-icon class="mdi mdi-pencil" aria-label="Edit"></md-icon>',
						    '</md-button>',
						  '</md-fab-actions>',
						'</md-fab-speed-dial>'].join('')
		}

	}


})();