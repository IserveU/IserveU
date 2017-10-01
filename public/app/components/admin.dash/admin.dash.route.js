(function() {

  'use strict';

  angular
    .module('app.admin.dash')
    .run(adminDashboardRun);

  adminDashboardRun.$inject = ['Router'];

  function adminDashboardRun(Router) {

    Router.state('admin', {
      url: '/dashboard',
      templateUrl: 'app/components/admin.dash/admin.dash.tpl.html',
      data: {
        requireLogin: true,
        requirePermissions: ['administrate-permission', 'create-motion', 'delete-user']
      }
    });

  }

})();

