 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('MotionSidebarController', MotionSidebarController);

	function MotionSidebarController($rootScope, $stateParams, $state, $scope, $mdSidenav, $interval, motion, vote, department, SetPermissionsService, ToastMessage) {

		var vm = this;

		/**************************************** Motion Sidebar Variables **************************************** */

		$scope.$state = $state;

		vm.listLoading = true;

        vm.can_create_vote  = SetPermissionsService.can('create-votes');

		vm.getMotions = getMotions;

		vm.emptyMotionsArray = false;

		vm.motion_filters = {
			take: 100,
			limit: 100,
			page: '',
			oldest: true,
			is_active: true,
			is_current: true
		}

		vm.orderByFilters = [
			   // {name: "Popularity" 		,query: "search_query_popularity"}, 
			   {name: "Newest"     		,query: {oldest: true}},
			   {name: "Oldest"	   		,query: {newest: true}},
			   {name: "Open for Voting" ,query: {is_active:true, is_current:true}},
			   {name: "Closed"			,query: {is_expired:true}}]

		vm.departmentFilter = {id: ''};
		vm.orderByFilter;
		vm.newFilter = [];

		vm.next_page = 1;

		vm.showSearchFilter = false;
		vm.searchOpened = false;
		vm.hide_show_more = true;
		vm.paginate_loading = false;

		vm.searchText = '';

		vm.motion_is_loading = {};

		vm.switchLoading = switchLoading;

		/**************************************** Motion Sidebar Function **************************************** */

		vm.closeSidenav = function(menuId){
			$mdSidenav(menuId).close();
		}

		vm.loadDepartments = function(){
			department.getDepartments().then(function(result){
				vm.departments = result;
			})
		}

		vm.querySearch = function(filter){
			if(angular.isNumber(vm.departmentFilter)){
				filter['department_id'] = vm.departmentFilter;
			}
			return motion.getMotions(filter).then(function(result){
				vm.newFilter = filter;
				return vm.motions = result.data;
			})
		}

		vm.querySearchDepartment = function(filter) {
			vm.newFilter['department_id'] = filter.department_id;
			return motion.getMotions(vm.newFilter).then(function(result){
				vm.newFilter = vm.newFilter;
				return vm.motions = result.data;
			})
		}

		vm.showSearch = function(){
			if(vm.showSearchFilter){
				vm.searchText = '';
			}
			vm.searchOpened = !vm.searchOpened;
			vm.showSearchFilter = !vm.showSearchFilter;
		}

		function switchLoading(bool, id){
			vm.motion_is_loading[id] = bool;
		}

		function getMotions(filter){
			motion.getMotions(filter).then(function(results) {
				vm.listLoading = false;
				vm.paginate_loading = false;
				vm.motions = results.data;
				if(!results.data[0]){
					vm.emptyMotionsArray = true;
				}
				if(results.next_page_url == null){
					vm.hide_show_more = false;
				}
				else{
					vm.next_page = results.next_page_url.slice(-1);
				}
			});
		};

		vm.loadMoreMotions = function(){
			vm.motion_filters.page = vm.next_page;
			vm.paginate_loading = true;
			getMotions();
		}


		// make this into a directive
		vm.cycleVote = function(motion){

			if(!motion.MotionOpenForVoting){
				ToastMessage.simple("Sorry! This motion is not open for voting.", 1000);
			}

			else{

				if(!motion.user_vote){
					castVote(motion.id);
				}

				else{
					var data = {
		                id: motion.user_vote.id,
		                position: null
		            }
					if(motion.user_vote.position != 1){
						data.position = motion.user_vote.position + 1; 
					}
					else {
						data.position = -1;
					}

					updateVote(data);
				}

			}

		}

		function castVote(id){
			// start at abstain
			vote.castVote({motion_id:id, position:0}).then(function(result){
				getMotions();
				getMotionInsideVoteController(result);
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(result) {
				getMotions();
				getMotionInsideVoteController(result);
			});
		}

		function getMotionInsideVoteController(result) {
			if($stateParams.id == result.motion_id){
				$rootScope.$emit('getMotionInsideVoteController', {vote:result});
			}
		}

		getMotions(vm.motion_filters);
		switchLoading(true, $stateParams.id);

		$rootScope.$on('sidebarLoadingFinished', function(events, data) {
			switchLoading(data.bool, data.id);
		});


		$rootScope.$on('refreshMotionSidebar', function(events, data) {
			getMotions(vm.motion_filters);
		});

		$rootScope.$on('refreshSelectMotionOnSidebar', function(events, data){
			angular.forEach(vm.motions, function(value, key) {
				if(value.id == data.motion.id){
					vm.motions[key] = data.motion;
				}
			})
		})     

	}



})();