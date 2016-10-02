(function() {

	'use strict';
	
	angular
		.module('iserveu')
		.directive('editNameField', editNameField)
		.directive('editCommunityField', editCommunityField)
		.directive('editBirthdayField', editBirthdayField)
		.directive('editEmailField', editEmailField)
		.directive('editTelephoneField', editTelephoneField)
		.directive('editAddressField', editAddressField)
		.directive('editStatusField', editStatusField)
		.directive('editPasswordField', editPasswordField);

	function editNameField() {
		return {
			template: ['<form name="editName" layout="row" flex>',
					   '<md-input-container>',
		               '<label>First name</label>',
		               '<input type="text" ng-model="profile.first_name">',
			           '</md-input-container>',
   					   '<md-input-container>',
		               '<label>Middle name</label>',
		               '<input type="text" ng-model="profile.middle_name">',
			           '</md-input-container>',
   					   '<md-input-container>',
		               '<label>Last name</label>',
		               '<input type="text" ng-model="profile.last_name">',
			           '</md-input-container>',
			           '</form>'].join('')
		}
	}
	function editCommunityField() {
		return {
			template: ['<form name="editCommunity">',
				        '<md-select placeholder="Which community do you reside in?" ng-model="profile.community_id" required flex>',
				            '<md-option ng-repeat="c in communities" ng-value="c.id" required>{{c.name}}</md-option>',
				        '</md-select>',
					   '</form>'].join('')
		}
	}
	function editBirthdayField() {
		return {
			template: ['<form>',
			      	   '<md-input-container>',
          			   '<label>Birthday</label>',
					   '<md-datepicker ng-model="profile.date_of_birth" md-placeholder="Birthday" format-birthday flex></md-datepicker>',
					   '</md-input-container>',
					   '</form>'].join('')
		}
	}
	function editEmailField() {
		return {
			template: ['<form>',
					   '<md-input-container>',
					   '<label>Email</label>',
					   '<input type="email" ng-model="profile.email">',
					   '</md-input-container>',
					   '</form>'].join('')
		}
	}
	function editTelephoneField() {
		return {
			template: ['<form>',
					   '<md-input-container>',
					   '<label>Telephone</label>',
					   '<input type="tel" ng-model="profile.phone_number">',
					   '</md-input-container>',
					   '</form>'].join('')
		}
	}
	function editAddressField() {
		return {
			template: ['<form layout="row" flex>',
					   '<md-input-container>',
		               '<label>Street No.</label>',
		               '<input type="text" ng-model="profile.street_number">',
			           '</md-input-container>',
   					   '<md-input-container>',
		               '<label>Street Name</label>',
		               '<input type="text" ng-model="profile.street_name">',
			           '</md-input-container>',
   					   '<md-input-container>',
		               '<label>Apt./Suite</label>',
		               '<input type="text" ng-model="profile.unit_number">',
			           '</md-input-container>',
			           '<md-input-container>',
		               '<label>Postal Code</label>',
		               '<input type="text" ng-model="profile.postal_code">',
			           '</md-input-container>',
			           '</form>'].join('')
		}
	}
	function editStatusField() {
		return {
			template: ['<form>',
			           '<md-select placeholder="Display options" ng-model="profile.status" flex required>',
			           '<md-option value="public">Public</md-option>',
			           '<md-option value="private">Private</md-option>',
			           '</md-select>',
					   '</form>'].join('')
		}
	}
	function editPasswordField() {
		return {
			template: ['<form layout="column">',
					   '<md-input-container>',
					   '<label>New Password</label>',
					   '<input type="password" ng-model="profile.password">',
					   '</md-input-container>',
   					   '<md-input-container>',
					   '<label>Confirm Password</label>',
					   '<input type="password" ng-model="profile.confirm_password" compare-to="profile.password">',
					   '</md-input-container>',
					   '</form>'].join('')
		}
	}
})();