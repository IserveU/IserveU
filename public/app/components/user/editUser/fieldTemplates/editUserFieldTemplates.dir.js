(function() {

	'use strict';

	angular
		.module('app.user')
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
				'</form>'
			].join('')
		}
	}

	function editCommunityField() {
		return {
			template: ['<form name="editCommunity">',
				'<md-select placeholder="Which community do you reside in?" ng-model="profile.community_id" required flex>',
				'<md-option ng-repeat="c in communities" ng-value="c.id" required>{{c.name}}</md-option>',
				'</md-select>',
				'</form>'
			].join('')
		}
	}

	function editBirthdayField() {
		return {
			template: ['<form layout="row"  ng-controller="birthdayController as birthday" flex>',
				'<md-input-container>',
				'<label>Month</label>',
				'<md-select ng-model="profile.date_of_birth.month" ng-change="selectMonth(profile.date_of_birth.month, sprofile.date_of_birth.year)"><md-option ng-repeat="month in months" placeholder="Month" ng-value="month">{{month.name}}</md-option></md-select>',
				'</md-input-container>',
				'<md-input-container>',
				'<label>Day</label>',
				'<md-select ng-model="profile.date_of_birth.day"><md-option ng-repeat="day in days" placeholder="Day" ng-value="day">{{day}}</md-option></md-select>',
				'</md-input-container>',
				'<md-input-container>',
				'<label>Year</label>',
				'<md-select ng-model="profile.date_of_birth.year" ng-change="selectYear(profile.date_of_birth.month, profile.date_of_birth.year)"><md-option ng-repeat="year in years" placeholder="Year" ng-value="year">{{year}}</md-option></md-select>',
				'</md-input-container>',
				'</form>'
			].join('')
		}
	}

	function editEmailField() {
		return {
			template: ['<form>',
				'<md-input-container>',
				'<label>Email</label>',
				'<input type="email" ng-model="profile.email">',
				'</md-input-container>',
				'</form>'
			].join('')
		}
	}

	function editTelephoneField() {
		return {
			template: ['<form>',
				'<md-input-container>',
				'<label>Telephone</label>',
				'<input type="tel" ng-model="profile.phone">',
				'</md-input-container>',
				'</form>'
			].join('')
		}
	}

	function editAddressField() {
		return {
			template: ['<form layout="column" flex>',
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
				'</form>'
			].join('')
		}
	}

	function editStatusField() {
		return {
			template: ['<form>',
				'<md-select placeholder="Display options" ng-model="profile.status" flex required>',
				'<md-option value="public">Public</md-option>',
				'<md-option value="private">Private</md-option>',
				'</md-select>',
				'</form>'
			].join('')
		}
	}

	function editPasswordField() {
		return {
			template: ['<form layout="column" name="editPassword">',
				'<md-input-container>',
				'<label>New Password</label>',
				'<input name="changePassword" type="password"  ng-minlength="8" ng-model="profile.password">',
				'<span class="error" ng-show="editPassword.changePassword.$error.minlength">Minimum password length : 8 </span>',
				'</md-input-container>',
				'<md-input-container>',
				'<label>Confirm Password</label>',
				'<input type="password"  ng-minlength="8" ng-model="profile.confirm_password" compare-to="profile.password">',
				'<span class="error" ng-show="editPassword.changePassword.$error.minlength">Minimum password length : 8 </span>',
				'</md-input-container>',
				'</form>'
			].join('')
		}
	}
})();