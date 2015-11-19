(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('splitUserField', splitUserField);


	function splitUserField($q) {

		var vm = this;

		vm.given = function(fields){
		angular.forEach(fields, function(value, key){
			switch(value.key){
				case "password":
					vm.password = value;
					break;
				case "identity_verified": 
					vm.identity_verified = value;
					break;
				case "email":
					vm.email = value;
					break;
				case "ethnic_origin_id":
					vm.ethnic_origin_id = value;
					break;
				case "public":
					vm.public = value;
					break;
				case "first_name":
					vm.first_name = value;
					break;
				case "middle_name":
					vm.middle_name = value;
					break;
				case "last_name":
					vm.last_name = value;
					break;
				case "date_of_birth":
					vm.date_of_birth = value;
					break;
				default:
					break;
			}
			});
		}

		vm.set = function(array){
			if(array){
				if(array.type == 'password'){
					array.type = array.type+'-edit';
				}
				var field = [{
		    		key: array.key,
		    		type: array.type,
		    		templateOptions: array.templateOptions,
		        	noFormControl: true,
	    		}]
	    	return field;
 		   }
		};

	}


}());
