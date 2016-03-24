(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('motionSidebar', motionSidebar);
   
  function motionSidebar() {

  	 /** @ngInject */
	function MotionSidebarController($mdSidenav, motionObj, motionSearchFactory, department) {

		var vm = this;

		/* Variables */
		vm.motionObj		 = motionObj;
		vm.motionListLoading = motionObj.data.length > 0 ? false : true;
		vm.search		 	 = motionSearchFactory; 
		
		/* HTML access to functions */
		vm.loadMoreMotions   = loadMoreMotions;		
		vm.closeSidenav      = function(id){ $mdSidenav(id).close(); }


		/* Pagination function. Runs off of ngInfiniteScroll library. When
		*  the sidebar reaches the bottom, this function is triggered. 
		*  @motionListLoading: boolean for DOM spinner when loading.
		*/
		function loadMoreMotions() {

			vm.motionListLoading = vm.paginating = true;

			motionObj.getMotions().then(function(r){
				vm.motionListLoading = vm.paginating = false;
			});
		};
	
		loadMoreMotions();
	};

    return {
    	controller: MotionSidebarController,
    	controllerAs: 'sidebar',
      	templateUrl: 'app/components/motion/components/motion-sidebar/motion-sidebar.tpl.html'
    }
    
  }
  
})();