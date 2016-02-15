(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('settings', settings);

	function settings ($http, auth) {

		var settingsObj =  {
			data: {},
			get: function() {
				var data = localStorage.getItem('settings');
				if(!data) 
					$http.get('api/setting').success(function(r){
						settingsObj.data = r.data;
					}).error(function(e){
						console.log(e);
					});
				else 
					settingsObj.data = JSON.parse(data);
			},
			save: function(data) {
				$http.post('/setting', data).success(function(r){
					localStorage.removeItem('settings');
					localStorage.setItem('settings', JSON.stringify(r.data));
				}).error(function(e) {
					console.log(e);
				});
			}
		}

		settingsObj.get();

		return settingsObj;

	}


})();