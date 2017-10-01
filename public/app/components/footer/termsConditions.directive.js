(function() {

  'use strict';

  angular
    .module('app.footer')
    .directive('termsAndConditions',
      ['$rootScope',
       'UserResource',
       '$log',
       'LocalStorageManager',
       'Settings',
       termsAndConditions]);

    function termsAndConditions($rootScope, UserResource, $log, LocalStorageManager, Settings) {

		function controllerMethod($mdDialog, $scope) {
      
      var self = this;  //global context for this
      
      if (!Settings.get('site.terms.force')) {
        return self;        
      } 

      var user = $rootScope.authenticatedUser || undefined;

      self.text = Settings.get('site.terms.text');      
      self.agreed  = LocalStorageManager.get('agreement_accepted',false);      
      self.showContract = showContract;

      if (!self.agreed) {
        showContract();
      }

      function showContract(ev) {
		    $mdDialog.show({
		      controller: ['$scope', '$mdDialog', TermsAndConditionsController],
		      templateUrl: 'app/components/footer/termsConditions.tpl.html',
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

    function updateDatabaseRecord(user) {
      UserResource.updateUser(user.slug, {agreement_accepted: 1}).then(function(result){
        if (!result.agreement_accepted) {
          $log.error("API not returning expected response", result);
        }
      });
    }
    
    function updateSessionRecord() {
        self.agreed = true;
        LocalStorageManager.set('agreement_accepted',true);
    }
        
    function TermsAndConditionsController($scope, $mdDialog) {

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
