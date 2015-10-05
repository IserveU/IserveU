 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('PropertyController', PropertyController);

	function PropertyController(property, user, $window, $scope, $stateParams) {

		var vm = this;

 		/*********************************** Variables ************************************/ 
		vm.address 		    = {postal_code: '',city: "Yellowknife",territory: "Northwest Territories"}
	    vm.selectedItem     = null;
	    vm.searchText       = null;
	    vm.searchNumber     = null;
	    vm.searchApt  	    = null;
	    vm.searchRollNumber = null;
	    vm.checkForAddress  = false;
	    vm.isMyAddress      = false;
	    vm.dataGroup		= {};
	    vm.searching		= false;

 		/*********************************** Functions ************************************/ 
	    vm.querySearch        = querySearch;
	    vm.selectedItemChange = selectedItemChange;
	    vm.checkOut 		  = checkOut;
	    vm.checkProperty 	  = checkProperty;
	    vm.assignPropertyData = assignPropertyData;

		function querySearch() {
			var data = {
				search_query_apt_number: vm.searchApt,
				search_query_address_number: vm.searchNumber,
				search_query_street_name: vm.searchText,
				search_query_roll_number: vm.searchRollNumber
			}
			return property.searchProperty(data).then(function(result){
				vm.property_queries = result;
				vm.searching = true;
				return result;
			});
		}

		function selectedItemChange(){
			vm.checkForAddress = true;
		}

		function checkOut(){
			vm.isMyAddress = !vm.isMyAddress;
		}

		function assignPropertyData(query){
			vm.selectedItem = query;
			vm.searchText = query.street;
			vm.searchNumber = query.address;
			vm.searchApt = query.unit;
			vm.searchRollNumber = query.roll_number;
		}

		function checkProperty(){
			if(!vm.selectedItem) {
				querySearch().then(function(result){
					vm.selectedItem = result[0];
					vm.checkForAddress = true;
				});
			}
			else if(vm.isMyAddress){
				saveProperty();
			}
		}

		function saveProperty(){
			var property_data = {
				id: vm.selectedItem.id,
				postal_code: vm.address.postal_code
			}

			property.updateProperty(property_data).then(function(results){
				console.log(results);
			})

			var user_data = {
				id: $stateParams.id,
				property_id: vm.selectedItem.id
			}

			user.updateUser(user_data).then(function(results){
				console.log(results);
			})
		}


 		/*********************************** Property Manager Functions (Must abstract) ************************************/ 

		vm.users;
 		user.getUserInfo().then(function(results){
 			vm.users = results.data;
 		})

	}




})();
