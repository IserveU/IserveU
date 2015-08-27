(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('ToastMessage', ToastMessageService);

	function ToastMessageService($mdToast) {
	
	var vm = this;

	vm.report_error = function(error){
        var toast = $mdToast.simple()
            .content("Somethings not working!")
            .action("Report")
            .position('bottom right')
            .hideDelay(3000);
        var toast_error = $mdToast.simple()
            .content("We'll work on it.")
            .position('bottom right')
            .hideDelay(3000);
        $mdToast.show(toast).then(function(response) {
            if (response == 'ok'){
              $mdToast.show(toast_error);
            }
        });		
        // code to store error, or send to jessica 
        console.log(error);
	}

    vm.simple = function(message){
        $mdToast.show(
            $mdToast.simple()
            .content(message)
            .position('bottom right')
            .hideDelay(3000)
        );
    }

	vm.delete_toast = function(message){
       var toast = $mdToast.simple()
            .content(message)
            .action("Undo?")
            .highlightAction(true)
            .position('bottom right')
            .hideDelay(6500);
            
        return toast;
    }



	}
})();