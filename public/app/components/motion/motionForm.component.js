(function () {
  'use strict'

  angular
    .module('app.motions')
    .component('motionFormComponent', {
      bindings: {
        departments: '<',
        motion: '<',
        existingMotionFiles: '<'
      },
      controller: MotionFormController,
      templateUrl: 'app/components/motion/form/motionForm.tpl.html'
    });

  MotionFormController.$inject = [
    '$state',
    '$translate',
    '$timeout',
    'isuSectionProvider',
    'Motion',
    'MotionResource',
    'ToastMessage',
    'MotionFilesFactory',
    'Settings',
    'Authorizer',
  ];

  function MotionFormController ($state, $translate, $timeout, isuSectionProvider, Motion, MotionResource, ToastMessage, MotionFilesFactory, Settings, Authorizer) {
    var self = this

    self.createMotion = $state.current.name === 'create-motion'
    // self.departments = []
    // self.existingMotionFiles = []
    // self.motion = new Motion({
    //   closing_at: new Date(+new Date() + 12096e5),
    //   status: 'draft'
    // })
    self.motionFile = MotionFilesFactory
    self.motionFiles = []
    self.processing = false
    self.cancel = cancel
    self.submitForReview = submitForReview
    self.triggerSpinner = triggerSpinner
    self.successHandler = successHandler
    self.saveMotion = saveMotion
    self.allowClosing = Settings.get('motion.allow_closing')

    function cancel () {
      ToastMessage.cancelChanges(function () {
        if (self.createMotion) {
          navigateAway()
        } else {
          $state.go('motion', { id: self.motion.id })
        }
      })
      var navigateAway = function () {
        MotionResource.deleteMotion(self.motion.id)
        $state.go('dashboard')
      }
    }

    function submitForReview () {
      self.motion.status = 'review'
      self.saveMotion()
    }

    function successHandler (r) {
      /** deprecrated */
      // MotionFilesFactory.attach(r.id, self.motionFiles);
      
      if (self.motion.id) {
        self.motion.setData(r).refreshExtensions() 
        ToastMessage.simple('You successfully updated this ' + $translate.instant('MOTION'))
      } else if (!Authorizer.canAccess('edit-motion')) {
        ToastMessage.simple('You successfully created a ' + $translate.instant('MOTION'))
      } else {
        ToastMessage.simple('Your submission has been sent in for review!')
      }

      // TODO!
      // self.motion.setData(r).refreshExtensions() 
    
      
      // })();
      $state.go('motion', ({ id: r.data.slug || r.data.id }), { reload: true })
    }

    function triggerSpinner (val) {
      self.processing = val || !self.processing
    }

    /** Initializing function to get motion data. */
    // (function init () {
      // Deprecated : done init ui-routerstate resolve
      // MotionDepartmentResource.getDepartments().then(function (success) {
      //   self.departments = success.data
      // });

      // // if edit-motion
      // if (self.createMotion) { return false }

      // self.motion = Motion.get($stateParams.id)
      // FileResource.getFiles($stateParams.id).then(function (r) {
      //   self.existingMotionFiles = r
      // })
    // })()

    function saveMotion (motion) {

      var opts, sanitizedMotion;

      self.triggerSpinner(true)

      opts = {
        target: '/api/motion/' + (self.motion.id || ''),
        method: (self.motion.id ? 'PATCH' : 'POST')
      };

      angular.extend(isuSectionProvider.defaults, opts)
      sanitizedMotion = self.motion._sanitize()

      isuSectionProvider.callMethodToApi(sanitizedMotion).then(function(success) {
        self.triggerSpinner(false)
        self.successHandler(success)
      }, function (error) {
        console.log('was an error: ', error);
        self.triggerSpinner(false)
      })
    }
  }

    // function motionFormLink (scope, el, attrs, ctrl) {
    //   var autopost = !!angular.element(el).attr('autopost')

    //   if (autopost) {
    //     angular.extend(isuSectionProvider.defaults, {
    //       target: '/api/motion',
    //       method: 'POST'
    //     })

    //     var motion = ctrl.motion._sanitize()
    //     isuSectionProvider.callMethodToApi(motion).then(function (success) {
    //       $stateParams.id = success.id
    //       self.motion = new Motion(success)
    //     }, function (error) {})
    //   }

    //   el.bind('submit', function (ev) {
    //     ev.preventDefault()
    //     ctrl.saveMotion()
    //   })
    // }

})()
