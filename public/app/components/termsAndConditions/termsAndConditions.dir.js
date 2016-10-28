(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('termsAndConditions', ['$rootScope', 'loginService', termsAndConditions]);

	function termsAndConditions($rootScope, loginService) {

		function controllerMethod($mdDialog, $scope) {

        	var self = this;  //global context for this

        	self.showContract = showContract;
        	self.agreed   = false;
    		self.hasRead  = false;

        	function showContract(ev){

        		ev.preventDefault();

			    if(self.hasRead === false){

				    $mdDialog.show({
				      controller: ['$scope', '$mdDialog', TermsAndConditionsController],
				      templateUrl: 'app/components/termsAndConditions/termsAndConditions.tpl.html',
				      parent: angular.element(document.body),
				      targetEvent: ev,
				      clickOutsideToClose: false
				    }).then(function(answer){
				    	handleAnswer(answer, ev);
				    });

				} else if ( ev.target.nodeName === 'FORM' ) {
		    		loginService.createUser();
				}
        	}

        	function handleAnswer(answer, ev) {

    		 	if( answer === 'agree' ) {
		        	self.agreed = true;
    		 	}

		    	if( answer === 'agree' && ev.target.nodeName === 'FORM') {
		    		loginService.createUser();
		    		self.hasRead = true;
		    	} else {
		    		self.hasRead = false;
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
			  console.log($rootScope.userIsLoggedIn);
			  $scope.userIsLoggedIn = $rootScope.userIsLoggedIn;
			  $scope.settingsGlobal = $rootScope.settingsGlobal;
        	}

  		}

		return {
		    controller: ['$mdDialog', '$scope', controllerMethod],
		    controllerAs: 'terms',
		    bindToController: true,
		    scope: true
		}
	}
}());