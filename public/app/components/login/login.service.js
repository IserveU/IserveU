(function() {

  'use strict';

  angular
    .module('iserveu')
    .factory('Login', LoginFactory);

  LoginFactory.$inject = ['Auth', 'AuthResource', 'LocalStorageManager'];

  function LoginFactory(Auth, AuthResource, LocalStorageManager) {
    
    var Login = {
      creating: false,
      loggingIn: false,
      rememberMe: true,
      authError: false,
      credentials: {
        email: '',
        password: ''
      },
      errors: {
        emailNotValid: false,
        invalidCredentials: false,
        accountLocked: false,
        default: {}
      },
      clearErrorMessages: clearErrorMessages,
      login: login,
      // clearCredentials: clearCredentials,
      // made public for resetPassword
      // successHandler: successHandler
    };

    /**
     * Depecrated: Clears out the logged in user and logs out
     */
    function clearCredentials(redirect) {
      // $rootScope.authenticatedUser = null;
      // $rootScope.userIsLoggedIn = false;
      // LocalStorageManager.logout();
      // motionIndex.clear();
      // if (redirect) {
      //   Redirect.onLogout();
      // }
    }

    function clearErrorMessages() {
      Login.authError = false;
      for (var i in Login.errors) {
        if (Login.errors[i])
          Login.errors[i] = false;
      }
    }

    function login(credentials) {
      Login.loggingIn = true;

      AuthResource.login(credentials).then(function(success) {
        Auth.handleAuthentication(success);
        LocalStorageManager.set('remember_me', Login.rememberMe);
      }, function(error) {
        Login.loggingIn = false;
        errorHandler(error.data);
      });
    }

    /** deprecated */
    function successHandler(res) {
      // LocalStorageManager.remove('agreement_accepted');

      // var user = res.user || res.data || res;
      // console.log(res);

      // $rootScope.userIsLoggedIn = true;
      // $rootScope.authenticatedUser = user;
      // $rootScope.authenticatedUser.permissions = Utils
      //   .transformObjectToArray($rootScope.authenticatedUser.permissions);

      // // motionIndex.clear();

      // LocalStorageManager.setCredentials(user, Login.rememberMe);

      // // Temporary fix to the user agreement not having a service and directive
      // if (!user.agreement_accepted) {
      //   window.location.href = "/";
      // }

      // $timeout(function() {
      //   Redirect.redirect()
      // }, 250);
    }

    function errorHandler(responseError) {

      clearErrorMessages();

      var error = responseError.error ? responseError.error.toLowerCase() : responseError;

      switch (error) {
        case "invalid credentials":
          Login.errors.invalidCredentials = true;
          break;
        case "email address not in database":
          Login.errors.invalidEmail = true;
          break;
        case "account is locked":
          Login.errors.accountLocked = true;
          break;
        default:
          Login.errors.default = {
            show: true,
            message: responseError.message || "Something went wrong!"
          }
          console.error(responseError);
          break;
      }
    }
    return Login;
  }


})();
