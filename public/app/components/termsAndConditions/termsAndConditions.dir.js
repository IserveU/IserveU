(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('termsAndConditions',
      ['$rootScope',
       'loginService',
       'userResource',
       '$log',
       'localStorageManager',
       termsAndConditions]);

    function termsAndConditions($rootScope, loginService, userResource, $log, localStorageManager) {

		function controllerMethod($mdDialog, $scope) {
    
      
      var self = this;  //global context for this
      

      if(!$rootScope.settingsGlobal.site.terms.force){
        return self;        
      } 
      
      
      var user;

      if($rootScope.authenticatedUser){
        user = $rootScope.authenticatedUser;
      }
      
      self.agreed  = localStorageManager.get('agreement_accepted',false);      

      if (!self.agreed) {
        showContract();
      }

      function showContract(ev) {


		    $mdDialog.show({
		      controller: ['$scope', '$mdDialog', TermsAndConditionsController],
		      templateUrl: 'app/components/termsAndConditions/termsAndConditions.tpl.html',
		      parent: angular.element(document.body),
		      targetEvent: ev || null,
		      clickOutsideToClose: false
		    });
    	}

      function handleAnswer(answer) {
        //Not agreeing does nothing. You must agree
        if(answer!=='agree'){ return false; }

        // Post to the backend the record accept
        if(user){ updateDatabaseRecord(user); }
        
        // Fixup the frontend
        updateSessionRecord();

        $mdDialog.hide(answer);
    }

    function updateDatabaseRecord(user){
      userResource.updateUser(user.slug, {agreement_accepted: 1}).then(function(result){
        if(!result.agreement_accepted){
          
          $log.error("API not returning expected response", result);
        }
      });
    }
    
    function updateSessionRecord(){
        self.agreed   = true;
        localStorageManager.set('agreement_accepted',true);
    }
        
    function TermsAndConditionsController($scope, $mdDialog){

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
    	    handleAnswer(answer);
      };

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
