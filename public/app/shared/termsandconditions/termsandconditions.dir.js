(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('termsAndConditions', termsAndConditions);


  	 /** @ngInject */
	function termsAndConditions(settings, loginService) {
	  	 /** @ngInject */
		function controllerMethod($mdDialog, $scope) {
        	
        	var vm = this;

        	vm.showTermsAndConditions = showTermsAndConditions;
        	vm.agree   = false;
    		vm.hasRead = false;
    		
        	function showTermsAndConditions(ev, create){
			    if(vm.hasRead === false){
				    $mdDialog.show({
				      controller: TermsAndConditionsController,
				      templateUrl: 'app/shared/termsandconditions/termsandconditions.tpl.html',
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
				}
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
        	
			  $scope.settings = settings.getData();

        	}

  		}	

		return {
		    controller: controllerMethod,
		    controllerAs: 'ctrl',
		    bindToController: true,
		    scope: true
		}
	}
}());