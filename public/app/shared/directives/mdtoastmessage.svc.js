(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ToastMessage', ToastMessage);

     /** @ngInject */
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

        function action(message, affirmative, warning){
           var toast = $mdToast.simple()
                .content(message)
                .action(affirmative)
                .highlightAction(warning)
                .position('bottom right')
                .hideDelay(5000);
                
            return toast;
        }

        function reload(time){
            time = time ? time : 1000;

            simple("The page will now refresh.", time);
            
            $timeout(function() {
                location.reload();
            }, time * 1.8 );
        }


        function customFunction(message, affirmative, fn){
            var toast = action(message, affirmative);
            $mdToast.show(toast).then(function(r){
                if(r == 'ok')
                    fn();
            });
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

        // exports
        return {
            simple: simple,
            action: action,
            reload: reload,
            customFunction: customFunction,
            destroyThis: destroyThis,
            cancelChanges: cancelChanges,
            report_error: report_error
        }


	}
})();