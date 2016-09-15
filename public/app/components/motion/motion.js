(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayMotion', [
			'$rootScope',
			'$stateParams',
			'$state',
			'Authorizer',
			'Motion',
			'motionResource',
			'motionIndex',
			'ToastMessage',
		displayMotion]);

	function displayMotion($rootScope, $stateParams, $state, Authorizer, Motion, motionResource, motionIndex, ToastMessage) {

	  function MotionController($scope) {

	  		/**
	  		*	Prototypical function from Motion model to load motion. 
	  		*	Attempts to get from motionIndex object, if there is
	  		*	no motion it will pull from the API and create a new
	  		*   Motion model and reinsert that into the index.
	  		*/
			$scope.motion = Motion.get( $stateParams.id );

			function create() {
				$state.go('create-motion');
			}

			function edit() {
				$state.go('edit-motion', {id: $stateParams.id});
			}

		    function destroy() {
	        	ToastMessage.destroyThis("motion", function(){
                    motionResource.deleteMotion($stateParams.id).then(function() {
                        $state.go('home', {}, {reload: true});
                        motionIndex._load();
                    });        	
	        	});
	        };

		    function isThisUsers(user) {
	        	if(Authorizer.canAccess('administrate-motion')) 
	        		return true;
	        	else if( $rootScope.userIsLoggedIn && ($rootScope.authenticatedUser.id === user) )
	        		return true;
        		return false;
	        }

	        (function exposeScopeMethods(){
	        	var methods = {
	        		create: create,
	        		edit: edit,
	        		destroy: destroy,
	        		isThisUsers: isThisUsers
	        	};

	        	angular.extend($scope, methods);
	        })();
	    }

	    return {
	    	controller: ['$scope', MotionController],
	    	templateUrl: 'app/components/motion/motion.tpl.html'
	    }


	}


})();