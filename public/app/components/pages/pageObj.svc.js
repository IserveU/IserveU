(function(){

	'use strict';


	angular
		.module('iserveu')
		.factory('pageObj', pageObj);

	function pageObj($http, $state) {

		var pageObj = {

			title: '',
			content: '',
			slug: '',
			index: {},
			pageLoading: true,
			processing: false,
			initLoad: function(type) {
				pageObj.pageLoading = true;

				$http.get('/api/page/'+type).then(function(r){

					if(r.data[0]){
						pageObj.title = r.data[0].title;
						pageObj.content = r.data[0].content;
						pageObj.slug = r.data[0].slug;
					}

		
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
					pageObj.processing = false;
					$state.go('pages', {id: r.slug});

				});
			},
			delete: function(slug) {
				$http.delete('/api/page/'+slug).then(function(r){
					pageObj.getIndex();
					$state.go('dashboard');
					pageObj.processing = false;
				});
			},
			update: function(slug, data) {
				$http.patch('/api/page/'+slug, data).then(function(r){
					pageObj.getIndex();
					pageObj.processing = false;
					$state.go('pages', {id: r.slug});
				});
			}
		}


		pageObj.getIndex();

		return pageObj;



	}



})();