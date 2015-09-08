(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('ToastMessage', ToastMessageService);

	function ToastMessageService($mdToast) {
	
	var vm = this;

    vm.simple = simple;

	vm.report_error = function(error){
        var toast = $mdToast.simple()
            .content("Something's not working!")
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

    function simple(message){
        return $mdToast.show(
            $mdToast.simple()
            .content(message)
            .position('bottom right')
            .hideDelay(3000)
        );
    }

    vm.double = function(message1, message2, bool){
        simple(message1).then(function(){
            if (bool) { simple(message2); }
        });
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