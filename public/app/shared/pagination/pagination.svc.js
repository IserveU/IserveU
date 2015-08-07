(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('PaginationService', PaginationService);

	function PaginationService($window) {
 		
 		var vm = this;

 		vm.loadMoreStuff = loadMoreStuff;

 		function loadMoreStuff($window){
			angular.element($window).bind("scroll", function() {
			    var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
			    var body = document.body, html = document.documentElement;
			    var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
			    var windowBottom = windowHeight + window.pageYOffset;
			    if(windowBottom >= docHeight - 200){
			        return true;
			    }
			    else {
			    	return false;
			    }
		});
 		}
	    

	}	
})();
