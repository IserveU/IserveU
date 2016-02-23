(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ToastMessage', ToastMessage);

	function ToastMessage($mdToast, $timeout, utils) {
	
        function simple(message, time){
            var timeDelay = ( time ) ? time : 1000;
            return $mdToast.show(
                $mdToast.simple()
                .content(message)
                .position('bottom right')
                .hideDelay(timeDelay)
            );
        }

        function double(message1, message2, bool, time){
            simple(message1, time).then(function(){
                if (bool) { simple(message2); }
            });
        }

        function action(message, affirmative, warning){
           var toast = $mdToast.simple()
                .content(message)
                .action(affirmative)
                .highlightAction(warning)
                .position('bottom right')
                .hideDelay(5000);
                
            return toast;
        }

        function reload(){
            simple("The page will now refresh.", 500);
            
            $timeout(function() {
                location.reload();
            }, 1000);
        }

        function destroyThis(type, fn){
            var toast = action("Destroy this " + type + "?", "Yes", true);
            $mdToast.show(toast).then(function(r){
                if(r == 'ok'){
                    fn();
                    simple( utils.capitalize(type) + " destroyed", 1000 );
                }
            });
        }

        function cancelChanges(fn){
            var toast = action("Discard changes?", "Yes");
            $mdToast.show(toast).then(function(r){
                if(r == 'ok') fn();
            });
        }

        // TODO: implment Error Handler Service
        function report_error(error){
            var toast = action("Sorry, something went wrong.", "See", true); 
            var error = action(error.message, "Report");
            var thanks = simple("Thanks for your help! We'll work on it.", 800);

            $mdToast.show(toast).then(function(r) {
                if (r == 'ok')
                    $mdToast.show(error).then(function(r){
                        if (r == 'ok') $mdToast.show(thanks);
                    });
            });
        }

        return {
            simple: simple,
            double: double,
            action: action,
            reload: reload,
            report_error: report_error,
            destroyThis: destroyThis,
            cancelChanges: cancelChanges,
        }


	}
})();