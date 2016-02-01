(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('ToastMessage', ToastMessageService);

	function ToastMessageService($mdToast) {
	
	var vm = this;

    vm.simple = simple;
    vm.double = double;

	vm.report_error = function(error){
        var toast = $mdToast.simple()
            .content("Sorry, something went wrong.")
            .action("Message")
            .position('bottom right')
            .highlightAction(true)
            .hideDelay(2000);
        var error_message = $mdToast.simple()
            .content(error.message)
            .position('bottom right')
            .action("Report")
            .hideDelay(3000);
        var toast_error = $mdToast.simple()
            .content("Thanks! We'll work on it.")
            .position('bottom right')
            .hideDelay(800);

        $mdToast.show(toast).then(function(response) {
            if (response == 'ok'){
                $mdToast.show(error_message).then(function(response){
                    if (response == 'ok'){
                      $mdToast.show(toast_error);
                    }    
                })
            }            
        });		
        // code to store error, or send to jessica 
	}

    function simple(message, time){
        var timeDelay = ( time ) ? time : 1000;
        return $mdToast.show(
            $mdToast.simple()
            .content(message)
            .position('bottom right')
            .hideDelay(timeDelay)
        );
    }

    function double (message1, message2, bool, time){
        simple(message1, time).then(function(){
            if (bool) { simple(message2); }
        });
    }

    vm.action = function(message1, affirmative){
       var toast = $mdToast.simple()
            .content(message1)
            .action(affirmative)
            .highlightAction(false)
            .position('bottom right')
            .hideDelay(5000);
            
        return toast;
    }

	vm.delete_toast = function(message, affirmative){
       var toast = $mdToast.simple()
            .content(message)
            .action(affirmative)
            .highlightAction(true)
            .position('bottom right')
            .hideDelay(6500);
            
        return toast;
    }



	}
})();