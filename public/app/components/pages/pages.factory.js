(function(){

	'use strict';


	angular
		.module('app.pages')
		.factory('Page', ['$http', '$state', '$stateParams', PageFactory]);

  	 /** @ngInject */
	function PageFactory($http, $state, $stateParams) {

		var Page = {
			title: '',
			text: '',
			slug: '',
			index: {},
			pageLoading: true,
			processing: false,
			load: function(slug) {
				Page.pageLoading = true;

				$http.get('/api/page/'+slug).then(function(r){

					var body = r.data || r;

					if(body){
						Page.id = body.id;
						Page.title = body.title;
						Page.text = body.text;
						Page.slug = body.slug;
						$stateParams.slug = body.slug;
					}

					Page.pageLoading = false;
				});
			},
			getIndex: function() {
				return $http.get('/api/page').then(function(r){
					Page.index = r.data;
					return r;
				});
			},
			create: function(data) {
				return $http.post('/api/page', data).then(function(r){
					return r;
				});
			},
			destroy: function(slug) {
				return $http.delete('/api/page/'+slug).then(function(r){
					return r;
				});
			},
			save: function(data) {
				$http.post('/api/page', data).then(function(r){
					var body = r.data || data;
					Page.getIndex();
					Page.processing = false;
					$state.go('pages', {id: body.slug});

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
					var body = r.data || data;
					Page.getIndex();
					Page.processing = false;
					$state.go('pages', {id: body.slug});
				});
			}
		}

		return Page;
	}



})();