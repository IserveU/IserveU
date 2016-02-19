(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('contentManager', contentManager);

	function contentManager($state, pageObj, settings, dropHandler, ToastMessage) {

		function contentController() {

			this.pageObj = pageObj;

			this.settings = settings.getData();

			this.dropHandler = 	dropHandler;

			this.createNewPage = function() {
				this.create_new_page = !this.create_new_page;
			};

			this.editPage = function(slug) {
				$state.go( 'edit-page', {id: slug} );
			};

			this.deletePage = function(slug) {
				ToastMessage.destroyThis("page", function() {
					pageObj.delete(slug);
				});
			};

			// TODO: export this into a service singleton
			this.save = function(type) {
				if(type === 'jargon') {
					settings.saveArray( 'jargon.en', this.settings.jargon.en );
					settings.saveArray( 'jargon.fr', this.settings.jargon.fr );
				} 
				else if (type === 'home')
					settings.saveArray( type+'.widgets', this.settings.home.widgets );
				else if (type === 'module') 
					settings.saveArray( type, this.settings.module );
				else if (type === 'terms') 
					settings.saveArray( 'site.terms', this.settings.site.terms );
				else if (type === 'introduction')
					settings.saveArray( 'home.introduction', this.settings.home.introduction );	
			}
		};


		return {
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin/partials/content-manager/content-manager.tpl.html',
		}


	}


})();