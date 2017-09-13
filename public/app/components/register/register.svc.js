(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('registerService', [
			'$rootScope',
			'$timeout',
			'authResource',
			'ToastMessage',
			'utils',
			'redirectService',
			'loginService',
      'localStorageManager',
		registerService]);

  	 /** @ngInject */
	function registerService($rootScope, $timeout, authResource, ToastMessage, utils, redirectService, loginService, localStorageManager) {
    
    
    var values = {
      first_name: "",
      last_name: "",
      email: "",
      community_id: null,
      password: "",
      agreement_accepted: localStorageManager.get('agreement_accepted')
    }
    
    var errors = {
      email: false,
      default: {}
    };
    
    var processing = false;


		function createUser() {
      var processing = false;

			if (values.date_of_birth && values.date_of_birth.length > 1) {
          values.date_of_birth = utils.date.stringify(values.date_of_birth);
			}
      
			authResource.register(values).then(loginService.successHandler, function(error) {
        processing = false;
        clearErrorMessages();

        for ( const key of Object.keys(error.data) ) {
          errorHandler(key, error.data[key]);
        }  
    
			});
		}
    
    function clearErrorMessages() {
    
      for (var i in errors) {
        if (errors[i])
          errors[i] = false;
      }
    }
    
    function errorHandler(key, value) {

      key = key.toLowerCase(); 

      switch(value[0]) {
        case "validation.unique":
          errors[key] = "validation.unique";
          break;
        default:
          errors.default = {
            show: true,
            message: responseError.message || "Something went wrong!"
          }
          console.error(responseError);
          break;
      }
    }

    return {
      createUser: createUser,
      values: values,
      errors: errors,
      processing: processing
    };
	
	}

})();