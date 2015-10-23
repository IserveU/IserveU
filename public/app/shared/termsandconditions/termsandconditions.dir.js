(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('termsAndConditions', termsAndConditions);

	function termsAndConditions() {

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
				    	if(answer === 'agree'){
				    		// find a way to validate form before creating user
				        	vm.agree = true;
				    	}
				    	if(create === true){
				    		console.log(create);
				    		vm.hasRead = true;
				    	}
				    	else {
				    		vm.hasRead = false;
				    	}
				    })
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