(function() {

	'use strict';

	angular
		.module('app.footer')
		.component('footerComponent', {
      template: `
      <footer layout="row" layout-align="end end" ng-cloak flex>
        <md-button class="md-accent md-raised" ng-hide="$root.userIsLoggedIn && $root.authenticatedUser.agreement_accepted"
        terms-and-conditions ng-click="terms.showContract($event)" flex-sm="50" flex-md="25" flex-gt-md="25">
        Terms &amp; Conditions
        </md-button>
      </footer>
      `
    });
  
}());
