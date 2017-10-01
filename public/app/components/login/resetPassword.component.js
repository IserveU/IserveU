(function() {

  'use strict';

  angular
    .module('app.login')
    .component('resetPasswordComponent', {
      templateUrl: 'app/components/login/resetPassword.tpl.html',
      controller: ResetPasswordController
    });

  ResetPasswordController.$inject = [
    '$rootScope',
    '$sanitize',
    '$state',
    'UserResource',
    'ResetPassword',
    'ToastMessage',
  ];

  function ResetPasswordController($rootScope, $sanitize, $state, UserResource, ResetPassword, ToastMessage) {

    this.$onInit = function() {
      ResetPassword.checkToken(); 
    };

    this.savePassword = function(newPassword) {
      var user = $rootScope.authenticatedUser.slug;
      var password = $sanitize(newPassword);

      UserResource.updateUser(user, {password: password}).then(function(results) {
        $state.go('home').then(function() {
          $state.reload(); // is this necessary?
        });
      }, function(error) {
        ToastMessage.simple('Something went wrong. Please try again later.');
      });
    };
  
  }
}());
