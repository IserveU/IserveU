(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('contentManager', contentManager);

	function contentManager($state, $mdToast, pageObj, settings, fileService, ToastMessage) {


		function contentController() {

			this.pageObj = pageObj;

			this.settings = settings.data;

			this.createNewPage = function() {

				this.create_new_page = !this.create_new_page;

			};

			this.editPage = function(slug) {

				$state.go('edit-page', {id: slug} );
			
			};

			this.deletePage = function(slug) {

				var toast = ToastMessage
						    .delete_toast(
								"Are you sure you want to delete this page?",
								"Yes");

				$mdToast.show(toast).then(function(r) {
					if(r === 'ok') pageObj.delete(slug);
					return 0;
				});
			};

			// TODO: make this into a service to be reused??
			this.dropHandler = 	function(file, insertAction){
  
				var reader = new FileReader();
				if(file.type.substring(0, 5) === 'image'){
					reader.onload = function() {
						if(reader.result !== '')
							fileService.upload(file).then(function(r){
								console.log(r);
								insertAction('insertImage', 'uploads/pages/'+r.data.filename, true);
							}, function(e) {
								console.log(e);
							});
					};


					reader.readAsDataURL(file);
					// NOTE: For async procedures return a promise and resolve it when the editor should update the model.
					return true;
				}
				return false;
			}


		};



		return {
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin/partials/content-manager/content-manager.tpl.html',
		}


	}


})();