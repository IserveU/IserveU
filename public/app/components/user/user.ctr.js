(function() {

	angular
		.module('iserveu')
		.controller('UserController', UserController);

	function UserController($rootScope, $scope, $stateParams, $filter, user, $mdToast, $animate, UserbarService, $state, $timeout) {
		
		UserbarService.setTitle("");
		
		var vm = this;
		var userlocal = JSON.parse(localStorage.getItem('user'));
		var userpermissions = JSON.parse(localStorage.getItem('permissions'));

		vm.canShowUser = false;
		vm.nextpage;
		vm.usercredentials;

		vm.isuserprofile = false;
		vm.ispublic = true;
		vm.users = [];


		//make a function to set the field names for this from api field names given
		vm.formEdit = {
			first_name: false,
			middle_name: false,
			last_name: false,
			date_of_birth: false,
			email: false
		};

		vm.isLoading = {
			first_name: false,
			middle_name: false,
			last_name: false,
			date_of_birth: false,
			email: false
		};

		vm.onSuccess = {
			first_name: false,
			middle_name: false,
			last_name: false,
			date_of_birth: false,
			email: false
		};

		$scope.getId = getId;
		vm.updateInfo = updateInfo;

		vm.showEditUsers = function(){
			angular.forEach(vm.formEdit, function(value, key) {
				vm.formEdit[key] = !vm.formEdit[key];
			});
		}

		vm.verifyUser = function(userinfo){
			var verifiedData = userinfo.identity_verified;
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

		function getId(){
			var id;
			angular.forEach(vm.users, function(value, key) {
				if(value.id == $stateParams.id){
					vm.id = value.id;
					id = key;
				}
			})
			return id;
		}

		function checkPermissions() {
			if(userpermissions.indexOf("show-users") != -1) {
				vm.canShowUser = true;
			}
		}

		function checkPublic(data) {
			if(data[getId()]){
			if(data[getId()].public == 0){
				vm.ispublic = false;
				vm.publicChoice = "Not Public";
			}
			else {
				vm.publicChoice = "Public";	
			}
			}
		}

		function checkUser() {
			if($stateParams.id == userlocal.id) {
				vm.isuserprofile = true;
			}
		}

		function getUsers(){
			user.getUserInfo().then(function(result) {
				vm.nextpage = result.current_page + 1;
				vm.users = result.data;
				checkUser();
				checkPublic(vm.users);
            });         
		}

		function loadMoreUsers(){
			var data = {
				page: vm.nextpage,
			}
			user.getUserInfo().then(function(result) {
				if(result.current_page == vm.nextpage){
					vm.nextpage = result.current_page + 1;
					vm.users.push(result.data);
					checkUser();
					checkPublic(vm.users);
				}
			}, function(error) {
				console.log(error);
			});
		}

		vm.setPublic = function(value) {
			vm.users[getId()].public = value;
			updateInfo(vm.users[getId()], 'public');
		}

		function updateInfo(data, datatype) {
			isLoading(datatype);
			user.updateUser(data).then(function(result){
				if(data.id == userlocal.id && data.public == 0){            
				$mdToast.show(
                  $mdToast.simple()
                    .content('Your changes will be seen when you log back in.')
                    .position('bottom right')
                    .hideDelay(3000)
                );}
				onSuccess(datatype);
				isLoading(datatype);
				$timeout(function(){
					onSuccess(datatype); changeEditable(datatype);}, 1500);
			},function(error){
				console.log(error);
			});
		}

		function isLoading(datatype){
			angular.forEach(vm.isLoading, function(value, key) {
				if(key == datatype) {
					vm.isLoading[key] = !vm.isLoading[key];
				}
			})
		}

		function onSuccess(datatype) {
			angular.forEach(vm.onSuccess, function(value, key) {
				if(key == datatype) {
					vm.onSuccess[key] = !vm.onSuccess[key];

				}
			})
		}

		function changeEditable(datatype) {
			angular.forEach(vm.formEdit, function(value, key) {
				if(key == datatype) {
					vm.formEdit[key] = !vm.formEdit[key];
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