(function() {

	'use strict';

	angular
		.module('app.login')
		.factory('Register', RegisterFactory);

  RegisterFactory.$inject = ['$rootScope','$timeout','Auth','AuthResource','ToastMessage','Utils','LocalStorageManager'];
	function RegisterFactory($rootScope, $timeout, Auth, AuthResource, ToastMessage, Utils, LocalStorageManager) {
    
    var Register = {
      values: {
        first_name: "",
        last_name: "",
        email: "",
        community_id: null,
        password: "",
        agreement_accepted: LocalStorageManager.get('agreement_accepted')
      },
      errors: {
        email: false,
        default: {}
      },
      processing: false,
      createUser: function(val) {
         Register.processing = true;

        if (val.date_of_birth && val.date_of_birth.length > 1) {
            val.date_of_birth = Utils.date.stringify(val.date_of_birth);
        }
        
        AuthResource.register(val).then(Auth.handleAuthentication, function(error) {
          Register.processing = false;
          Register.clearErrorMessages();
          for ( const key of Object.keys(error.data) ) {
            Register.errorHandler(key, error.data[key]);
          }  

        });
      },
      clearErrorMessages: function() {
        for (var i in this.errors) {
          if (this.errors[i]) this.errors[i] = false;
        }
      },
      errorHandler: function(key, value) {
        key = key.toLowerCase(); 

        switch(value[0]) {
          case "validation.unique":
            Register.errors[key] = "validation.unique";
            break;
          default:
            Register.errors.default = {
              show: true,
              message: responseError.message || "Something went wrong!"
            }
            break;
        }
      }
    }

    return Register;
	}

})();