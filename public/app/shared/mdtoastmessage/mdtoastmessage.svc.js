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

    function simple(message, time){
        var timeDelay = ( time ) ? time : 3000;
        return $mdToast.show(
            $mdToast.simple()
            .content(message)
            .position('bottom right')
            .hideDelay(timeDelay)
        );
    }

    vm.double = function(message1, message2, bool, time){
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

	vm.delete_toast = function(message){
       var toast = $mdToast.simple()
            .content("Are you sure?")
            .action("Delete" + message)
            .highlightAction(true)
            .position('bottom right')
            .hideDelay(6500);
            
        return toast;
    }



	}
})();