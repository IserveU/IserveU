(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('motionSidebar', 
    	['$timeout',
       '$mdSidenav',
    	 'motionIndex',
    	 'motionSearchFactory',
    	motionSidebar]);
   
  function motionSidebar($timeout, $mdSidenav, motionIndex, motionSearchFactory) {

    	function MotionSidebarController($scope) {
    		
        $scope.$mdSidenav = $mdSidenav;

        /** global context for this */
    		var self = this;
            
        /** @type {exports} */
        self.closeSidenav = closeSidenav;
        self.loadMotions = loadMotions;
    		self.motionIndex = motionIndex;
    		self.search = motionSearchFactory; 

        /**
         * Pull to fill sidebar using motionIndex service. 
         * @return {} 
         */
        function loadMotions() {
          if(Object.keys(self.motionIndex._index).length === 0){
              return self.motionIndex._load();            
          } else {
              return self.motionIndex.loadMoreMotions();
          }
        }

        /**
         * Close sidenav.
         * @param  {string} id $mdSidenav identifier
         * @return {}
         */
    		function closeSidenav(id) { 
    			  $mdSidenav(id).close(); 
    		};

    	};

        return {
        	controller: ['$scope', MotionSidebarController],
        	controllerAs: 'motionSidebar',
          	templateUrl: 'app/components/motionSidebar/motionSidebar.tpl.html'
        }
    
  }
  
})();