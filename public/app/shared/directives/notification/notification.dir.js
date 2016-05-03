(function() {
	
	angular
		.module('iserveu')
		.directive('notificationTemplate',  ['notificationService', notificationTemplate]);

	function notificationTemplate(notificationService) {

		function notificationTemplateController($element) {
			
			this.close = function() {
				$element.remove();
			}

			this.text = notificationService.getNotificationText($element);
			this.primaryButton = notificationService.primaryButton;

		}


		return {
			transclude: true,
			controller: ['$element', notificationTemplateController],
			controllerAs: 'notify',
			templateUrl: 'app/shared/directives/notification/notification.tpl.html'
		}


	}

})();