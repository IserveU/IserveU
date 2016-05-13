(function() {
	
	angular
		.module('iserveu')
		.directive('bioSection', bioSection)
		.directive('budgetSection', budgetSection);

	function bioSection() {

		return {
			replace:true,
			template: ['<div layout="column" flex layout-padding">',
						'<div layout="row" layout-sm="column" layout-xs="column" flex>',

							'<md-input-container>',
							  	'<md-icon class="mdi mdi-account-circle"></md-icon>',
							 	'<input type="file" name="sections[0].content.image.file" ng-model="sections[0].content.image.file"/>',
							'</md-input-container>',
					
							'<md-input-container flex>',
								'<label>Website</label>',
								'<input type="text" ng-model="form.motion.sections[0].content.subheading"/>',
							'</md-input-container>',
						
						'</div>',

						'<div layout="row"  layout-sm="column"  layout-xs="column"  flex>',
							'<md-input-container flex>',
								'<label>Details</label>',
								'<input type="text"  placeholder="Name, company name, position" ng-model="form.motion.sections[0].content.heading"/>',
							'</md-input-container>',
						'</div>',

				        '<md-input-container class="md-block">',
				          '<label>Biography</label>',
				          '<textarea ng-model="form.motion.sections[0].content.text" md-maxlength="150" rows="5" md-select-on-focus></textarea>',
				        '</md-input-container>',
						'<input type="hidden" ng-init="form.motion.sections[0].type = \'Profile\'" ng-model="form.motion.sections[0].type"',
						'<input type="hidden" ng-init="form.motion.sections[0].order = 1" ng-model="form.motion.sections[0].order"',
					'</div>'].join('')
		}
	}

	function budgetSection() {

		return {
			replace: true,
			template: [ '<div layout="row" layout-padding flex>',
						'<md-input-container flex>',
							'<label>Price</label>',
							'<md-icon class="mdi mdi-currency-usd"></md-icon>',
							'<input type="number" ng-model="form.motion.sections[1].content.price" required/>',
						'</md-input-container>',

						'<md-input-container flex>',
						'<label>Description</label>',
							'<input type="text" ng-model="form.motion.sections[1].content.description" required/>',
						'</md-input-container>',
						'<input type="hidden" ng-init="form.motion.sections[1].type = \'Budget\'" ng-model="form.motion.sections[1].type"',
						'<input type="hidden" ng-init="form.motion.sections[1].order = 0" ng-model="form.motion.sections[1].order"',
						'</div>'].join('')
		}
	}

})();