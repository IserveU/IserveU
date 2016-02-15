(function() {


	'use strict';

    angular
      .module('iserveu')
      .directive('motionSearchbar', motionSidebarSearch);


    // TODO: start refactoring and cleaning up the code. Simplifying.
    function motionSidebarSearch($timeout, department, motionObj, motion, searchFactory) {

    	function controllerMethod() {
    		
        	var vm = this;

    		vm.departmentObj = department.self;

    		vm.orderByFilters = [
			   // {name: "Popularity" 		,query: "search_query_popularity"}, 
			   {name: "Newest"     		,query: {oldest: true}},
			   {name: "Oldest"	   		,query: {newest: true}},
			   // {name: "Open for Voting" ,query: {is_active:true, is_current:true}},   // CHECK PHP, not working.
			   {name: "Closed"			,query: {is_expired:true}}
			];


			vm.motion_filters = {
				take: 100,
				limit: 100,
				next_page: 1,
				oldest: true,
				is_active: true,
				is_current: true
			}

			vm.departmentFilter = {id: ''};
			vm.orderByFilter;
			vm.newFilter = [];

			vm.showSearch = false;

			vm.searchText = '';
			vm.searching  = false;

			vm.searchInput = function() {
				searchFactory.text = vm.searchText;
			}

			vm.querySearch = function(filter){

				vm.searching = true;

				var temp_arr = Object.getOwnPropertyNames(filter);
				temp_arr.pop();			//removes $mdSelect event thats bundled with var filter
				emptyMotionFilters();

				angular.forEach(temp_arr, function(fil, key){
					vm.motion_filters[fil] = true;
				})

				if(angular.isNumber(vm.departmentFilter)){
					filter['department_id'] = vm.departmentFilter;
					vm.motion_filters.push(vm.departmentFilter);
				}
				return motion.getMotions(filter).then(function(result){
					vm.newFilter = filter;
					vm.searching = false;
					return motionObj.data = result.data;
				})
			}

			vm.querySearchDepartment = function(filter) {

				vm.newFilter['department_id'] = filter.department_id;
				emptyMotionFilters();
				vm.motion_filters['department_id'] = filter.department_id;

				vm.searching = true;

				return motion.getMotions(vm.newFilter).then(function(result){
					vm.newFilter = vm.newFilter;
					vm.searching = false;
					return motionObj.data = result.data;
				})
			}

			function emptyMotionFilters() {
				var temp_filters = vm.motion_filters;

				vm.motion_filters		   = [];
				vm.motion_filters['take']  = temp_filters.take;
				vm.motion_filters['limit'] = temp_filters.limit;
				vm.motion_filters['next_page']  = temp_filters.next_page;
			}

			vm.showSearchFunc = function(){
				if(vm.showSearch)
					vm.searchText = searchFactory.text = '';

				vm.showSearch = !vm.showSearch;
			}

			function getMotions(filter){			
				
				vm.searching = true;
				motion.getMotions(filter).then(function(results) {
					motionObj.data = results.data;
					motionObj.next_page = null;
					vm.searching = false;
				});
			};

			vm.showAll = function() {
				vm.departmentFilter = '';
				vm.orderByFilter 	= '';
				emptyMotionFilters();
				getMotions(vm.motion_filters);
			}

      }

      return {
	      	controller: controllerMethod,
	        controllerAs: 'search',
	        templateUrl: 'app/components/motion/components/motion-sidebar/partials/motion-sidebar-search.tpl.html'
      }
      
    }
  
})();
