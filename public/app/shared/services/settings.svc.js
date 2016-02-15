(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('settings', settings);

	function settings ($http, auth, refreshLocalStorage) {

		var settingsObj =  {
			initialData: {},
			data: function() {
				if (settingsObj.initialData) return settingsObj.initialData;
				else {
					settingsObj.get();
					settingsObj.data();
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
				$http.post('/setting', data).success(function(r){

					console.log(data);

					console.log(r);	
					refreshLocalStorage.setItem('settings', r);
				}).error(function(e) {
					console.log(e);
				});
			},
			saveArray: function(name, value) {
				if( angular.isUndefined(value) || value == null || value.length == 0)
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