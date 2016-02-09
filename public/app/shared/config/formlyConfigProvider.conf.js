/*
*	 DEPRECATED; NO LONGER USING THIS LIBRARY 
*
*////////////////////////////////////////////////

// (function() {
	
// 	'use strict';

// 	angular
// 		.module('iserveu')
// 		.config(


// 	function(formlyConfigProvider){

// 		formlyConfigProvider.disableWarnings = true;
// 	    formlyConfigProvider.setType({
// 		  name: 'input',
// 		  template: '<md-input-container><label>{{options.templateOptions.label | translate}}</label><input ng-model="model[options.key]"></md-input-container>'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'date',
// 		  template: '<md-input-container><input type="date" aria-label="{{options.templateOptions.label | translate}}" label="{{options.templateOptions.label}}" ng-model="model[options.key]" convert-date/></md-input-container>'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'md-switch',
// 		  template: '<md-switch ng-model="model[options.key]" ng-value="options.templateOptions.valueProp" ng-true-value="1" ng-false-value="0"/>{{options.templateOptions.label | translate}}'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'email',
// 		  template: '<md-input-container><label>{{options.templateOptions.label | translate}}</label><input type="email" ng-model="model[options.key]"></md-input-container>'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'password',
// 		  template: '<md-input-container><label>{{options.templateOptions.label | translate}}</label><input type="password" ng-hide="true" ng-model="options.templateOptions.valueProp"/></md-input-container>'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'password-edit',
// 		  template: '<md-button ng-click="options.templateOptions.ngClick()"><label>{{"CHANGE_PASSWORD" | translate}}</label></md-button>'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'select',
// 		  template: '<md-select aria-label="select" ng-model="model[options.key]" placeholder="{{options.templateOptions.label | translate }}"><md-option ng-repeat="item in options.templateOptions.ngRepeat" ng-value="item.id">{{item.region}}<md-tooltip>{{item.description}}</md-tooltip></md-option></md-select>'
// 		});
// 		formlyConfigProvider.setType({
// 		  name: 'userform',
// 		  templateUrl: 'app/components/user/userform.tpl.html'
// 		});

// 	});

// })();