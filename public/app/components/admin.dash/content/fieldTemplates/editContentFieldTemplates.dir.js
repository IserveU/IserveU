(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editSiteNameField', editSiteNameField)
		.directive('editSocialMediaField', editSocialMediaField)

	function editSiteNameField() {
		return {
			template: `<form name="editName" layout="row" flex>
						<md-input-container>
						  <label>Homepage</label>
						  <input type="url" ng-model="settingsGlobal.site.address" format-http>
						</md-input-container>
						<md-input-container>
						  <label>Twitter</label>
						  <input type="url" ng-model="settingsGlobal.site.twitter" format-http>
						</md-input-container>
						<md-input-container>
						  <label>Facebook</label>
						  <input type="url" ng-model="settingsGlobal.site.facebook" format-http>
						</md-input-container>
			           </form>`
		}
	}
	function editSocialMediaField() {
			return {
				template: `<form name="editName" layout="row" flex>
							<md-input-container>
					            <label>Homepage</label>
					            <input type="text" ng-model="profile.address">
						    </md-input-container>
				           </form>`
			}
		}
})();
