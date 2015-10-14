 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('MotionSidebarController', MotionSidebarController);

	function MotionSidebarController($rootScope, $stateParams, $state, $scope, motion, vote, department) {

		var vm = this;

		$scope.$state = $state;

		vm.need_identification = JSON.parse(localStorage.getItem('user')).need_identification;
		console.log(vm.need_identification);

		vm.getMotions = getMotions;

		vm.emptyMotionsArray = false;

		vm.motion_filters = {
			take: 100,
			limit: 100,
			page: ''
		}

		vm.orderByFilters = [
			   // {name: "Popularity" 		,query: "search_query_popularity"}, 
			   {name: "Oldest"     		,query: {oldest: true}},
			   {name: "Newest"	   		,query: {newest: true}},
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
			vm.searchOpened = !vm.searchOpened;
			vm.showSearchFilter = !vm.showSearchFilter;
		}

		function switchLoading(bool, id){
			vm.motion_is_loading[id] = bool;
		}


		function getMotions(){
			motion.getMotions(vm.motion_filters).then(function(results) {
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


		// make this into a service maybe?
		vm.cycleVote = function(motion){
			if(motion.votes[0] == undefined){
				castVote(motion.id);
			}

			else{
				var data = {
	                id: motion.votes[0].id,
	                position: null
	            }
				if(motion.votes[0].position != 1){
					data.position = motion.votes[0].position + 1; 
				}
				else {
					data.position = -1;
				}

				updateVote(data);
			}

		}

		function castVote(id){
			// start at abstain
			vote.castVote({motion_id:id, position:0}).then(function(result){
				getMotions();
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(result) {
				getMotions();
			});
		}

		getMotions();
		switchLoading(true, $stateParams.id);

		$rootScope.$on('sidebarLoadingFinished', function(events, data) {
			switchLoading(data.bool, data.id);
		});


		$rootScope.$on('refreshMotionSidebar', function(events, data) {
			getMotions();
		});     

	}



})();