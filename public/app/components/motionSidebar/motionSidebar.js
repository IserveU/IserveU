(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('motionSidebar', 
    	['$mdSidenav',
    	 'motionIndex',
    	 'motionSearchFactory',

    	motionSidebar]);
   
  function motionSidebar($mdSidenav, motionIndex, motionSearchFactory) {

    	function MotionSidebarController($scope) {
    		
            $scope.$mdSidenav = $mdSidenav;

            // global context for this
    		var self = this;
            
            /** @type {exports}  */
    		self.motionIndex = motionIndex;
    		self.search = motionSearchFactory; 

            /**
             * Pull to fill sidebar using motionIndex service. 
             * @return {} 
             */
            self.loadMotions = function() {
                if(Object.keys(self.motionIndex._index).length === 0){
                    self.motionIndex._load();            
                }
                else{
                    self.motionIndex.loadMoreMotions();   
                }
            }

            /**
             * Close sidenav.
             * @param  {string} id $mdSidenav identifier
             * @return {}
             */
    		self.closeSidenav = function(id) { 
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