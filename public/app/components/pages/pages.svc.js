(function(){

	'use strict';


	angular
		.module('iserveu')
		.factory('pageService', ['$http', '$state', pageServiceFactory]);

  	 /** @ngInject */
	function pageServiceFactory($http, $state) {

		var Page = {

			title: '',
			content: '',
			slug: '',
			index: {},
			pageLoading: true,
			processing: false,
			initLoad: function(type) {
				Page.pageLoading = true;

				$http.get('/api/page/'+type).then(function(r){

					if(r.data[0]){
						Page.title = r.data[0].title;
						Page.content = r.data[0].content;
						Page.slug = r.data[0].slug;
					}

		
					Page.pageLoading = false;
				});
			},
			getIndex: function() {
				$http.get('/api/page').then(function(r){
					Page.index = r.data;
				});
			},
			save: function(data) {
				console.log(data);
				$http.post('/api/page', data).then(function(r){
					Page.getIndex();
					Page.processing = false;
					$state.go('pages', {id: r.slug});

				});
			},
			delete: function(slug) {
				$http.delete('/api/page/'+slug).then(function(r){
					Page.getIndex();
					$state.go('dashboard');
					Page.processing = false;
				});
			},
			update: function(slug, data) {
				$http.patch('/api/page/'+slug, data).then(function(r){
					Page.getIndex();
					Page.processing = false;
					$state.go('pages', {id: r.slug});
				});
			}
		}


		Page.getIndex();

		return Page;



	}



})();