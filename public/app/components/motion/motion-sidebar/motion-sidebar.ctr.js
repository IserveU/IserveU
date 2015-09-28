 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('MotionSidebarController', MotionSidebarController);

	function MotionSidebarController(motion, vote, MotionSidebarService, $rootScope, $stateParams, $filter, $timeout, $state, $scope) {

		var vm = this;

		$scope.$state = $state;

		vm.emptyMotionsArray = false;

		vm.motion_filters = {
			take: 100,
			limit: 100,
			page: ''
		}

		vm.next_page = 1;

		vm.showSearchFilter = false;
		vm.searchOpened = false;
		vm.hide_show_more = true;
		vm.paginate_loading = false;


		vm.showSearch = function(){
			vm.searchOpened = !vm.searchOpened;
			vm.showSearchFilter = !vm.showSearchFilter;
		}

		vm.searchText;

		vm.motion_is_loading = {};

		$rootScope.$on('sidebarLoadingFinished', function(events, data) {
			switchLoading(data.bool, data.id);
		});


		$rootScope.$on('refreshMotionSidebar', function(events, data) {
			getMotions();
		});     

		vm.switchLoading = switchLoading;

		function switchLoading(bool, id){
			vm.motion_is_loading[id] = bool;
		}

		function getMotions(filters){
			console.log(filters);
			motion.getMotions(filters).then(function(results) {
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
			getMotions(vm.motion_filters);
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

		getMotions(vm.filters);
		switchLoading(true, $stateParams.id);

	}



})();