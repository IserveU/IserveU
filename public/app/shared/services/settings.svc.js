(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('settings', ['$http', 'SETTINGS_JSON', 'auth', 'refreshLocalStorage', 
			settings]);

  	 /** @ngInject */
	function settings ($http, SETTINGS_JSON, auth, refreshLocalStorage) {

		var factory = {
			/**
			*	Variable to store settings data. Sub-bool is
			*	front-end spinner.
			*/
			data: angular.extend({}, SETTINGS_JSON, {saving: false}),
			/**
			*	Service accessor. Retrieves set data else it will
			*	retrieve the data and call itself again.
			*/
			getData: function() {
				return this.data;
			},
			/** Post function */
			save: function(data) {
				$http.post('/setting', data).success(function(r){

					refreshLocalStorage.setItem('settings', r);
					factory.data.saving = false;

				}).error(function(e) { console.log(e); });
			},
			/**
			*	Robust check with guard so that you are not submitting
			*	a null/empty/undefined value to the settings array.
			*/
			saveArray: function(name, value) {
				if( angular.isUndefined(value) || value == null || value.length == 0 )
					return 0;

				this.data.saving = true;

				this.save({
					'name': name,
					'value': value
				});
			},
			/**
			*	Organizes the data array into names that correspond
			*	to the key value of Laravel's Settings library.
			*/
			saveTypeOf: function (type, data) {
				if( angular.isString(data) && angular.toJson(data).hasOwnProperty('filename' ))
					data = angular.toJson(data).filename;

				if ( type === 'palette' )
					this.saveArray( 'theme', data.assignThemePalette(data) );
				// else if ( type === 'home')
				// 	console.log(data);
				else 
					this.saveArray( type, data );					
			}
		}

		return factory;

	}


})();