(function() {

  'use strict';

  angular
    .module('app.motions')
    .run(motionsRun);

  motionsRun.$inject = ['Router'];

  function motionsRun(Router) {

    Router
      .state('motion', {
        url: '/motion/:id',
        component: 'motionComponent',
        data: {
          requireLogin: true,
          moduleMotion: false
        },
        resolve: {
          motion: ['Motion', '$transition$', function(Motion, $transition$) {
            return Motion.get($transition$.params().id);
          }]
        }
      })
      .state('edit-motion', {
        url: '/edit-motion/:id',
        component: 'motionFormComponent',
        data: {
          requireLogin: true,
          moduleMotion: true
        },
        resolve: {
          departments: ['MotionDepartmentResource', function(MotionDepartmentResource) {
            return MotionDepartmentResource.getDepartments();
          }],
          motion: ['Motion', '$transition$', function(Motion, $transition$) {
            return Motion.get($transition$.params().id);
          }],
          existingMotionFiles: ['FileResource', '$transition$', function(FileResource, $transition$) {
            return FileResource.getFiles($transition$.params().id);
          }]
        }
      })
      .state('create-motion', {
        url: '/create-motion',
        // template: '<motion-form autopost="true"></motion-form>',
        component: 'motionFormComponent',
        data: {
          requireLogin: true,
          moduleMotion: true
        },
        resolve: {
          departments: ['MotionDepartmentResource', function(MotionDepartmentResource) {
            return MotionDepartmentResource.getDepartments();
          }],
          motion: ['Motion', function(Motion) {
            return new Motion({closing_at: new Date(+new Date() + 12096e5), status: 'draft'});
          }]
        }
      });

  }

})();