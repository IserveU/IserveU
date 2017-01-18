(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('termsAndConditions', ['$rootScope', 'loginService', 'userResource', termsAndConditions]);

    function termsAndConditions($rootScope, loginService, userResource) {

		function controllerMethod($mdDialog, $scope) {

      var self = this;  //global context for this
      var user = {};

      if($rootScope.authenticatedUser){
        user = $rootScope.authenticatedUser;
      }

      self.showContract = showContract;
      self.agreed   = user.agreement_accepted || false;
      self.hasRead  = false;

      console.log(user);
      if ($scope.userIsLoggedIn && !self.agreed) {
        showContract();
      }

      function showContract(ev) {

        if(ev) {
          ev.preventDefault();
        } else {
          ev = {};
        }

	      if(self.hasRead === false){
  		    $mdDialog.show({
  		      controller: ['$scope', '$mdDialog', TermsAndConditionsController],
  		      templateUrl: 'app/components/termsAndConditions/termsAndConditions.tpl.html',
  		      parent: angular.element(document.body),
  		      targetEvent: ev || null,
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

      var user = $rootScope.authenticatedUser;

      if ($scope.userIsLoggedIn && !user.agreement_accepted){
        $scope.notAccepted = true;
        $mdDialog.show();
      }

      $scope.hide = function() {
        $mdDialog.hide();
      };

      $scope.cancel = function() {
        $mdDialog.cancel();
      };

      $scope.answer = function(answer) {

        if(!$scope.notAccepted && user)
          $mdDialog.hide();

        if(answer === 'agree' && user){
          userResource.updateUser(user.slug, {agreement_accepted: 1}).then(function(results){
            $rootScope.authenticatedUser.agreement_accepted = true;
          });
        }

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
