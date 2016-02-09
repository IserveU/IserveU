(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('adminDashboard', adminDashboard);

	function adminDashboard() {

		function adminController() {

			var theme = JSON.parse(localStorage.getItem('settings')).theme;

			this.themeSelect = theme.name;

			this.primary = {
				hue_one: '#'+theme.primary['50'],
				hue_two: '#'+theme.primary['400'],
				hue_three: '#'+theme.primary['700'],
				warning: '#'+theme.primary['A700'],
				contrast: theme.primary['contrastDefaultColor']
			}

			this.accent = {
				hue_one: '#'+theme.accent['50'],
				hue_two: '#'+theme.accent['400'],
				hue_three: '#'+theme.accent['700'],
				contrast: theme.accent['contrastDefaultColor']
			}


			this.saveAppearanceSettings = function() {
				if(this.themeSelect !== theme.name) {
					
					for(var i in this.primary){

						var value_name, hue;

						switch (i) {
							case 'hue_one': 
								hue = 50;
								value_name = 'theme.primary['+hue+']';
								break;
							case 'hue_two':
								hue = 400;
								value_name = 'theme.primary['+hue+']';
								break;
							case 'hue_three':
								hue = 700;
								value_name = 'theme.primary['+hue+']';
								break;
							case 'warning':
								hue = 'A700';
								value_name = 'theme.primary['+hue+']';
								break;
						}

						var data = {
							value: value_name
						}

						settings.save().then(function(r) {

						}, function(e) {

						});
					

					}
				}


			}
		}


		function adminLink() {



		}



		return {

			controller: adminController,
			controllerAs: 'c',
			link: adminLink,
			templateUrl: 'app/components/admin/dashboard.tpl.html'
		}



	}



})();