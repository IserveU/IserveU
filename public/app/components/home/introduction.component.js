(function() {
  
  angular
    .module('app.home')
    .component('introductionComponent', {
      bindings: {
        homeData: '<'
      },
      template: `
        <div layout-margin>
          <div class="md-title" ng-bind-html="$ctrl.homeData[0].title | trustAsHtml"></div>
          <div class="md-body-1" ng-bind-html="$ctrl.homeData[0].text | trustAsHtml"></div>
        </div>
      `
    });
})();