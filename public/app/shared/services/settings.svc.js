(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('settings', settings);

  	 /** @ngInject */
	function settings ($http, auth, refreshLocalStorage, appearanceService) {

		var factory = {
			/**
			*	Variable to store settings data. Sub-bool is
			*	front-end spinner.
			*/
			initialData: { saving: false },
			/**
			*	Service accessor. Retrieves set data else it will
			*	retrieve the data and call itself again.
			*/
			getData: function() {
				if (this.initialData) return this.initialData;
				else {
					this.get();
					this.getData();
				}
			},
			/** Retrieves settings data */
			get: function() {
				var data = localStorage.getItem('settings');
				if(!data) 
					$http.get('api/setting').success(function(r){
						factory.initialData = r.data;
					}).error(function(e){
						console.log(e);
					});
				else 
					this.initialData = JSON.parse(data);
			},
			/** Post function */
			save: function(data) {
				$http.post('/setting', data).success(function(r){

					refreshLocalStorage.setItem('settings', r);
					factory.initialData.saving = false;

				}).error(function(e) { console.log(e); });
			},
			/**
			*	Robust check with guard so that you are not submitting
			*	a null/empty/undefined value to the settings array.
			*/
			saveArray: function(name, value) {
				if( angular.isUndefined(value) || value == null || value.length == 0 )
					return 0;

				this.initialData.saving = true;

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

				if( angular.isString(data) && JSON.parse(data).filename )
					data = JSON.parse(data).filename;

				if ( type === 'palette' )
					this.saveArray( 'theme', appearanceService.assignThemePalette(data) );
				// else if ( type === 'home')
				// 	console.log(data);
				else 
					this.saveArray( type, data );					
			}
		}

		factory.get();

		return factory;

	}


})();