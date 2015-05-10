(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.controller('homeController', home);

	function home($scope, $timeout, $mdSidenav, $mdUtil, $log) {

		var vm = this;


		$scope.toggleSidebar = buildToggler('left-nav');
    	
    	$scope.toggleUserbar = buildToggler('user-bar');

		/**
	     * PS: Maybe this should go somewhere else?
	     * Build handler to open/close a SideNav; when animation finishes
	     * report completion in console. 
	     */
	    function buildToggler(navID) {
	    	console.log(navID);

	      var debounceFn =  $mdUtil.debounce(function(){
	            $mdSidenav(navID)
	              .toggle()
	              .then(function () {
	                $log.debug("toggle " + navID + " is done");
	              });
	          },300);
	      return debounceFn;
	    }

	}

})();