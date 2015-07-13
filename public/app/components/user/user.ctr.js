(function() {

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, $stateParams, user, $mdToast, $animate, UserbarService, $state) {
		
		UserbarService.setTitle("");
		
		var vm = this;
		var userlocal = JSON.parse(localStorage.getItem('user'));
		var userpermissions = JSON.parse(localStorage.getItem('permissions'));

		vm.canShowUser = false;
		vm.canEditUser = false;

		$scope.isuserprofile = false;
		$scope.ispublic = true;
		$scope.nameformBool = false;
		$scope.emailformBool = false;
		$scope.notlocked = true;
		$scope.users = [];

		$scope.formEdit = {
			first_name: false,
			middle_name: false,
			last_name: false,
			date_of_birth: false,
			email: false
		};

		$scope.isLoading = {
			first_name: false,
			middle_name: false,
			last_name: false,
			date_of_birth: false,
			email: false
		};

		$scope.onSuccess = {
			first_name: false,
			middle_name: false,
			last_name: false,
			date_of_birth: false,
			email: false
		};

		$scope.getId = getId;

		$scope.verifyUser = function(userinfo){
			var verifiedData = userinfo.identity_verified;
			console.log(verifiedData)
			var data = {
				id: userinfo.id,
				identity_verified: verifiedData
			}
			editUser(data);

			var isverified;
			if(userinfo.identity_verified == 1){
				isverified = "verified.";
			}
			else {
				isverified = "unverified.";
			}

			var message = userinfo.first_name + ' ' + userinfo.last_name + ' has been ' + isverified;

			$mdToast.show(
              $mdToast.simple()
                .content(message)
                .position('bottom right')
                .hideDelay(3000)
          	  );
		}

		$scope.closeUserList = function(){
			$rootScope.userListIsClicked = false
		}

		function getId(){
			var id = $stateParams.id;
			id--;
			return id;
		}

		$scope.edit = function(booleanVar) {
			console.log(booleanVar);
			return booleanVar = true;
			console.log(booleanVar);
		}

		function checkPermissions() {
			if(userpermissions.indexOf("show-users") != -1) {
				vm.canShowUser = true;
			}
			if(userpermissions.indexOf("edit-users") != -1) {
				vm.canEditUser = true;
			}
		}

		function checkPublic() {
			if(!vm.canShowUser && !$scope.users[getId()]) { 
				$scope.ispublic = false;
			}
		}

		function checkUser() {
			if(!vm.canShowUser && $stateParams.id == userlocal.id) {
				$scope.isuserprofile = true;
			}
		}

		function getUsers(){
			user.getUserInfo().then(function(result) {
				$scope.users = result;
				checkPublic();
				checkUser();
            });         
		}

		//returns true or false if user info is permissable to be udpated
		$scope.updateInfo = function(data, datatype){
			var id = data.id;
			console.log(data);
			return user.editUser(id).then(function(result) {
				if(result[datatype].match("locked")){
				$mdToast.show(
                  $mdToast.simple()
                    .content("This will take a few days to process!")
                    .position('bottom right')
                    .hideDelay(3000)
                );
					return false;
				};
				data = {id:id};
				angular.extend(data, [input]);
				Object.defineProperty(data, datatype,
					Object.getOwnPropertyDescriptor(data, 0));
				delete data[0];
				editUser(data);
			});
		}

		function editUser (data) {
			console.log(data);
			user.updateUser(data).then(function(result){
				console.log(result);
			},function(error){
				console.log(error);
			});
		}

		vm.isLoading = function(data){
			console.log(data);
			// $scope.isLoading = !$scope.isLoading;
		}

		getUsers();
		checkPermissions();
	
	}
})();