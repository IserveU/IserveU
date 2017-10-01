(function() {

  'use strict';

  angular
    .module('app.error-page')
    .component('errorPageComponent', {
      bindings: {
        message: '<'
      },
      template: `
      <page class="home layout-row layout-wrap flex" ng-cloak>
        <div id="centered_content">
          <div class="widget" layout-margin>
            <div class="md-body-1" ng-bind-html="$ctrl.message | trustAsHtml"></div>
          </div>
        </div>
      </page>
      `
    });

})();