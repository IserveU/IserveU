(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('termsAndConditions', ['SETTINGS_JSON', 'loginService', termsAndConditions]);


  	 /** @ngInject */
	function termsAndConditions(SETTINGS_JSON, loginService) {
	  	 /** @ngInject */
		function controllerMethod($mdDialog, $scope) {
        	
        	var vm = this;

        	vm.showTermsAndConditions = showTermsAndConditions;
        	vm.agree   = false;
    		vm.hasRead = false;
    		
        	function showTermsAndConditions(ev, create, authError){

			    if(vm.hasRead === false){
				    $mdDialog.show({
				      controller: ['$scope', '$mdDialog', TermsAndConditionsController],
				      templateUrl: 'app/shared/terms/termsandconditions.tpl.html',
				      parent: angular.element(document.body),
				      targetEvent: ev,
				      clickOutsideToClose:false
				    }).then(function(answer){
				    	if( answer === 'agree' )
				        	vm.agree = true;
				    	if( answer === 'agree' && create === true ) {
				    		loginService.createUser();
				    		vm.hasRead = true;
				    	}
				    	else
				    		vm.hasRead = false;
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