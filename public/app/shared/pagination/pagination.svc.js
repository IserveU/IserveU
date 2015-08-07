(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('PaginationService', PaginationService);

	function PaginationService($window, $injector, $q) {
 		
		var vm = this;
		vm.loadMoreStuff = loadMoreStuff;
		vm.loadMoreStuffSidebar = loadMoreStuffSidebar;

 		var deferred = $q.defer();

 		function loadMoreStuff($window){
 			var result;
			angular.element($window).bind("scroll", function() {
			    var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
			    var body = document.body, html = document.documentElement;
			    var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
			    var windowBottom = windowHeight + window.pageYOffset;
				if (windowBottom < docHeight - 200) {
 					result = false;
					deferred.reject(result);
			    }
			    else if(windowBottom >= docHeight - 200){
			    	result = true;
			    	deferred.resolve(result);
			    }

				});
			return deferred.promise;
 		}

 		// function loadMoreStuffSidebar(){
 		// 	angular.element(document.getElementById('sidebar-outer')).bind("scroll", function() {
	  //         var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
	  //         var html = document.getElementById('sidebar-outer');
	  //         var docHeight = html.clientHeight;
	  //         var windowBottom = windowHeight + document.getElementById('sidebar-outer').scrollTop;
	  //         if(windowBottom >= docHeight){
	  //           console.log("here");
	  //           return true;
	  //         }
	  //         else {
	  //         	return false;
	  //         }
   //       });

 		// }
	    
	    return {
	    	loadMoreStuff: loadMoreStuff,
	    	// loadMoreStuffSidebar: loadMoreStuffSidebar
	    }

	}	
})();
