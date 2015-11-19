(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('commentSection', commentSection)
		.directive('agreeCommentSection', agreeCommentSection)
		.directive('disagreeCommentSection', disagreeCommentSection);

	function commentSection($compile) {
		function linkMethod(scope, element, attrs) {
			attrs.$observe('hasVoted', function(value) {
				if(value == 'false'){
					element.remove();
				}
			});
		}
		return {
			link: linkMethod
		}
	}


	function agreeCommentSection(){
		return {
		 	templateUrl: 'app/components/comment/partials/agreecomments.tpl.html'			
		}
	}

	function disagreeCommentSection(){
		return {
		 	templateUrl: 'app/components/comment/partials/disagreecomments.tpl.html'			
		}	
	}


}());