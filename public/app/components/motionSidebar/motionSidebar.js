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

    	function MotionSidebarController() {

    		// global context for this
    		var self = this;

    		self.motionIndex = motionIndex;
    		self.search = motionSearchFactory; 
    		
    		self.closeSidenav = function(id) { 
    			$mdSidenav(id).close(); 
    		};

    		// Initial pull to fill sidebar using motionIndex service.	
    		self.motionIndex._load();

    	};

        return {
        	controller: MotionSidebarController,
        	controllerAs: 'motionSidebar',
          	templateUrl: 'app/components/motionSidebar/motionSidebar.tpl.html'
        }
    
  }
  
})();