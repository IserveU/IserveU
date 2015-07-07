(function() {

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, $stateParams, user, $mdToast, $animate, UserbarService) {
		
		UserbarService.setTitle("Profile");
		
		var vm = this;
		
		$scope.isuserprofile = true;
		$scope.nameformBool = false;
		$scope.notlocked = true;
		$scope.users = {
				userInfo: null
			}

		$scope.verifyUser = function(userinfo){
			console.log(userinfo.id);
			var data = {
				id: userinfo.id,
				identity_verified: 1
			}
			editUser(data);
		}

		$scope.closeUserList = function(){
			$rootScope.userListIsClicked = false
		}

		$scope.getId = function(){
			var id = $stateParams.id;
			id--;
			return id;
		}

		function getUsers(){
			user.getUserInfo().then(function(result) {
				$scope.users.userInfo = result;

            });         
		}

		//returns true or false if user info is permissable to be udpated
		$scope.updateInfo = function(data, datatype, id){
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
			user.updateUser(data).then(function(result){
				console.log(result);
			},function(error){
				console.log(error);
			});
		}

		getUsers();
	
	}
})();