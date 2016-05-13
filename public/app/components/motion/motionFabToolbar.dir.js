(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionFabToolbar', ['$rootScope', 'Authorizer', '$state', '$stateParams', 'motionResource', 'motionIndex', 'fabLink', 'ToastMessage', 
		motionFabToolbar]);

	function motionFabToolbar($rootScope, Authorizer, $state, $stateParams, motionResource, motionIndex, fabLink, ToastMessage){

		return {
			link: function(scope, el, attrs) { 
				fabLink(el); 
			},
			controller: function() {
				this.isOpen = false;
		        
		        this.deleteMotion = function() {
		        	ToastMessage.destroyThis("motion", function(){

	                    motionResource.deleteMotion($stateParams.id).then(function(r) {
	                        $state.go('home', {}, {reload: true});
	                        motionIndex._load();
	                    }, function(e) { ToastMessage.report_error(e); });
		        	
		        	});
		        };

		        this.isUsersMotion = function(userId) {
		        	if(Authorizer.canAccess('administrate-motion')) 
		        		return true;
		        	else if( $rootScope.userIsLoggedIn && ($rootScope.authenticatedUser.id === userId) )
		        		return true;
		        	else 
		        		return false;
		        }

			},
			controllerAs: 'fab',
			template: [ '<md-fab-speed-dial ng-if="fab.isUsersMotion(motion.user_id)"',
						 'class="md-scale fab-tool-animate" md-open="fab.isOpen"', 
			   			 'ng-mouseenter="fab.isOpen=true" ng-mouseleave="fab.isOpen=false" md-hover-full', 
			   			 'md-direction="down" style="position:fixed; right: 15px;">',

			  			'<md-fab-trigger>',
						    '<md-button aria-label="menu" class="md-fab md-primary">',
						      '<md-icon class="mdi mdi-menu"></md-icon>',
						    '</md-button>',
						'</md-fab-trigger>',
						 
						'<md-fab-actions>',
							'<md-button has-permission="administrate-motion" aria-label="create"',
							'ui-sref="create-motion" class="md-fab md-raised md-mini">',
								'<md-icon class="mdi" md-font-icon="mdi-plus"></md-icon>',
							'</md-button>',

						    '<md-button aria-label="edit"',
						    'ui-sref="edit-motion({id:motion.id})" class="md-fab md-raised md-mini">',
								'<md-icon class="mdi" md-font-icon="mdi-pencil"></md-icon>',
							'</md-button>',

						    '<md-button has-permission="delete-motion" aria-label="delete"',
						    'ng-click="fab.deleteMotion()" class="md-fab md-raised md-mini md-warn">',
								'<md-icon class="mdi" md-font-icon="mdi-delete"></md-icon>',
							'</md-button>',
						'</md-fab-actions>',
						'</md-fab-speed-dial>'].join('')
		};

	}

})();