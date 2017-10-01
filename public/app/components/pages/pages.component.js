(function() {

	'use strict';

	angular
		.module('app.pages')
		.component('pageComponent', {
      bindings: { $transition$: '<' },
      controller: PageController,
      transclude: true,
      template: `
      TODO: Pages component is working heh
        <section layout-margin>
          <md-card ng-class="$ctrl.service.pageLoading ? $ctrl.loading : none " flex>
            <md-card-content layout-padding>
              <p ng-bind-html="$ctrl.service.text | trustAsHtml" layout-padding></p>
            </md-card-content>
          </md-card>
        </section>


        <floating-button  has-permission="administrate-motion" class="motion_fab"
            init-buttons="['create', 'edit', 'delete']"
            on-create="$ctrl.create()"
            on-edit="$ctrl.edit()"
            on-delete="$ctrl.destroy()">
        </floating-button>`
    });

  PageController.$inject = ['$stateParams', 'Page'];
	function PageController($stateParams, Page) { // , UserbarService, ToastMessage

		this.service = Page;
		this.loading = "loading";
		this.create  = create;
		this.edit    = edit;
		this.destroy = destroy;

		function create() {
			$state.go('create-page');
		}

		function edit() {
			$state.go('edit-page', {id: Page.slug});
		}

		function destroy() {
			ToastMessage.destroyThis("page", function() {
				Page.delete($stateParams.id);
			});
		}

    //  this.$onInit = () => {
    //   // let to = this.$transition$.to();
    //   // let toParams = this.$transition$.params("to");
    //   // let from = this.$transition$.from();
    //   // let fromParams = this.$transition$.params("from");
    //   // do some init stuff
    // }
		(function init() {
      console.log('fuck me');
      // put this into Router
			// UserbarService.title = Page.title;
      // console.log(this.$transition$.params());
			// Page.load();
		})();
	}



})();