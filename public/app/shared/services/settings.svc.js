(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('settings', settings);

  	 /** @ngInject */
	function settings ($http, auth, refreshLocalStorage, appearanceService) {

		var settingsObj =  {
			initialData: { saving: false },
			getData: function() {
				if (this.initialData) return this.initialData;
				else {
					this.get();
					this.getData();
				}
			},
			get: function() {
				var data = localStorage.getItem('settings');
				if(!data) 
					$http.get('api/setting').success(function(r){
						settingsObj.initialData = r.data;
					}).error(function(e){
						console.log(e);
					});
				else 
					this.initialData = JSON.parse(data);
			},
			save: function(data) {
				console.log(data);
				$http.post('/setting', data).success(function(r){

					refreshLocalStorage.setItem('settings', r);
					settingsObj.initialData.saving = false;

				}).error(function(e) { console.log(e); });
			},
			saveArray: function(name, value) {
				if( angular.isUndefined(value) || value == null || value.length == 0 )
					return 0;

				this.initialData.saving = true;

				this.save({
					'name': name,
					'value': value
				});
			},
			saveTypeOf: function (type, data) {

				if( angular.isString(data) && JSON.parse(data).filename )
					data = JSON.parse(data).filename;

				if ( type === 'palette' )
					this.saveArray( 'theme', appearanceService.assignThemePalette(data) );
				else 
					this.saveArray( type, data );					
			}
		}

		settingsObj.get();

		return settingsObj;

	}


})();