'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.utils')
    .factory('ToastMessage', ToastMessage);

  ToastMessage.$inject = ['$rootScope','$state','$mdToast','$timeout','$translate','Utils','Authorizer']
  function ToastMessage($rootScope, $state, $mdToast,
    $timeout, $translate, Utils, Authorizer) {

    function simple(message, time) {
      var timeDelay = time || 1000;
      return $mdToast.show(
        $mdToast.simple()
          .content(message)
          .position('bottom right')
          .hideDelay(timeDelay)
      );
    }

    function action(message, affirmative, warning, time) {
      return $mdToast.simple()
        .content(message)
        .action(affirmative || 'Yes')
        .highlightAction(warning || false)
        .position('bottom right')
        .hideDelay(time || 5000);
    }

    function reload(time) {

        // time = time || 1000;

        // simple('The page will now refresh.', time);

        // $timeout(function() {
        //     location.reload();
        // }, time * 1.8 );
    }


    function customFunction(message, affirmative, fn, warning) {
      var toast = action(message, affirmative, warning || true);
      return $mdToast.show(toast).then(function(r) {
        if (r === 'ok') fn();
      });
    }

    function destroyThis(type, fn) {
      var toast = action('Destroy this ' + $translate.instant(type) + '?', 'Yes', true);
      $mdToast.show(toast).then(function(r) {
        if (r === 'ok') {
          fn();
          simple(utils.capitalize($translate.instant(type)) + ' destroyed', 1000);
        }
      });
    }

    function destroyThisThenUndo(type, fn, fn2) {
      fn();
      var toast = action('You deleted your ' + type, 'Undo', true);
      $mdToast.show(toast).then(function(r) {
        if (r === 'ok') {
          fn2();
          simple(utils.capitalize(type) + ' restored', 1000);
        }
      });
    }

    function mustBeLoggedIn(reason) {
      if ($rootScope.userIsLoggedIn) {
        return false;
      } else {
        (function() {
          return true && customFunction('You must be logged in ' + reason, 'Go',
            function() {$state.go('login');});
        })();
      }
    }
    
    /**
     * Shows a toast message saying a message if the user does not have permission
     * this should go into a permissions system in the future
     * @param  {string} permission Permission string 'create-vote' or 'show-user'
     * @param  {string} reason     The translate string shown if they are rejectexd
     * @return {boolean}           if they have the permission
     */
    function mustHavePermission(permission,reason) {
      if(!Authorizer.canAccess(permission)){
        if(reason===undefined){
          reason = Authorizer.permissionToTranslateKey(permission);
        }
        simple($translate.instant(reason));
        return false;
      }
      return true;      
    }
    
    /**
     * Shows a defaul toast message with the correct translation key
     * @param  {string} key The key to use
     */
    function translate(key) {
      simple($translate.instant(key));
    }

    function cancelChanges(fn) {
      var toast = action('Discard changes?', 'Yes');
      $mdToast.show(toast).then(function(r) {
        if (r === 'ok') fn();
      });
    }

    // TODO: implment Error Handler Service
    function report_error(error) {

      var toast = action('Sorry, something went wrong.', 'Report', true);

      $mdToast.show(toast).then(function(r) {
        if (r === 'ok') {
          simple('Thanks for your assistance!', 800);
        }
      });
    }

    // exports
    return {
      simple: simple,
      translate: translate,
      action: action,
      reload: reload,
      customFunction: customFunction,
      destroyThis: destroyThis,
      destroyThisThenUndo: destroyThisThenUndo,
      mustBeLoggedIn: mustBeLoggedIn,
      mustHavePermission: mustHavePermission,
      cancelChanges: cancelChanges,
      report_error: report_error
    };


  }
})(window, window.angular);
