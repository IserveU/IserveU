(function(){

	'use strict';


	angular
		.module('iserveu')
		.factory('pageObj', pageObj);

	function pageObj($http) {

		var pageObj = {

			title: '',
			content: '',
			index: {},
			pageLoading: true,
			initLoad: function(type) {
				pageObj.pageLoading = true;

				$http.get('/api/page/'+type).then(function(r){
					pageObj.title = r.data[0].title;
					pageObj.content = r.data[0].content;
					pageObj.pageLoading = false;
				});
			},
			getIndex: function() {
				$http.get('/api/page').then(function(r){
					pageObj.index = r.data;
				});
			},
			save: function(data) {
				$http.post('/api/page', data).then(function(r){
					pageObj.getIndex();
				});
			},
			delete: function(slug) {
				$http.delete('/api/page/'+slug).then(function(r){
					pageObj.getIndex();
				});
			},
			update: function(slug, data) {
				$http.patch('/api/page/'+slug, data).then(function(r){
					pageObj.getIndex();
				});
			}
		
		}


			pageObj.getIndex();

		return pageObj;



	}



})();