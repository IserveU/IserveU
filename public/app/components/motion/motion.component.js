(function() {

  'use strict';

  angular
    .module('app.motions')
    .component('motionComponent', {
      templateUrl:'app/components/motion/motion.tpl.html',
      controller: MotionController,
      bindings: {
        motion: '<'
      }
    });

  MotionController.$inject = [
    '$rootScope',
    '$state',
    'Authorizer',
    'Motion',
    'MotionResource',
    'MotionIndex',
    'ToastMessage',
    'Settings'
  ];

  function MotionController($rootScope, $state, Authorizer, Motion, MotionResource, MotionIndex, ToastMessage, Settings) {

    /**
     *  Prototypical function from Motion model to load motion.
     *  Attempts to get from MotionIndex object, if there is
     *  no motion it will pull from the API and create a new
     *   Motion model and reinsert that into the index.
     */
    // $scope.motion = Motion.get($stateParams.id);
    //fetch userCommentVote for the associated partials with motion to use.
    
    this.$onInit = function() {
      this.create = create;
      this.edit = edit;
      this.destroy = destroy;
      this.isThisUsers = isThisUsers;
      this.showVoteView = Settings.get('voting.on');
      this.showCommentView = Settings.get('comment.on');
    };

    var create = function() {
      $state.go('create-motion');
    }

    var edit = function() {
      $state.go('edit-motion', {
        id: this.motion.id
      });
    }

    var destroy = function() {
      ToastMessage.destroyThis("motion", function() {
        MotionResource.deleteMotion(this.motion.id).then(function() {
          $state.go('home', {}, {
            reload: true
          });
          MotionIndex._load();
        });
      });
    }

    var isThisUsers = function(user) {
      if (Authorizer.canAccess('administrate-motion'))
        return true;
      else if ($rootScope.userIsLoggedIn && ($rootScope.authenticatedUser.id === user))
        return true;
      return false;
    }

  }

})();
