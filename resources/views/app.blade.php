<html lang="en" ng-app="LoginApp">
  <head>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/0.9.0/angular-material.min.css">
        <link rel="stylesheet" href="/css/dependencies.css">
        <link rel="stylesheet" href="/css/style.css">   
        <meta name="viewport" content="initial-scale=1" />
  </head>
  <body back-img ng-controller="AppCtrl" layout-align="center center">


    
		@yield('content')


       
    <!-- Angular Material Dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-animate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-aria.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/angular_material/0.9.0/angular-material.min.js"></script>

    <script>
    var app = 	angular.module('LoginApp', ['ngMaterial'])
	.config(function($mdThemingProvider) {

		$mdThemingProvider.definePalette('isuAqua', {
		    '50': '4E6829',
		    '100': '4E6829',
		    '200': '4E6829',
		    '300': '4E6829',
		    '400': '4E6829',
		    '500': '4E6829',
		    '600': '4E6829',
		    '700': '4E6829',
		    '800': '4E6829',
		    '900': '4E6829',
		    'A100': 'ff0000',
		    'A200': 'ff0000',
		    'A400': 'ff0000',
		    'A700': 'ff0000',
		    'contrastDefaultColor': 'light',    
		});
		$mdThemingProvider.definePalette('isuOrange', {
		    '50': '5F2640',
		    '100': '5F2640',
		    '200': '5F2640',
		    '300': '5F2640',
		    '400': '5F2640',
		    '500': '5F2640',
		    '600': '5F2640',
		    '700': '5F2640',
		    '800': '5F2640',
		    '900': '5F2640',
		    'A100': 'ffb473',
		    'A200': 'ff7600',
		    'A400': 'ff7600',
		    'A700': 'a64d00',
		    'contrastDefaultColor': 'light',    
		});

		$mdThemingProvider.theme('default').primaryPalette('isuAqua').accentPalette('isuOrange');

	});

	app.controller('AppCtrl', ['$scope', function($scope, $mdSidenav){


	 
	 
	}]);



	app.directive('backImg', function(){
	    return function(scope, element, attrs){
	        var imgnum =  Math.floor(Math.random() * (18 - 1 + 1)) + 1;
	        if(imgnum<=9){
	        	imgnum = "0"+imgnum;

	        }

	        element.css({
	            'background-image': 'url(/img/photos/large/' + imgnum +'.jpg)',
	            'background-size' : 'cover'
	        });
	    };
	});


</script>
  </body>
</html>
	
	