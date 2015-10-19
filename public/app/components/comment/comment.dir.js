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
			link: linkMethod,
			controller: controllerMethod,
			controllerAs: 'ctrl',
			bindToController: true
		}

	}


	function agreeCommentSection(){
		return {
		 	templateUrl: 'app/components/comment/templates/agreecomments.tpl.html'			
		}
	}

	function disagreeCommentSection(){
		return {
		 	templateUrl: 'app/components/comment/templates/disagreecomments.tpl.html'			
		}	
	}


}());