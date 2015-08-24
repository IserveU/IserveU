(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('PaginationService', PaginationService);

	function PaginationService($window) {
 		
		var vm = this;
		vm.loadMoreStuff = loadMoreStuff;
		vm.loadMoreStuffSidebar = loadMoreStuffSidebar;

		function checkWindow(){
			
		}

 		function loadMoreStuff($window){
			angular.element($window).bind("scroll", function() {
			    var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
			    var body = document.body, html = document.documentElement;
			    var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
			    var windowBottom = windowHeight + window.pageYOffset;
				if (windowBottom >= docHeight - 200) {
					return true;
			   	 }
			});
 		}

 		function loadMoreStuffSidebar(){
 			angular.element(document.getElementById('sidebar-outer')).bind("scroll", function() {
	          var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
	          var html = document.getElementById('sidebar-outer');
	          var docHeight = html.clientHeight;
	          var windowBottom = windowHeight + document.getElementById('sidebar-outer').scrollTop;
	          if(windowBottom >= docHeight){
	            return true;
	          }
         });

 		}
	    
	    return {
	    	loadMoreStuff: loadMoreStuff,
	    	loadMoreStuffSidebar: loadMoreStuffSidebar
	    }

	}	
})();
