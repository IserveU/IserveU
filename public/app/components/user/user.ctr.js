(function() {

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, $stateParams, user, $mdToast, $animate, UserbarService, $state, $timeout) {
		
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
		$scope.updateInfo = updateInfo;

		vm.showEditUsers = function(){
			angular.forEach($scope.formEdit, function(value, key) {
				$scope.formEdit[key] = !$scope.formEdit[key];
			});
		}

		$scope.verifyUser = function(userinfo){
			var verifiedData = userinfo.identity_verified;
			console.log(verifiedData)
			var data = {
				id: userinfo.id,
				identity_verified: verifiedData
			}
			updateInfo(data);

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

		function checkPermissions() {
			if(userpermissions.indexOf("show-users") != -1) {
				vm.canShowUser = true;
			}
			if(userpermissions.indexOf("administrate-users") != -1) {
				vm.canEditUser = true;
			}
		}

		function checkPublic() {
			console.log($scope.users[getId()]);
			if($scope.users[getId()].public == 0) { 
				$scope.ispublic = false;
				console.log("this is annoying");
			}
			
				if($scope.users[getId()].public == 0){
					return $scope.publicChoice = "Not Public";
				}
				else 
					$scope.publicChoice = "Public";
			
		}

		function checkUser() {
			if($stateParams.id == userlocal.id) {
				$scope.isuserprofile = true;
			}
		}

		function getUsers(){
			user.getUserInfo().then(function(result) {
				console.log(result);
				$scope.users = result;
				checkPublic();
				checkUser();
				angular.forEach(result, function(value, key) {
					$scope.users[key].date_of_birth = new Date(result[key].date_of_birth);
				});

            });         
		}

		$scope.setPublic = function(value) {
			$scope.users[getId()].public = value;
			updateInfo($scope.users[getId()], 'public');
		}

		function updateInfo(data, datatype) {
			isLoading(datatype);
			user.updateUser(data).then(function(result){
				onSuccess(datatype);
				isLoading(datatype);
				$timeout(function(){
					onSuccess(datatype); changeEditable(datatype);}, 1500);
			},function(error){
				console.log(error);
			});
		}

		function isLoading(datatype){
			angular.forEach($scope.isLoading, function(value, key) {
				if(key == datatype) {
					$scope.isLoading[key] = !$scope.isLoading[key];
				}
			})
		}

		function onSuccess(datatype) {
			angular.forEach($scope.onSuccess, function(value, key) {
				if(key == datatype) {
					$scope.onSuccess[key] = !$scope.onSuccess[key];

				}
			})
		}

		function changeEditable(datatype) {
			angular.forEach($scope.formEdit, function(value, key) {
				if(key == datatype) {
					$scope.formEdit[key] = !$scope.formEdit[key];
				}
			})
		}

		// function getFields(id){
		// 	user.editUser(id).then(function(result){
		// 		console.log(result);
		// 	})
		// }

		getUsers();
		checkPermissions();
	
	}
})();