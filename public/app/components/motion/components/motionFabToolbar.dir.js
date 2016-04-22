(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFabToolbar', ['$state', '$stateParams', 'motion', 'motionObj', 'fabLink', 'ToastMessage', 
		motionFabToolbar]);

	function motionFabToolbar($state, $stateParams, motion, motionObj, fabLink, ToastMessage){

		return {
			link: function(scope, el, attrs) { 
				fabLink(el); 
			},
			controller: function() {
				this.isOpen = false;
		        this.deleteMotion = function() {
		        	ToastMessage.destroyThis("motion", function(){

	                    motion.deleteMotion($stateParams.id).then(function(r) {
	                    
	                        $state.go('home');
	                        motionObj.getMotions();
	                    
	                    }, function(e) { ToastMessage.report_error(e); });
		        	
		        	});
		        };
			},
			controllerAs: 'fab',
			template: [  '<md-fab-speed-dial has-permission="administrate-motions"',
						 'class="md-scale fab-tool-animate" md-open="fab.isOpen"', 
			   			 'ng-mouseenter="fab.isOpen=true" ng-mouseleave="fab.isOpen=false" md-hover-full', 
			   			 'md-direction="down" style="position:fixed; right: 15px;">',

			  			 '<md-fab-trigger>',
						    '<md-button aria-label="menu" class="md-fab md-primary">',
						      '<md-icon class="mdi mdi-menu"></md-icon>',
						    '</md-button>',
						 '</md-fab-trigger>',
						 
						 '<md-fab-actions>',
							'<md-button has-permission="create-motions" aria-label="create"',
							'ui-sref="create-motion" class="md-fab md-raised md-mini">',
								'<md-icon class="mdi" md-font-icon="mdi-plus"></md-icon>',
							'</md-button>',

						    '<md-button has-permission="administrate-motions" aria-label="edit"',
						    'ui-sref="edit-motion({id:motion.details.id})" class="md-fab md-raised md-mini">',
								'<md-icon class="mdi" md-font-icon="mdi-pencil"></md-icon>',
							'</md-button>',

						    '<md-button has-permission="delete-motions" aria-label="delete"',
						    'ng-click="fab.deleteMotion()" class="md-fab md-raised md-mini md-warn">',
								'<md-icon class="mdi" md-font-icon="mdi-delete"></md-icon>',
							'</md-button>',
						  '</md-fab-actions>',
						  
						'</md-fab-speed-dial>'].join('')
		};

	}

})();