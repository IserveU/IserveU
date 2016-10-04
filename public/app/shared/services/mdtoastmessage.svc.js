(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ToastMessage', ['$rootScope', '$state', '$mdToast', '$timeout', '$translate', 'utils', ToastMessage]);

     /** @ngInject */
	function ToastMessage($rootScope, $state, $mdToast, $timeout, $translate, utils) {

        function simple(message, time){
            var timeDelay = time || 1000;
            return $mdToast.show(
                $mdToast.simple()
                .content(message)
                .position('bottom right')
                .hideDelay(timeDelay)
            );
        }

        function action(message, affirmative, warning, time){
           return $mdToast.simple()
                .content(message)
                .action(affirmative || "Yes")
                .highlightAction(warning || false)
                .position('bottom right')
                .hideDelay(time || 5000);
        }

        function reload(time){

            // time = time || 1000;

            // simple("The page will now refresh.", time);

            // $timeout(function() {
            //     location.reload();
            // }, time * 1.8 );
        }


        function customFunction(message, affirmative, fn, warning){
            var toast = action(message, affirmative, warning || true);
            return $mdToast.show(toast).then(function(r){
                if(r == 'ok') fn();
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

        function destroyThisThenUndo(type, fn, fn2){
            fn();
            var toast = action("You deleted your " + type, "Undo", true);
            $mdToast.show(toast).then(function(r){
                if(r == 'ok'){
                    fn2();
                    simple( utils.capitalize(type) + " restored", 1000 );
                }
            });

        }

        function mustBeLoggedIn(reason){
            return $rootScope.userIsLoggedIn ? false :
            (function(){ return true && customFunction("You must be logged in "+reason, "Go",
                function(){ $state.go('login') })
            })();
        }

        function voteSuccess(type){
            simple("You " + type + ( type === "abstain" ? "ed on" : "d with" ) +
                    " this " + $translate.instant('MOTION') );
        }


        function cancelChanges(fn){
            var toast = action("Discard changes?", "Yes");
            $mdToast.show(toast).then(function(r){
                if(r == 'ok') fn();
            });
        }

        // TODO: implment Error Handler Service
        function report_error(error){

            var toast = action("Sorry, something went wrong.", "Report", true);

            $mdToast.show(toast).then(function(r) {
                if (r == 'ok') {
                    simple("Thanks for your assistance!", 800);
                }
            });
        }

        // exports
        return {
            simple: simple,
            action: action,
            reload: reload,
            customFunction: customFunction,
            destroyThis: destroyThis,
            destroyThisThenUndo: destroyThisThenUndo,
            mustBeLoggedIn: mustBeLoggedIn,
            voteSuccess: voteSuccess,
            cancelChanges: cancelChanges,
            report_error: report_error
        }


	}
})();