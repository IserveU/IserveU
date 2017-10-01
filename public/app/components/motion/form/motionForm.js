(function () {
  'use strict'

  angular
    .module('iserveu')
    .directive('motionForm', [
      '$rootScope',
      '$state',
      '$stateParams',
      '$timeout',
      '$translate',
      'isuSectionProvider',
      'Motion',
      'MotionResource',
      'motionFileResource',
      'fileResource',
      'ToastMessage',
      'motionFilesFactory',
      'Authorizer',
      motionForm
    ])

  function motionForm ($rootScope, $state, $stateParams, $timeout, $translate, isuSectionProvider, Motion, MotionResource, motionFileResource, fileResource,
    ToastMessage, motionFilesFactory, Authorizer) {
    function motionFormController ($scope) {
      var self = this

      self.createMotion = $state.current.name === 'create-motion'
      self.departments = []
      self.existingMotionFiles = []
      self.motion = new Motion({
        closing_at: new Date(+new Date() + 12096e5),
        status: 'draft'
      })
      self.motionFile = motionFilesFactory
      self.motionFiles = []
      self.processing = false
      self.cancel = cancel
      self.submitForReview = submitForReview
      self.triggerSpinner = triggerSpinner
      self.successHandler = successHandler
      self.saveMotion = saveMotion

      function cancel () {
        ToastMessage.cancelChanges(function () {
          if (self.createMotion) {
            navigateAway()
          } else {
            $state.go('motion', {
              id: self.motion.id
            })
          }
        })
        var navigateAway = function () {
          MotionResource.deleteMotion($stateParams.id)
          $state.go('dashboard')
        }
      }

      function submitForReview () {
        self.motion.status = 'review'
        self.saveMotion()
      }

      function successHandler (r) {
        /** deprecrated */
        // motionFilesFactory.attach(r.id, self.motionFiles);

        console.log(r);

        $rootScope.preventStateChange = true
        self.motion.setData(r).refreshExtensions()

        if (self.motion.id) {
          ToastMessage.simple('You successfully updated this ' + $translate.instant('MOTION'))
        } else if (Authorizer.canAccess('edit-motion')) {
          ToastMessage.simple('Your submission has been sent in for review!')
        }

        $timeout(function () {
          $state.go('motion', ({
            id: r.slug || r.id
          }), {
            reload: true
          })
        }, 600)
      }

      function triggerSpinner (val) {
        self.processing = val || !self.processing
      }

      /** Initializing function to get motion data. */
      (function init () {

        MotionDepartmentResource.getDepartments().then(function (success) {
          self.departments = success.data
        });

        // if edit-motion
        if (self.createMotion) { return false }

        self.motion = Motion.get($stateParams.id)
        fileResource.getFiles($stateParams.id).then(function (r) {
          self.existingMotionFiles = r
        })
      })()

      function saveMotion () {
        self.triggerSpinner(true)

        angular.extend(isuSectionProvider.defaults,
          (!$stateParams.id) ? {
            target: '/api/motion',
            method: 'POST'
          } : {
            target: '/api/motion/' + $stateParams.id,
            method: 'PATCH'
          })

        var motion = self.motion._sanitize()

        isuSectionProvider.callMethodToApi(motion).then(function (success) {
          console.log('was successful');
          self.triggerSpinner(false)
          self.successHandler(success)
        }, function (error) {
          console.log('was an error: ', error);
          self.triggerSpinner(false)
        })
      }
    }

    function motionFormLink (scope, el, attrs, ctrl) {
      var autopost = !!angular.element(el).attr('autopost')

      if (autopost) {
        angular.extend(isuSectionProvider.defaults, {
          target: '/api/motion',
          method: 'POST'
        })

        var motion = ctrl.motion._sanitize()
        isuSectionProvider.callMethodToApi(motion).then(function (success) {
          $stateParams.id = success.id
          self.motion = new Motion(success)
        }, function (error) {})
      }

      el.bind('submit', function (ev) {
        ev.preventDefault()
        ctrl.saveMotion()
      })
    }

    return {
      link: motionFormLink,
      controller: ['$scope', motionFormController],
      controllerAs: 'form',
      templateUrl: 'app/components/motionForm/motionForm.tpl.html'
    }
  }
})()
