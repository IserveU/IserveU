// depcreated

(function() {


	'use strict';

    angular
      .module('iserveu')
      .directive('motionToolbarSearch', motionToolbarSearch);

     /** @ngInject */
    function motionToolbarSearch(motionSearchFactory) {

    	function controllerMethod($scope) {
	    
    		$scope.search = motionSearchFactory;

      	}

      return {
	      	controller: controllerMethod,
	        templateUrl: 'app/components/motion/components/motion-sidebar/search/motion-toolbar-search.tpl.html'
      }
      
    }
  
})();
