(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('termsAndConditions', ['$rootScope', 'SETTINGS_JSON', 'loginService', termsAndConditions]);


	function termsAndConditions($rootScope, SETTINGS_JSON, loginService) {

		function controllerMethod($mdDialog, $scope) {
        	
        	var self = this;  //global context for this

        	self.showTermsAndConditions = showTermsAndConditions;
        	self.agree   = false;
    		self.hasRead = false;

        	function showTermsAndConditions(ev, create, authError){

        		ev.preventDefault();

			    if(self.hasRead === false){
				    $mdDialog.show({
				      controller: ['$scope', '$mdDialog', TermsAndConditionsController],
				      templateUrl: 'app/shared/terms/termsandconditions.tpl.html',
				      parent: angular.element(document.body),
				      targetEvent: ev,
				      clickOutsideToClose:false
				    }).then(function(answer){
				    	if( answer === 'agree' )
				        	self.agree = true;
				    	if( answer === 'agree' && create === true ) {
				    		loginService.createUser();
				    		self.hasRead = true;
				    	}
				    	else
				    		self.hasRead = false;
				    });
				} else if (authError)
		    		loginService.createUser();
        	}

        	function TermsAndConditionsController($scope, $mdDialog){
    		  $scope.hide = function() {
			    $mdDialog.hide();
			  };
			  $scope.cancel = function() {
			    $mdDialog.cancel();
			  };
			  $scope.answer = function(answer) {
			    $mdDialog.hide(answer);
			  };
        		
        	  $scope.userIsLoggedIn = $rootScope.userIsLoggedIn;
			  $scope.settings = SETTINGS_JSON;
        	}

  		}	

		return {
		    controller: ['$mdDialog', '$scope', controllerMethod],
		    controllerAs: 'ctrl',
		    bindToController: true,
		    scope: true
		}
	}
}());