(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('settings', settings);

	function settings ($http, auth, refreshLocalStorage) {

		var settingsObj =  {
			initialData: { saving: false },
			getData: function() {
				if (settingsObj.initialData) return settingsObj.initialData;
				else {
					settingsObj.get();
					settingsObj.getData();
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
					settingsObj.initialData = JSON.parse(data);
			},
			save: function(data) {
				console.log(data);
				settingsObj.initialData.saving = true;
				$http.post('/setting', data).success(function(r){
					refreshLocalStorage.setItem('settings', r);
					settingsObj.initialData.saving = false;
				}).error(function(e) {
					console.log(e);
				});
			},
			saveArray: function(name, value) {
				if( angular.isUndefined(value) || value == null || value.length == 0 )
					return 0;

				settingsObj.save({
					'name': name,
					'value': value
				});
			}
		}

		settingsObj.get();

		return settingsObj;

	}


})();