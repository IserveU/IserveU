(function() {

	'use strict';

	angular
		.module('iserveu', [
			'ngCookies',
			'ngResource',
			'ngMaterial',
			'ngMessages',
			'ngSanitize', 
			'satellizer',
			'textAngular',
			'ui.router',
			'flow',
            'infinite-scroll',
			'pascalprecht.translate',
			'mdColorPicker'
		])
		.config(['$urlRouterProvider', '$authProvider', '$compileProvider',
			function($urlRouterProvider, $authProvider, $compileProvider) {

				$compileProvider.debugInfoEnabled(false); // speeds up the app, the debug info are for {{}}

				$authProvider.loginUrl = '/authenticate';

			    // the overall default route for the app. If no matching route is found, then go here
			    $urlRouterProvider.otherwise('/home');			
				$urlRouterProvider.when("/user/:id", "/user/:id/profile"); // for displaying sub-url

		}])
		.run(['$rootScope', '$auth', '$window', 'redirect', 'globalService',
			function($rootScope, $auth, $window, redirect, globalService) {
				
				$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	

					redirect.onLogin(toState, toParams, fromState);

					redirect.ifNotAuthenticated(
								event,
								toState.data.requireLogin,
								$auth.isAuthenticated(),
								toState.name,
								fromState.name
							);

					globalService.checkUser();

					globalService.setState( toState );
				});


		        globalService.init();

				$window.onbeforeunload = function(e) {
					var publicComputer = localStorage.getItem('public_computer');
					if(JSON.parse(publicComputer) == true) 
						return localStorage.clear();
				};

		}])

		
}());
(function() {

	'use strict';

    BackgroundImageController.$inject = ["$rootScope", "$state", "$scope", "$timeout", "ToastMessage", "UserbarService", "SetPermissionsService", "backgroundimage"];
	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	/** @ngInject */
	function BackgroundImageController($rootScope, $state, $scope, $timeout, ToastMessage, UserbarService, SetPermissionsService, backgroundimage) {	

		UserbarService.setTitle("Upload");

		var vm = this;

		$scope.$state = $state;

		vm.isactive = 0;
		vm.backgroundimages;
		vm.preview = false;
		vm.ispreviewimage = true;
		vm.uploading = false;
		vm.onSuccess = false;
		vm.showError = false;
		vm.isNotAdmin = true;
		vm.url;
		vm.credited;

		function isAdmin(){
			vm.isNotAdmin = !SetPermissionsService.can('administrate-background_images');
		}

		vm.uploadFile = function(){

		    backgroundimage.saveBackgroundImage(vm.thisFile).then(function(result) {

		    	vm.backgroundimages = '';

		    	vm.onSuccess = true;
		    	vm.uploading = false;
		    	// ToastMessage.double("Upload successful!", "Your image has been sent in for approval!", vm.isNotAdmin);

				$rootScope.$emit('backgroundImageUpdated');

		    },function(error){
		    	vm.uploading = false;
		    	vm.showError = true;
		    });

		}

		vm.upload = function(flow){
			vm.preview = true;

			var fd = new FormData();

			fd.append("background_images", flow.files[0].file);
		    fd.append("credited", vm.credited);
		    if ( vm.url && !/^(http):\/\//i.test(vm.url) ) {
		    	vm.url = 'http://' + vm.url; // appends http: if missing
		    }
		    fd.append("url", vm.url);
		    fd.append('active', vm.isactive);

		    vm.thisFile = fd;

		}

		isAdmin();


    }

}());
(function() {

	'use strict';

	department.$inject = ["$resource", "$http", "$q", "$timeout"];
	angular
		.module('iserveu')
		.factory('department', department);

		// TODO: refactor

	/** @ngInject */
	function department($resource, $http, $q, $timeout) {

		var Department = $resource('api/department/:id', {}, {
	        'update': { method:'PUT' }
	    });

		var self = {
			data: {},
			getData: function() {

				if(self.data.hasOwnProperty(0))
					return self.data;
				else {
					self.initDepartments();
					$timeout(function() {
						self.getData();
					}, 600);
				}
			},
			initDepartments: function() {
				Department.query().$promise.then(function(r) {
					self.data = r;
				});
			},
			getDepartments: function() {
				return $http.get('api/department/').success(function(result) {
					return result.data;
				});
			}
		};

		function addDepartment(data){
			return Department.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteDepartment(id){
			return Department.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateDepartment(data){
			return Department.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		self.initDepartments();

	return {
			self: self,
			addDepartment: addDepartment,
			deleteDepartment: deleteDepartment,
			updateDepartment: updateDepartment,
			get: self.getDepartments
		}




	}

})();
(function() {

	'use strict';

	HomeController.$inject = ["$rootScope", "$scope", "settingsData", "motion", "comment", "vote", "user", "UserbarService"];
	angular
		.module('iserveu')
		.controller('HomeController', HomeController);

    /** @ngInject */
    // this is a TODO    
	function HomeController($rootScope, $scope, settingsData, motion, comment, vote, user, UserbarService) {
		
        UserbarService.setTitle("Home");

		var vm = this;

        /************************************** Variables **********************************/
        vm.settings = settingsData;
        vm.shortNumber = 120;
		vm.topMotion;
		vm.myComments = [];
		vm.myVotes = [];
		vm.topComment;
        vm.empty = {
            mycomments: false,
            myvotes: false
        };

        vm.loading = {
            topmotion: true,
            topcomment: true,
            mycomments: true,
            myvotes: true
        }

        /************************************** Home Functions **********************************/



        // TODO: loading on each box

        function getTopMotion() {
        	motion.getTopMotion().then(function(result){
                vm.loading.topmotion = false;
        		vm.topMotion = result.data[0];
                if( !vm.topMotion ) vm.empty.topmotion = true;
        	},function(error) {
                vm.loading.topmotion = false;
                vm.empty.topmotion = true;
        	});
        }

        function getMyComments(){
        	comment.getMyComments(user.self.id).then(function(result){
                vm.loading.mycomments = false;
        		vm.myComments = result;
                if( !vm.myComments[0] ) vm.empty.mycomments = true;
        	},function(error) {
                vm.loading.mycomments = false;
                vm.empty.mycomments = true;
        	});
        }

        function getTopComment(){
        	comment.getComment().then(function(result){
                vm.loading.topcomment = false;

                if( !result[0] )  vm.empty.topcomment = true; 
                else vm.topComments = result.slice(0,5);
        	},function(error) {
                vm.loading.topcomment = false;

                vm.empty.topcomment = true;
        	});
        }

        function getMyVotes(){
            vote.getMyVotes(user.self.id, {limit:5}).then(function(result){
                vm.loading.myvotes = false;

                vm.myVotes = result.data;
                if( result.total == 0 ) vm.empty.myvotes = true;
            },function(error) {
                vm.loading.myvotes = false;
                vm.empty.myvotes = true;
            });
        }

        $rootScope.$on('usersVoteHasChanged', function(event, args) {
            getMyVotes();
        });

        getTopMotion();
        getTopComment();


        // this is the dumbest thing i've ever written. too tired to write well...

        $scope.$watch( function() { return user.self },
            function(details) {
                if( details ) {
                    getMyComments();
                    getMyVotes();
                } else {
                    user.self = $rootScope.authenticatedUser
                    getMyComments();
                    getMyVotes();
                }
            }, true
        );

	}
	
}());


(function() {

	'use strict';

	displayMotion.$inject = ["$rootScope", "$stateParams", "motion", "motionObj", "UserbarService", "voteObj", "commentObj", "isMotionOpen"];
	angular
		.module('iserveu')
		.directive('displayMotion', displayMotion);


	//TODO: refactor
	 /** @ngInject */
	function displayMotion($rootScope, $stateParams, motion, motionObj, UserbarService, voteObj, commentObj, isMotionOpen) {

	  function MotionController() {

	        $rootScope.motionIsLoading[$stateParams.id] = true; // used to turn loading circle on and off for motion sidebar

	  		/* Variables */
	  		var vm = this;
			vm.details = {};
			vm.isLoading = true;
			vm.voteObj = voteObj;

	        function getMotion(id) {

	            var catchMotion = motionObj.getMotionObj(id);

	            commentObj.comment = null;

	            if (catchMotion) 
	                postGetMotion(catchMotion)
	            else {
	                motion.getMotion(id).then(function(r) {
	                    postGetMotion(r);
	                });     
	            }
	        }

	        function postGetMotion(motion){
	        	// service setters
	        	UserbarService.title = motion.title;
	            isMotionOpen.set(motion.MotionOpenForVoting);
	            voteObj.user  = motion.user_vote ? motion.user_vote : {position: null} ;

	            // UI animation and dependencies
	            vm.details = motion;
	            vm.isLoading    = $rootScope.motionIsLoading[motion.id] = false;
	            commentObj.getMotionComments(motion.id);  
	            voteObj.calculateVotes(motion.id);   
	        }

	        getMotion($stateParams.id);
	        
	    }


	    return {
	    	controller: MotionController,
	    	controllerAs: 'motion',
	    	templateUrl: 'app/components/motion/partials/motion.tpl.html'
	    }


	}


})();
(function() {

	'use strict';

	createPage.$inject = ["$state", "pageObj", "ToastMessage", "dropHandler"];
	angular
		.module('iserveu')
		.directive('createPageContent', createPage);

  	/** @ngInject */
	function createPage($state, pageObj, ToastMessage, dropHandler) {

		function createPageController() {

			this.dropHandler = dropHandler;
			this.pageObj = pageObj;
			this.saveString = "Create";

			this.cancel = function() {
				ToastMessage.cancelChanges(function(){
					$state.go('dashboard');
				});
			};
		};


		return {
			controller: createPageController,
			controllerAs: 'create',
			template: ['<md-card><md-card-content><form name="page" ng-submit="page.$valid && create.pageObj.save(create.form)">',
					   '<md-input-container style="width: 100%">',
					   '<input placeholder="Page title" ng-model="create.form.title" required/></md-input-container>',
					   '<text-angular ng-model="create.form.content" ta-file-drop="create.dropHandler"></text-angular>',
					   '<div layout="row"><spinner name="create.saveString" on-hide="create.pageObj.processing"></spinner>',
					   '<md-button ng-click="create.cancel()">Cancel</md-button></div>',
					   '</md-card-content></md-card>'].join('')
		}



	}

})();
(function() {


	'use strict';

	editPageContent.$inject = ["$state", "$stateParams", "ToastMessage", "pageObj", "dropHandler"];
	angular
		.module('iserveu')
		.directive('editPageContent', editPageContent);

  	 /** @ngInject */
	function editPageContent($state, $stateParams, ToastMessage, pageObj, dropHandler) {


		function editPageController() {

			this.pageObj = pageObj;
			this.dropHandler = dropHandler;
			this.saveString = "Save";

			this.save = function() {
				pageObj.processing = true;
				pageObj.update($stateParams.id, {
					'title': this.pageObj.title,
					'content': this.pageObj.content
				});
			};

			this.cancel = function() {
	            ToastMessage.cancelChanges(function(){
	            	$state.go('pages', {id: $stateParams.id});
	            });
			};

			pageObj.initLoad($stateParams.id);
		}


		return {
			controller: editPageController,
			controllerAs: 'edit',
			template: ['<md-card><md-card-content><md-input-container style="width: 100%; margin-bottom: 0">',
					   '<input ng-model="edit.pageObj.title"/></md-input-container>',
					   '<text-angular ng-model="edit.pageObj.content" ta-file-drop="edit.dropHandler"></text-angular><div layout="row">',
					   '<spinner name="edit.saveString" ng-click="edit.save()" on-hide="edit.pageObj.processing"></spinner>',
					   '<md-button ng-click="edit.cancel()">Cancel</md-button></div>',
					   '</md-card-content></md-card>'].join('')
		}

	}


})();
(function(){

	'use strict';


	pageObj.$inject = ["$http", "$state"];
	angular
		.module('iserveu')
		.factory('pageObj', pageObj);

  	 /** @ngInject */
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
(function() {


	'use strict';

	pageContent.$inject = ["$stateParams", "pageObj", "UserbarService"];
	angular
		.module('iserveu')
		.directive('pageContent', pageContent);

  	 /** @ngInject */
	function pageContent($stateParams, pageObj, UserbarService) {


		function pageController() {

			this.pageObj = pageObj;

			this.loading = "loading";

			pageObj.initLoad($stateParams.id);

			UserbarService.title = pageObj.title;

		}


		return {
			controller: pageController,
			controllerAs: 'p',
			template: ['<pages-fab></pages-fab><md-card ng-class="p.pageObj.pageLoading ? p.loading : none "><md-card-content>',
					   '<p ng-bind-html="p.pageObj.content"></p></md-card-content></md-card>'].join('')
		}

	}


})();
(function() {

	'use strict';

	afterauth.$inject = ["$stateParams", "$state", "$mdToast", "$rootScope", "auth", "user", "SetPermissionsService"];
	angular
		.module('iserveu')
		.factory('afterauth', afterauth);

  	 /** @ngInject */
	function afterauth($stateParams, $state, $mdToast, $rootScope, auth, user, SetPermissionsService) {

		 function setLoginAuthDetails (user, token){
			if(token)
				localStorage.setItem( 'satellizer_token', JSON.stringify( token ) );

			SetPermissionsService.set( JSON.stringify( user.permissions ) );
			localStorage.setItem( 'user', JSON.stringify(user) );
			$rootScope.authenticatedUser = user;
			redirect();
		}

		function redirect(){

			$rootScope.userIsLoggedIn = true;

			return $rootScope.redirectUrlName 
				   ? $state.go($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID}) 
				   : $state.go('home');
		}

		function clearCredentials(){
			localStorage.clear();
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
			$state.go('login', {});		
		}

		return {
			setLoginAuthDetails: setLoginAuthDetails,
			redirect: redirect,
			clearCredentials: clearCredentials
		}


	}
})();

(function() {

	'use strict';

	auth.$inject = ["$resource", "$http", "$sanitize", "CSRF_TOKEN", "$auth", "$q"];
	angular
		.module('iserveu')
		.factory('auth', auth);

  	 /** @ngInject */
	function auth($resource, $http, $sanitize, CSRF_TOKEN, $auth, $q) {

		var login = function(credentials) {
			return $auth.login(sanitizeCredentials(credentials)).then(function(user) {
				return user;
			}, function(error) {
				return $q.reject(error);
			});
		};

		var logout = function() {
			return $auth.logout();
		};

		var sanitizeCredentials = function(credentials) {
			return {
		    	email: $sanitize(credentials.email),
		    	password: $sanitize(credentials.password),
		    	csrf_token: CSRF_TOKEN
		  	};
		};

		var getAuthenticatedUser = function() {
			return $http.get('api/user/authenticateduser').success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		};
		
		var postAuthenticate = function(credentials) {
			return $http.post('authenticate', credentials).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			})
		};

		var getNoPassword = function(remember_token) {
			return $http.get('authenticate/' + remember_token).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			})
		}

		var postUserCreate = function(credentials) {
			return $http.post('api/user', credentials).success(function(result) {					
				return result;
			})
			  .error(function(error) {
			  	return error;
			});
		};

		var getSettings = function() {
			return $http.get('settings').success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		};

		var getResetPassword = function(credentials) {
			return $http.post('authenticate/resetpassword', credentials).success(function(result) {
				return result;
			})
			.error(function(error){
				return error;
			})
		}

		return {
			login: login,
		  	logout: logout,
		  	getAuthenticatedUser: getAuthenticatedUser,
		  	postAuthenticate: postAuthenticate,
		  	getNoPassword: getNoPassword,
		  	postUserCreate: postUserCreate,
		  	getSettings: getSettings,
		  	getResetPassword: getResetPassword
		};
	}

	
})();
(function() {

	'use strict';

	CommonController.$inject = ["settings"];
	angular
		.module('iserveu')
		.controller('CommonController', CommonController);

  	 /** @ngInject */
	function CommonController(settings) {

		this.settings = settings.getData();

		this.getLogoUrl = function() {

			return this.settings.logo == 'default' 
				   ? '/themes/default/logo/symbol_mono.svg'
				   : '/uploads/'+this.settings.theme.logo;

		}

	}



})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$httpProvider',

	function($httpProvider){

		$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"; // for AJAX
		$httpProvider.defaults.headers.common['X-CSRFToken'] = localStorage.getItem('satellizer_token');

		/** 
		/* This is being unused ... it's a good concept that will come in handy later as an error handler
		/* as well as a trigger for 200 triggers!
		*/
		
		// $httpProvider.interceptors.push(function($timeout, $q, $injector, $rootScope) {
		// 	var $state, $http;

		// 	$timeout(function() {
		// 		$http = $injector.get('$http');
		// 		$state = $injector.get('$state');
		// 	});
		

		// 	return {
		// 		responseError: function(rejection) {
		// 			if(rejection.status === 401)
		// 				// $state.go('permissionfail');

		// 			if(rejection.status === 403)
		// 				// access forbidden
		
		// 			return $q.reject(rejection);
		// 		}
		// 	}
		// });





	}]);


})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$mdThemingProvider',
	function($mdThemingProvider){

		var theme = localStorage.getItem('settings');

		if ( !theme || theme.length <= 2 || theme == 'undefined'){

		    var initInjector = angular.injector(['ng']);
		    var $http = initInjector.get('$http');

			$http.get('settings').then(function(r){
				localStorage.setItem('settings', JSON.stringify(r.data));
				theme = r.data.theme;
				setTheme();
			});
		} else if (theme != undefined) {
			setTheme();
		} 

		function setTheme() {

			theme = angular.isString(theme) ? JSON.parse(theme).theme : theme;

			$mdThemingProvider.definePalette('primary', {
		        '50': theme.primary['50'],
		        '100': theme.primary['100'],
		        '200': theme.primary['200'],
		        '300': theme.primary['300'],
		        '400': theme.primary['400'],
		        '500': theme.primary['500'],
		        '600': theme.primary['600'],
		        '700': theme.primary['700'],
		        '800': theme.primary['800'],
		        '900': theme.primary['900'],
		        'A100': theme.primary['A100'],
		        'A200': theme.primary['A200'],
		        'A400': theme.primary['A400'],
		        'A700': theme.primary['A700'],
		        'contrastDefaultColor': theme.primary['contrastDefaultColor'],    // whether, by default, text (contrast)
		        'contrastDarkColors': theme.primary['A700'],
		        'contrastLightColors': 'dark' // could also specify this if default was 'dark'
	        });

			$mdThemingProvider.definePalette('accent', {
		        '50': theme.accent['50'],
		        '100': theme.accent['100'],
		        '200': theme.accent['200'],
		        '300': theme.accent['300'],
		        '400': theme.accent['400'],
		        '500': theme.accent['500'],
		        '600': theme.accent['600'],
		        '700': theme.accent['700'],
		        '800': theme.accent['800'],
		        '900': theme.accent['900'],
		        'A100': theme.accent['A100'],
		        'A200': theme.accent['A200'],
		        'A400': theme.accent['A400'],
		        'A700': theme.accent['A700'],
		        'contrastDefaultColor': theme.accent['contrastDefaultColor'],    // whether, by default, text (contrast)
		        'contrastDarkColors': theme.accent['A700'],
		        'contrastLightColors': 'dark' // could also specify this if default was 'dark'
	        });

	  	    $mdThemingProvider.theme('default')
	            .primaryPalette('primary', {
	            	'default': '400',
	            	'hue-1': '50',
	            	'hue-2': '400',
	            	'hue-3': '700'
	            })
	            .accentPalette('accent', {
	            	'default': '400',
	            	'hue-1': '50',
	            	'hue-2': '400',
	            	'hue-3': '700'
	            });

	    };


	}]);

})();
(function() {

	'use strict';


	angular
		.module('infinite-scroll')
		.value('THROTTLE_MILLISECONDS', 250);


})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$translateProvider',

  	 /** @ngInject */
	["$translateProvider", function($translateProvider){

		var jargon = localStorage.getItem('settings');

		if ( !jargon || jargon.length <= 2 || jargon.en){

		    var initInjector = angular.injector(['ng']);
		    var $http = initInjector.get('$http');

			$http.get('settings').then(function(r){
				localStorage.setItem('settings', JSON.stringify(r.data));
				jargon = r.data.jargon;
			});

		} else 
			jargon = JSON.parse(jargon).jargon;


		$translateProvider.preferredLanguage('en');

		// $translateProvider.determinePreferredLanguage(function(){
			// var preferredLangKey = '';
			// custom logic, probably grab from local storage/cookie storage
		// 	return preferredLangKey;
		// });

		$translateProvider.translations('en', {
			LANG_NAME: "Languages",
			MOTION: jargon.en.motion ? jargon.en.motion : "Motion",
			MOTIONS: jargon.en.motions ? jargon.en.motions : "Motions", //depecrated
			BETA_HEADER: "IserveU is currently in BETA. ",
			BETA_MESSAGE: "Features and improvements are constantly being added. If you would like give feedback and help us test the software, please email ",
			BETA_MESSAGE_MINI: "If you encounter any issues, please email ",
			PHOTO_COURTESY: "Photo courtesy of ",
			LOGOUT: "Logout",
			YOUR_PROFILE: "Your Profile",
			USER_LIST: "User List",
			UPLOAD_BACKGROUND_IMG: "Upload Background Image",
			DEPARTMENT_MANAGER: "Department Manager",
			PROPERTY_MANAGER: "Property Manager",
			//UserBar Titles
			Home: "Home",
			Background_Images: "Background Images", 
			//home state
			WELCOME: "Welcome!",
			YOUR_VOTES: "Your Votes",
			YOUR_COMMENTS: "Your Comments",
			CURRENTLY_PASSING: "Currently Passing",
			TODAYS_TOP_COMMENTS: "Today's Top Comments",
			BY: "by ",
			BY_A: "by a Yellowknifer",
			//background state
			WHO_TOOK_THIS: "Who took this photo?",
			EXAMPLE_WEBSITE: "myphotographywebsite.ca",
			DAILY_CYCLE_TOOLTIP: "This will place your photo into the daily cycles immediately.",
			UPLOAD_PHOTO_ERROR: "There was an error uploading your photo. Make sure you've entered in all the fields correctly.",
			PREVIEW: "Preview",
			//buttons
			SUBMIT: "Submit",
			OK: "Okay",
			CANCEL: "Cancel",
			ACTIVE: "Active",
			ADD: "Add",
			EDIT: "Edit",
			DELETE: "Delete",
			SAVE: "Save",
			SAVE_CHANGES: "Save Changes",
			CLOSE: "Close",
			BACK: "Back",
			REMOVE: "Remove",
			//field names
			FIRST_NAME: "First Name",
			MIDDLE_NAME: "Middle Name",
			LAST_NAME: "Last Name",
			BIRTHDAY: "Birthday",
			EMAIL_ADDRESS: "Email Address",
			PUBLIC: "Public",
			PRIVATE: "Private",
			IDENTITY_VERIFIED: "Identity Verified",
			USER_VERIFIED: "User identity is verified.",
			USER_NOT_VERIFIED: "User identity is not verified",
			PASSWORD: "Password",
			CHANGE_PASSWORD: "Change Password",
			ETHNIC_ORIGIN: "Ethnicity",
			ADDRESS: "Address",
			SELECT_ROLE: "Select Role",
			BIRTHDAY_NOT_SET: "Birthday not set",
			//user state
			EDIT_YOUR_PUBLIC_PROFILE: "Edit your public profile",
			VOTING_HISTORY: "Voting History",
			//role state
			NAME: "Name",
			IDENTITY: "Identity",
			ROLES: "Roles",
			VERIFIED: "Verified",
			UNVERIFIED: "Unverified",
			USER_ROLES: "User roles",
			//motion state
			INTRODUCTION: "Introduction",
			DETAILS: "Details",
			CLOSED: "Closed",
			OPEN: "Open",
			CLOSES_ON: "closes on ",
			DESCRIPTION: "Description",
			VOTING: "Voting",
			POST: "Post",
			SUBMIT: "Submit",
			POST_COMMENT: "Post Comment",
			YOUR_COMMENT: "Your Comment",
			SUBMIT_COMMENT: "Submit Comment",
			SAVE_COMMENT: "Save Comment",
			COMMENT: "Comment",
			AGREE: "Agree",
			AGREED: "Agreed",
			DISAGREE: "Disagree",
			DISAGREED: "Disagreed",
			ABSTAIN: "Abstain",
			ABSTAINED: "Abstained",
			AGREE_DEFERRALS: "Agree deferrals",
			DISAGREE_DEFERRALS: "Disagree deferrals",
			ABSTAIN_DEFERRALS: "Abstain deferrals",
			AGREE_WITH_COMMENT: "Agree With Comment",
			DISAGREE_WITH_COMMENT: "Disagree With Comment",
			DISAGREE_ABSTAIN: "Disagree / Abstain",
			WRITTEN: "written ",
			EDITED: "edited ",
			DEPARTMENT:  jargon.en.department ? jargon.en.department : "Department",
			DEPARTMENTS:  jargon.en.departments ? jargon.en.departments : "Departments",
			DISPLAY: "Display",
			ACTIVATE_MOTION: "Activate motion.",
			ATTACHMENTS: "Attachments",
			DRAG_AND_DROP: "or drag and drop your files here",
			//motion sidebar
			NEUTRAL: "Neutral",
			QUICK_VOTE: "Quick Vote",
			NO_MOTIONS: "No Motions",
			CREATE_NEW_MOTION: jargon.en.motion ? "Create new " + jargon.en.motion : "Create new motion",
			//department state
			EXISTING_DEPARTMENT: "Existing departments",
			//Password Reset
			PLEASE_RESET: "Please reset your password.",
			NEW_PASSWORD: "New Password",
			CONFIRM_PASSWORD: "Confirm Password",
			LEAST_CHAR: "Must be at least 8 characters.",
			PASS_NOT_MATCH: "Your password does not match.",
			//Address keys
			VERIFY_ADDRESS: "Verify your address",
			ADDRESS_CAPTION: "We will need to verify your identity for your Yellowknife vote to count.",
			APT_SUITE: "Apt./Suite",
			STREET_NUM: "Street Number",
			POSTAL_CODE: "Postal Code",
			ROLL_NUMBER: "Roll Number",
			SEARCH_RESULTS: "Search Results",
			SELECT_ADDRESS: "Select your address.",
			FIELD_SEARCH: "Please fill in the fields on the left to begin searching.",
			UNIT_NUMBER: "Unit Number",
			STREET_ADDRESS: "Street Address",
			STREET: "Street Name"


		});

		$translateProvider.translations('fr', {
			LANG_NAME: "Langue",
			MOTION: jargon.fr.motion ? jargon.fr.motion : "Motion",
			MOTIONS: jargon.fr.motions ? jargon.fr.motions : "Motions",
			BETA_HEADER: "IserveU est présentement en BETA. ",
			BETA_MESSAGE: "Les caractéristiques et les améliorations sont constamment ajoutées. Si vous désirez nous aider en testant notre programme, envoyez-nous un courriel à ",
			BETA_MESSAGE_MINI: "Pour tous problèmes, communiquez avec nous par courriel ",
			PHOTO_COURTESY: "Les photos sont une courtoisie de ",
			LOGOUT: "Se déconnecter",
			YOUR_PROFILE: "Votre profile",
			USER_LIST: "Liste d’utilisateurs",
			UPLOAD_BACKGROUND_IMG: "Charger l’image d’arrière-plan",
			DEPARTMENT_MANAGER: "Gérant de département",
			PROPERTY_MANAGER: "Gérant de propriété",
			//UserBar Titles
			Home: "Accueil",
			Background_Images: "Images d’arrière-plan", 
			//home state
			WELCOME: "Bienvenue!",
			YOUR_VOTES: "Votre vote",
			YOUR_COMMENTS: "Vos commentaires",
			CURRENTLY_PASSING: "Passant actuellement",
			TODAYS_TOP_COMMENTS: "Top commentaire du jour",
			BY_A: "par un Yellowknifer",
			//background state
			WHO_TOOK_THIS: "Qui a pris cette photo?",
			EXAMPLE_WEBSITE: "myphotographywebsite.ca",
			DAILY_CYCLE_TOOLTIP: "Cette option permettra d’ajouter votre photo au quotidien immédiatement.",
			UPLOAD_PHOTO_ERROR: "Une erreur de chargement est survenue  assurez-vous d’avoir remplis correctement tous les champs d’option.",
			PREVIEW: "Aperçus",
			//buttons
			SUBMIT: "Soumis",
			OK: "Okay",
			CANCEL: "Cancellé",
			ACTIVE: "Activé",
			ADD: "Ajouter",
			EDIT: "Édité",
			DELETE: "Effacé",
			SAVE: "Sauvegardé",
			SAVE_CHANGES: "Changement sauvegardé",
			CLOSE: "Fermé",
			BACK: "Retour",
			REMOVE: "Supprimer",
			//field names
			FIRST_NAME: "Prénom",
			MIDDLE_NAME: "Second nom",
			LAST_NAME: "Nom de famille",
			BIRTHDAY: "Date de naissance",
			EMAIL_ADDRESS: "Adresse courriel",
			PUBLIC: "Publique",
			PRIVATE: "Privé",
			IDENTITY_VERIFIED: "Identité vérifié",
			USER_VERIFIED: "Identité de l'utilisateur vérifié.",
			USER_NOT_VERIFIED: "Identité de l'utilisateur non vérifées",
			PASSWORD: "Mot de passe",
			CHANGE_PASSWORD: "Changer le mot de passe",
			ETHNIC_ORIGIN: "Ethnicité",
			ADDRESS: "Adresse",
			SELECT_ROLE: "Sélectionner un rôle",
			BIRTHDAY_NOT_SET: "Date de naissance non-enregistrée",
			//user state
			EDIT_YOUR_PUBLIC_PROFILE: "Modifier votre profile publique",
			VOTING_HISTORY: "L'histoire de Vote",
			// TODO: role state
			NAME: "Nom",
			IDENTITY: "Identité",
			ROLES: "Rôles",
			VERIFIED: "Vérifié",
			UNVERIFIED: "Non-vérifier",
			USER_ROLES: "Rôle de l’utilisateur",
			//motion state
			INTRODUCTION: "Introduction",
			DETAILS: "Détails",
			CLOSED: "Fermé",
			OPEN: "Ouvert",
			CLOSES_ON: "fermer ",
			DESCRIPTION: "Description",
			VOTING: "Voter",
			POST_COMMENT: "Afficher un commentaire",
			YOUR_COMMENT: "Votre commentaire",
			SUBMIT_COMMENT: "Envoyer votre commentaire",
			SAVE_COMMENT: "Sauvegarder le commentaire",
			COMMENT: "Commentaire",
			AGREE: "Accepter",
			AGREED: "",
			DISAGREE: "En désaccord",
			DISAGREED: "",
			ABSTAIN: "",
			ABSTAINED: "",
			AGREE_DEFERRALS: "",
			DISAGREE_DEFERRALS: "",
			ABSTAIN_DEFERRALS: "",
			AGREE_WITH_COMMENT: "En accord avec le commentaire",
			DISAGREE_WITH_COMMENT: "En désaccord avec le commentaire",
			DISAGREE_ABSTAIN: "En désaccord/ S’abstenir",
			WRITTEN: "écris ",
			EDITED: "édité ",
			DEPARTMENT:  jargon.fr.department ? jargon.fr.department : "Département",
			DEPARTMENTS:  jargon.fr.departments ? jargon.fr.departments : "Départements",
			DISPLAY: "Affichage",
			ACTIVATE_MOTION: "Motion active.",
			ATTACHMENTS: "Attachments",
			DRAG_AND_DROP: "Attachez ou faites glisser votre document ici",
			//motion sidebar
			NEUTRAL: "Neutre",
			QUICK_VOTE: "Vote rapide",
			NO_MOTIONS: "Aucune motion",
			CREATE_NEW_MOTION: jargon.fr.motion ? "Créer une nouvelle" + jargon.fr.motion : "Créer une nouvelle motion",
			//department state
			EXISTING_DEPARTMENT: "Département existant",
			//Password Reset
			PLEASE_RESET: "SVP réinitialisez votre mot de passe.",
			NEW_PASSWORD: "Nouveau mot de passe",
			CONFIRM_PASSWORD: "Confirmer votre mot de passe",
			LEAST_CHAR: "Doit contenir au moins 8 caractères.",
			PASS_NOT_MATCH: "Votre  de passe ne correspond pas.",
			//Address keys
			VERIFY_ADDRESS: "Vérifier votre adresse",
			ADDRESS_CAPTION: "Nous auront besoin de vérifier votre identité pour que vote de Yellowknife compte.",
			APT_SUITE: "App./Suite",
			STREET_NUM: "Numéro civique",
			POSTAL_CODE: "Code postal",
			ROLL_NUMBER: "Numéro de lien",
			SEARCH_RESULTS: "Résultats de la recherche",
			SELECT_ADDRESS: "Sélectionnez votre adresse.",
			FIELD_SEARCH: "SVP remplir les champs sur la gauche pour commencer la recherche.",
			UNIT_NUMBER: "Numéro d’unité",
			STREET_ADDRESS: "Numéro de rue",
			STREET: "Nom de rue"
		}).fallbackLanguage('en');

		//uses local storage to remember user's preferred language
		$translateProvider.useLocalStorage();

	}]]);


})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$stateProvider',

     /** @ngInject */
	["$stateProvider", function($stateProvider){

    // TODO: add state permissions to each state.

    $stateProvider
    	.state( 'home', {
    		url: '/home',
    		templateUrl: 'app/components/home/home.tpl.html',
    		controller: 'HomeController as home',
    		data: {
    	        requireLogin: true
    	    },
            resolve: {
                settingsData: ["settings", function(settings) {
                    return settings.getData();
                }]
            }
    	})
        .state('edit-home', {
            url: '/edit-home',
            template: '<edit-home>',
            data: {
                requireLogin: true
            }
        })
        .state( 'dashboard', {
            url: '/dashboard',
            templateUrl: 'app/components/admin/dashboard.tpl.html',
            data: {
                requireLogin: true
            },
            resolve: {
                settingsData: ["settings", function(settings) {
                    return settings.getData();
                }]
            }
        })
    	.state( 'motion', {
    	    url: '/motion/:id',
    	    template: '<display-motion></display-motion>',
    	    data: {
    	        requireLogin: true,
                moduleMotion: true
    	    }
    	})
        .state('edit-motion', {
            url: '/edit-motion/:id',
            template: '<edit-motion></edit-motion>',
            data: {
                requireLogin: true,
                moduleMotion: true
            }
        })
        .state( 'create-motion', {
            url: '/create-motion',
            template: '<create-motion></create-motion>',
            data: {
                requireLogin: true,
                moduleMotion: true
            }
        })
        .state( 'pages', {
            url: '/page/:id',
            template: '<page-content></page-content>',
            data: {
                requireLogin: true
            }  
        })
        .state( 'edit-page', {
            url: '^/page/:id/edit',
            template: '<edit-page-content></edit-page-content>',
            data: {
                requireLogin: true
            }  
        })
       .state( 'create-page', {
            url: '/create-page',
            template: '<create-page-content></create-page-content>',
            data: {
                requireLogin: true
            }  
        })
        .state( 'user', {
            url: '/user/:id',
            template: '<display-profile></display-profile>',
            data: {
                requireLogin: true
            },
            resolve: {
                profile: ["user", "$stateParams", function(user, $stateParams) {
                    var profile;
                    return user.getUser($stateParams.id)
                        .then(function(r) {
                            return profile = r; });
                }],
                communityIndex: ["$http", function($http) {
                    var community;
                    return $http.get('/api/community')
                        .success(function(r){
                            return community = r;
                    });
                }]
            },
            controller: ["$scope", "profile", "communityIndex", function($scope, profile, communityIndex) {
                $scope.profile = profile;
                $scope.communities = communityIndex.data;
            }]
        })
        // this is a good place for resolves
        .state( 'user.profile', {
            url: '/profile',
            data: {
                requireLogin: true
            }
        })
        .state('edit-user', {
            url: '/edit-user/:id',
            template: '<edit-user></edit-user>',
            data: {
                requireLogin: true
            },  
            resolve: {
                profile: ["user", "$stateParams", function(user, $stateParams) {
                    var profile;
                    return user.getUser($stateParams.id)
                        .then(function(r) {
                            return profile = r; });
                }],
                communityIndex: ["$http", function($http) {
                    var community;
                    return $http.get('/api/community')
                        .success(function(r){
                            return community = r;
                    });
                }]
            },
            controller: ["$scope", "profile", "communityIndex", function($scope, profile, communityIndex) {
                $scope.profile = profile;
                $scope.communities = communityIndex.data;
            }]
        }) 
        .state( 'create-user', {
            url: '^/user/create',
            templateUrl: 'app/components/user/components/create-user/create-user.tpl.html',
            controller: 'CreateUserController as create',
            data: {
                requireLogin: true
            }
        })
        .state('login', {
            url: '/login',
        	controller: 'loginController as login',
        	templateUrl: 'app/shared/auth/login/login.tpl.html',
            data: {
                requireLogin: false
            } 
    	})
    	.state('login.resetpassword', {
    		url: '/:resetpassword',
    		data: {
    			requireLogin: false
    		}
    	})
    	.state('permissionfail' , {
    		url: '/invalidentry',
        	controller: 'RedirectController as redirect',
        	templateUrl: 'app/shared/permissions/onfailure/permissionsfail.tpl.html',
            data: {
                requireLogin: false
            } 
    	});    
        	
	}]]);

})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$provide',

  	 /** @ngInject */
	["$provide", function($provide){

		$provide.decorator('taOptions', ['taRegisterTool', '$delegate', function(taRegisterTool, taOptions){


	        // $delegate is the taOptions we are decorating
	        // register the tool with textAngular
	        taRegisterTool('colourRed', {
	            iconclass: "mdi mdi-link",
	            action: function(deferred, restoreSelection){
	                this.$editor().wrapSelection('forecolor', 'red');
	            }
	        });

	        // add the button to the default toolbar definition
	        taOptions.toolbar[1].push('colourRed');
	        return taOptions;
	    }]);

	}]]);


})();
(function() {
	
	'use strict';

	convertClosingDate.$inject = ["$filter"];
	angular
		.module('iserveu')
		.directive('convertClosingDate', convertClosingDate);


  	 /** @ngInject */
	function convertClosingDate($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
      				data.setDate(data.getDate() + 7);
      				return data;
			    });
			}
		}
	}

}());


(function() {

	'use strict';

	focusInput.$inject = ["$timeout"];
	angular
		.module('iserveu')
		.directive('focusInput', focusInput);

  	 /** @ngInject */
	function focusInput($timeout) {

	    return {
	        restrict : 'A',
	        link : function($scope,$element,$attr) {
	            $scope.$watch ($attr.focusInput,function(_focusVal) {
	                $timeout(function() {
	                    _focusVal ? $element.focus() :
	                        $element.blur();
	                });
	            });
	        }
	    }


	}

})();
(function() {
	
	'use strict';

	formatDate.$inject = ["$filter"];
	angular
		.module('iserveu')
		.directive('formatDate', formatDate);

  	 /** @ngInject */
	function formatDate($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {


				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
      // 				if(data === "0000-00-00" || data === null ) {
      // 					// TODO: make this more flexible to reuse this directive not just for birthdays
      // 					return "Enter your birthday";
      // 				}
      // 				else {
      // 					var transformedDate = new $filter('date')(data, 'MMMM d, yyyy');
						// return transformedDate;
      // 				}
					// return $filter('date')(data);

					return new Date(data);
      				

			    });
			}
		}
	}

}());


(function() {
	
	'use strict';

	formatAddress.$inject = ["$filter", "community", "utils"];
	angular
		.module('iserveu')
		.directive('formatAddress', formatAddress);

  	 /** @ngInject */
	function formatAddress($filter, community, utils) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

      			ngModelController.$formatters.push(function(data) {

      				var address = '';

      				if( !data.street_name )
      					return "Enter your address";
      				else if ( !data.unit_number && !data.street_number)
      					address = utils.toTitleCase(data.street_name);
      				else if( !data.unit_number )
      					address = data.street_number + ' ' + utils.toTitleCase(data.street_name);
      				else if ( !data.street_number )
      					address = "Unit #" + data.unit_number + ' ' + utils.toTitleCase(data.street_name);
      				else
						address = data.unit_number + '-' + data.street_number + ' ' + utils.toTitleCase(data.street_name);

					if(data.community_id)
						for(var i in scope.communities){
							if (data.community_id === scope.communities[i].id)
								return address + ', ' + scope.communities[i].name;
						}
					else return address;

			    });
			}
		}
	}

}());


(function() {

	'use strict';

	ToastMessage.$inject = ["$state", "$mdToast", "$timeout", "utils"];
	angular
		.module('iserveu')
		.factory('ToastMessage', ToastMessage);

     /** @ngInject */
	function ToastMessage($state, $mdToast, $timeout, utils) {
	
        function simple(message, time){
            var timeDelay = ( time ) ? time : 1000;
            return $mdToast.show(
                $mdToast.simple()
                .content(message)
                .position('bottom right')
                .hideDelay(timeDelay)
            );
        }

        function action(message, affirmative, warning){
           var toast = $mdToast.simple()
                .content(message)
                .action(affirmative)
                .highlightAction(warning)
                .position('bottom right')
                .hideDelay(5000);
                
            return toast;
        }

        function reload(time){
            time = time ? time : 1000;

            simple("The page will now refresh.", time);
            
            $timeout(function() {
                location.reload();
            }, time * 1.8 );
        }


        function customFunction(message, affirmative, fn){
            var toast = action(message, affirmative);
            $mdToast.show(toast).then(function(r){
                if(r == 'ok')
                    fn();
            });
        }

        function destroyThis(type, fn){
            var toast = action("Destroy this " + type + "?", "Yes", true);
            $mdToast.show(toast).then(function(r){
                if(r == 'ok'){
                    fn();
                    simple( utils.capitalize(type) + " destroyed", 1000 );
                }
            });
        }

        function cancelChanges(fn){
            var toast = action("Discard changes?", "Yes");
            $mdToast.show(toast).then(function(r){
                if(r == 'ok') fn();
            });
        }

        // TODO: implment Error Handler Service
        function report_error(error){
            var toast = action("Sorry, something went wrong.", "See", true); 
            var error = action(error.message, "Report");
            var thanks = simple("Thanks for your help! We'll work on it.", 800);

            $mdToast.show(toast).then(function(r) {
                if (r == 'ok')
                    $mdToast.show(error).then(function(r){
                        if (r == 'ok') $mdToast.show(thanks);
                    });
            });
        }

        // exports
        return {
            simple: simple,
            action: action,
            reload: reload,
            customFunction: customFunction,
            destroyThis: destroyThis,
            cancelChanges: cancelChanges,
            report_error: report_error
        }


	}
})();
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('pressEnter', pressEnter);

	function pressEnter() {

	return function (scope, element, attrs) {
		 element.bind('keydown keypress', function (event) {
		   if(event.which === 13) {
  			  scope.$apply(function (){
	      		  scope.$eval(attrs.pressEnter);
        	  });
       		 	event.preventDefault();
     		}
		 });
		}
	}

}());


(function() {

	'use strict';

	backImg.$inject = ["$http"];
	angular
		.module('iserveu')
		.directive('backImg', backImg);

  	 /** @ngInject */
	function backImg($http) {

		function linkMethod(scope, element, attrs){
				
			$http.get('settings').success(function(r){
				set( r.background_image 
					 ? r.background_image 
		    		 : "/themes/default/photos/background.png");
			}).error(function(e){
				console.log(e);
			});

			function set(background_image){
				element.css({
				    'background-image': 'url('+background_image+')'
				});
			}
		}


		return {
			link: linkMethod
		}

	}



}());
(function() {

	angular
		.module('iserveu')

		.filter('capitalize', function() {
		    return function(input) {
		      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
		    }
		})
		.filter('dateToDate', function() {
		  	return function(input) {
		    	input = new Date(input);
		    	return input;
	  		};
		})
		.filter('proComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position == "1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		})
		.filter('conComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position == "0" || input[i].position == "-1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		})
		.filter('object2Array', function() {
		    return function(obj) {
		    	return Object.keys(obj).map(function(key){return obj[key];});
		    }
	 	})
	 	.filter('bytes', function() {
			return function(bytes, precision) {
				if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
				if (typeof precision === 'undefined') precision = 1;
				var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
					number = Math.floor(Math.log(bytes) / Math.log(1024));
				return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) +  ' ' + units[number];
			}
		})

})();
(function() {
	
	'use strict';

	hasPermission.$inject = ["SetPermissionsService", "$state"];
	angular
		.module('iserveu')
		.directive('hasPermission', hasPermission);

  	 /** @ngInject */
	function hasPermission(SetPermissionsService, $state) {

		function linkMethod(scope, element, attrs) {
			var redirect = false;
			if(attrs.hasPermission.substring(0,16) == "redirectIfCannot"){
				attrs.hasPermission = attrs.hasPermission.substring(16);
				redirect = true;
			}
			if(attrs.hasPermission.substring(0,6) == 'hasAll'){	//when the permission attribute begins with 'hasAll'
				if(!SetPermissionsService.canAll(attrs.hasPermission.substring(6))){ //passes in the next part after, hasAll[ 'type' ]
					element.remove(attrs.hasPermission);
				}
			}
			else if(!SetPermissionsService.can(attrs.hasPermission)){ 
				element.remove(attrs.hasPermission);
				if(redirect){
					$state.go('permissionfail');
				}
			}
		}

		return {
			restrict: 'AE',
			link: linkMethod
		}

	}

}());
(function() {

	'use strict';

	SetPermissionsService.$inject = ["$rootScope", "$state", "auth"];
	angular
		.module('iserveu')
		.service('SetPermissionsService', SetPermissionsService);


	// This is a TODO!
  	 /** @ngInject */
	function SetPermissionsService($rootScope, $state, auth) {

		var vm = this;

		vm.set = set;
		vm.permissions;

		function set(permissions_array){
			if(permissions_array == undefined){auth.logout();}
			localStorage.setItem('permissions', permissions_array);
			vm.permissions = JSON.parse(permissions_array);
		}


		vm.can = function(action){
			var result = false;
			angular.forEach(vm.permissions, function(value, key){
				if(value == action){
					result = true;
				}
			});
			return result;
		}

		vm.canAll = function(section_name){
			var iterator = 0;
			var result = false;

			angular.forEach(vm.permissions, function(value, key){
				var splitpermissions = value.split('-');
				if(splitpermissions[1] == section_name){
					iterator++;
				}
			});

			if(iterator > 1){
				result = true;
			}
			
			return result;
		}

		if(!vm.permissions){set(localStorage.getItem('permissions'));}
		

	}
})();
(function() {
	
	incompleteProfile.$inject = ["$state", "user"];
	angular
		.module('iserveu')
		.directive('incompleteProfile', incompleteProfile);

	/** @ngInject */
	function incompleteProfile($state, user) {

		incompleteProfileController.$inject = ["$scope"];
		function incompleteProfileController($scope) {
			
			// not checking this on every state change :(

			$scope.state = $state;

			for( var i in user.self )
				if ( i === 'date_of_birth' ||
					 i === 'street_name'   ||
					 i === 'postal_code'   ||
					 i === 'community_id' )

			$scope.show = user.self[i] === null ? true : false;
		}

		function incompleteProfileLink(scope, el, attrs) {

			if( !scope.show )
				el.remove(attrs.incompleteProfile);
		
		}


		return {
			restrict: 'EA',
			controller: incompleteProfileController,
			link: incompleteProfileLink,
			templateUrl: 'app/shared/notifications/incomplete-profile.tpl.html'
		}


	}

})();
(function() {


	'use strict';

	restService.$inject = ["$stateParams"];
	angular
		.module('iserveu')
		.service('REST', restService);

	function restService($stateParams) {

		this.post = {
			makeData: function (type, data) {
				var fd = { id: $stateParams.id };
				// Object.keys(data).length just isn't working here so it's not very reusable...
				if ( type === 'address' || type === 'last_name' ) 
					fd = this.makeMutlipleData(fd, data);
				else
					fd[type] = data;
				return fd;
			},
		 	makeMutlipleData: function (fd, data) {
				for( var i in data )
					if( data[i] )
						fd[i] = data[i];
				return fd;
			}
		}






	}

})();
(function() {

	'use strict';

	dateService.$inject = ["$filter"];
	angular
		.module('iserveu')
		.service('dateService', dateService);


  	 /** @ngInject */
	function dateService($filter) {

		this.stringify = stringify;
		this.updateForPost = updateForPost;

		function stringify (date) {
			return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
		};

		function updateForPost (date) {
			var tempDate = date;
			date = null;
			return stringify(date);
		}

	}


})();
(function() {

	'use strict';


	angular
		.module('iserveu')
		.factory('dropHandler', 

		["fileService", function (fileService) {

			return function(file, insertAction){

					var reader = new FileReader();

					// TODO: for now upload the file and if you can even just
					// return the upload url!
					// if(file.type === 'application/pdf'){
					// 	reader.onload = function() {
					// 		if(reader.result !== '') insertAction('insertLink', '/uploads/'+reader.result, true);
					// 	};
					// 	reader.readAsDataURL(file);

					// 	return true;
					// }

					if(file.type.substring(0, 5) === 'image'){
						reader.onload = function() {
							if(reader.result !== '')
								fileService.upload(file).then(function(r){
									insertAction('insertImage', '/uploads/'+r.data.filename, true);
								}, function(e) { console.log(e); });
						};

						reader.readAsDataURL(file);
						return true;
					}
					return false;
				};
		}]);


})();




(function() {

	'use strict';

	fabLink.$inject = ["$window", "utils"];
	angular
		.module('iserveu')
		.service('fabLink', fabLink);

  	 /** @ngInject */
	function fabLink($window, utils) {

		return function(el) {

			var container = document.getElementById('maincontent'),
				element   = el.children().eq(0).children().eq(0);

			angular
				.element(container)
				.bind('scroll', function() {

					if( !utils.isElementInViewport(document.getElementById('userbar')) 
						&& container.clientHeight == (container.scrollTop + 130)) 
						element.css({ 'top': '10px' });
					else
						element.css({ 'top': '81px' });

					if( container.scrollTop == 55)
						element.css({ 'top': '81px' });
			});

			angular
				.element($window)
				.bind('scroll', function() {

					if( !utils.isElementInViewport(document.getElementById('userbar')) 
						|| $window.clientHeight == ($window.scrollTop + 130))
						element.css({ 'top': '10px' });
					else
						element.css({ 'top': '81px' });

					if( $window.scrollTop == 55)
						element.css({ 'top': '81px' });

			});
		}







	}



})();
(function() {
	
	'use strict';


	fileService.$inject = ["$http"];
	angular
		.module('iserveu')
		.factory('fileService', fileService);


  	 /** @ngInject */
	function fileService($http) {

		var upload = function(file) {

			var fd = new FormData();

			fd.append('file', file);

			return $http.post('file', fd, {
				transformRequest: angular.identity,
				headers: {
					'Content-type': undefined
				}
			}).success(function(r){
				return r;
			}).error(function(e){
				return e;
			});
		}

		return {
			upload: upload
		}

	}

	
})();
(function() {

    'use strict';

    angular
        .module('iserveu')
.factory('PubSub', ["socket", function (socket) {
    var container =  [];
    return {
        subscribe: function(options, callback){
            if(options){
                var collectionName = options.collectionName;
                var modelId = options.modelId;
                var method = options.method;
                if(method === 'POST'){
                    var name = '/' + collectionName + '/' + method;
                    socket.on(name, callback);
                }
                else{
                    var name = '/' + collectionName + '/' + modelId + '/' + method;
                    socket.on(name, callback);
                }
                //Push the container..
                this.pushContainer(name);
            }else{
                throw 'Error: Option must be an object';
            }
        }, //end subscribe
 
        pushContainer : function(subscriptionName){
            container.push(subscriptionName);
        },
 
        //Unsubscribe all containers..
        unSubscribeAll: function(){
            for(var i=0; i<container.length; i++){
                socket.removeAllListeners(container[i]);   
            }
            //Now reset the container..
            container = [];
        }
 
    };
}]);


})();
(function() {
	
	'use strict';

	refreshLocalStorage.$inject = ["auth", "user", "SetPermissionsService"];
	angular
		.module('iserveu')
		.service('refreshLocalStorage', refreshLocalStorage);

  	 /** @ngInject */
	function refreshLocalStorage(auth, user, SetPermissionsService) {

		this.init = function(){

			localStorage.removeItem('user');
			localStorage.removeItem('permissions');
			localStorage.removeItem('settings');

			auth.getSettings().then(function(r){

				if(r.data.user) localStorage.setItem('user', JSON.stringify(r.data.user));
				if(r.data.permissions) localStorage.setItem('permissions', JSON.stringify(r.data.user.permissions));
				if(r.data.settings) localStorage.setItem('settings', JSON.stringify(r.data.settings));
			
			});
		};

		this.item = function(name) {

			localStorage.removeItem(name);

			auth.getSettings().then(function(r){

				localStorage.setItem(name, JSON.stringify(r.data.settings));
			
			});
		};

		this.setItem = function(name, jsonArray) {
			
			localStorage.removeItem(name);

			localStorage.setItem(name, JSON.stringify( jsonArray ));
		};
	
	}
	


})();
(function() {

	'use strict';

	settings.$inject = ["$http", "auth", "refreshLocalStorage", "appearanceService"];
	angular
		.module('iserveu')
		.factory('settings', settings);

  	 /** @ngInject */
	function settings ($http, auth, refreshLocalStorage, appearanceService) {

		var settingsObj =  {
			initialData: { saving: false },
			getData: function() {
				if (this.initialData) return this.initialData;
				else {
					this.get();
					this.getData();
				}
			},
			get: function() {
				var data = localStorage.getItem('settings');
				if(!data) 
					$http.get('api/setting').success(function(r){
						settingsObj.initialData = r.data;
					}).error(function(e){
						console.log(e);
					});
				else 
					this.initialData = JSON.parse(data);
			},
			save: function(data) {
				console.log(data);
				$http.post('/setting', data).success(function(r){

					refreshLocalStorage.setItem('settings', r);
					settingsObj.initialData.saving = false;

				}).error(function(e) { console.log(e); });
			},
			saveArray: function(name, value) {
				if( angular.isUndefined(value) || value == null || value.length == 0 )
					return 0;

				this.initialData.saving = true;

				this.save({
					'name': name,
					'value': value
				});
			},
			saveTypeOf: function (type, data) {

				if( angular.isString(data) && JSON.parse(data).filename )
					data = JSON.parse(data).filename;

				if ( type === 'palette' )
					this.saveArray( 'theme', appearanceService.assignThemePalette(data) );
				else 
					this.saveArray( type, data );					
			}
		}

		settingsObj.get();

		return settingsObj;

	}


})();
(function() {

	'use strict';

	socket.$inject = ["$rootScope"];
	angular
		.module('iserveu')
		.factory('socket', socket);


	function socket($rootScope){

	// 	var socket = io.connect('http://192.168.10.10:3000');

	// 	var userId = $rootScope.authenticatedUser.id;

	//     socket.on('connection:UserWithId'+userId+'IsVerified', function(data){

	// 		localStorage.removeItem('permissions');
	// 		localStorage.setItem('permissions', JSON.stringify(data.permissions));

	//         socket.emit('authentication', {token: token, userId: userId });
	//         socket.on('authenticated', function() {
	//             // use the socket as usual
	//             console.log('User is authenticated');
	//         });
	//     });

	//     return socket;
	
	}


}());
(function() {

	'use strict';

	spinner.$inject = ["settings"];
	angular
		.module('iserveu')
		.directive('spinner', spinner);

  	 /** @ngInject */
	function spinner(settings) {

		// TODO: make loading circle changeable from settings array

		return {
			transclude: true,
			scope: {
				'name': '=',
				'onLoaded': '&',
				'onHide': '=',
			},
			template: ['<md-button type="submit">',
						'<span ng-hide="onHide">{{name}}</span>',
            			'<md-icon md-svg-src="/themes/default/loading.svg" ng-show="onHide">',
            			'</md-icon></md-button>'].join('')
		}

	}



})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('utils', utils);

	function utils() {

		this.capitalize = function(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}

		this.toTitleCase = function(str) {
		    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}

		this.isElementInViewport = function(el) {

		    if (typeof jQuery === "function" && el instanceof jQuery) {
		        el = el[0];
		    }

		    var rect = el.getBoundingClientRect();

		    return (
		        rect.top >= 0 &&
		        rect.left >= 0 &&
		        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
		        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
		    );
		}


		
	}


})();
(function() {
	
	'use strict';

	termsAndConditions.$inject = ["settings", "loginService"];
	angular
		.module('iserveu')
		.directive('termsAndConditions', termsAndConditions);


  	 /** @ngInject */
	function termsAndConditions(settings, loginService) {
	  	 /** @ngInject */
  		controllerMethod.$inject = ["$mdDialog", "$scope"];
		function controllerMethod($mdDialog, $scope) {
        	
        	TermsAndConditionsController.$inject = ["$scope", "$mdDialog"];
        	var vm = this;

        	vm.showTermsAndConditions = showTermsAndConditions;
        	vm.agree   = false;
    		vm.hasRead = false;
    		
        	function showTermsAndConditions(ev, create){
			    if(vm.hasRead === false){
				    $mdDialog.show({
				      controller: TermsAndConditionsController,
				      templateUrl: 'app/shared/termsandconditions/termsandconditions.tpl.html',
				      parent: angular.element(document.body),
				      targetEvent: ev,
				      clickOutsideToClose:false
				    }).then(function(answer){
				    	if( answer === 'agree' )
				        	vm.agree = true;
				    	if( answer === 'agree' && create === true ) {
				    		loginService.createUser();
				    		vm.hasRead = true;
				    	}
				    	else
				    		vm.hasRead = false;
				    });
				}
        	}

        	function TermsAndConditionsController($scope, $mdDialog){
    		  $scope.hide = function() {
			    $mdDialog.hide();
			  };
			  $scope.cancel = function() {
			    $mdDialog.cancel();
			  };
			  $scope.answer = function(answer) {
			    $mdDialog.hide(answer);
			  };
        	
			  $scope.settings = settings.getData();

        	}

  		}	

		return {
		    controller: controllerMethod,
		    controllerAs: 'ctrl',
		    bindToController: true,
		    scope: true
		}
	}
}());
(function() {

	'use strict';

	backgroundimage.$inject = ["$resource", "$http", "CSRF_TOKEN", "$auth"];
	angular
		.module('iserveu')
		.factory('backgroundimage', backgroundimage);

	/** @ngInject */
	function backgroundimage($resource, $http, CSRF_TOKEN, $auth) {

		var getBackgroundImages = function() {
			return $http.get('background_image').success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		};

		var saveBackgroundImage = function(fd) {
			return $http.post('api/background_image', fd, {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        transformRequest: angular.identity
		    }).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		var getBackgroundImage = function(id) {
			return $http.get('api/background_image/' + id).success(function(result) {
				return result;
			}).error(function(error){
				return error;
			});
		}

		var updateBackgroundImage = function(data){
			return $http.put('api/background_image/'+data.id, data).success(function(result){
				return result;
			}).error(function(error){
				return error;
			})
		}

		var deleteBackgroundImage = function(id){
			return $http.delete('api/background_image/'+id).success(function(result){
				return result;
			}).error(function(error){
				return error;
			})
		}

		return {
		  	getBackgroundImages: getBackgroundImages,
		  	saveBackgroundImage: saveBackgroundImage,
		  	getBackgroundImage: getBackgroundImage,
		  	updateBackgroundImage: updateBackgroundImage,
		  	deleteBackgroundImage: deleteBackgroundImage
		};
	}

	
})();
(function(){


	'use strict';

	commentObj.$inject = ["$stateParams", "comment", "ToastMessage"];
	angular
		.module('iserveu')
		.factory('commentObj', commentObj);

	/** @ngInject */
	function commentObj($stateParams, comment, ToastMessage) {

		var factory = {
			comment: null,
			comments: { agree: null, disagree: null, vote: null },
			editing: false,
			writing: false,
			posting: false,
			getUserComment: function(r){
				this.comment = r;
				if(this.comment && this.comment.text)
					this.writing = false;
				else
					this.writing = true;
			},
			getMotionComments: function(id){
				comment.getMotionComments(id).then(function(r) {
					factory.getUserComment(r.thisUsersComment);
					factory.comments.agree = r.agreeComments;
					factory.comments.disagree = r.disagreeComments;
					factory.comments.vote = r.thisUsersCommentVotes;
				});
			},
			submit: function(vote_id, text){
				this.posting = true;

				var data = {
	                vote_id: vote_id,
	                text: text
	            }

	            comment.saveComment(data).then(function(r) {
	                ToastMessage.simple("Comment post successful!");
	            	factory.getMotionComments($stateParams.id);
	                factory.writing = factory.posting = false;
	            }, function(e){ ToastMessage.report_error(e); });    
			},
			writeComment: function() {
				this.writing = !this.writing;
			},
			editComment: function() {
				factory.editing = !factory.editing;
			},
			update: function(text) {
	            var d = {
	                id: factory.comment.id,
	                text: factory.comment.text
	            }
	            comment.updateComment(d).then(function(r) {
	            	factory.getMotionComments($stateParams.id);
	                ToastMessage.simple("Commment updated!");
	            });
			},
			delete: function(){
				ToastMessage.destroyThis("comment", function() {
                    comment.deleteComment(factory.comment.id).then(function(r) {
						factory.getMotionComments($stateParams.id);
						ToastMessage.simple("Comment deleted.")
                    }); 
				});
			},
		};

		factory.getMotionComments($stateParams.id);

		return factory;
	}

})();
(function() {

	'use strict';

	commentVoteObj.$inject = ["$stateParams", "commentvote", "commentObj", "ToastMessage"];
	angular
		.module('iserveu')
		.factory('commentVoteObj', commentVoteObj);

	/** @ngInject */
	function commentVoteObj($stateParams, commentvote, commentObj, ToastMessage) {

		var obj = {
			loading: false,
			save: function (id, pos) {
				if(!obj.loading) {

	            obj.loading = true;

	            commentvote
	            	.saveCommentVotes({comment_id: id, position: pos})
		   				.then(function(r){
		   				commentObj.getMotionComments($stateParams.id);
			            obj.loading = false;
		            },function(e){
		                ToastMessage.report_error(e);
			            obj.loading = false;

		            });  
		
	            }
			},
			update: function(id, pos) {
				if(!obj.loading) {

	            obj.loading = true;

				commentvote.updateCommentVotes({id: id, position: pos})
				 	.then(function(r){
						commentObj.getMotionComments($stateParams.id);
			            obj.loading = false;

		            },function(e){
		                ToastMessage.report_error(e);
			            obj.loading = false;

		            }); 

	            }		
			},
			delete: function(id) {
				if(!obj.loading) {

	            obj.loading = true;

				commentvote.deleteCommentVote(id)
					.then(function(r) {
						commentObj.getMotionComments($stateParams.id);
			            obj.loading = false;

					}, function(e) {
						ToastMessage.report_error(e);
			            obj.loading = false;

					});

	            }		
			},
			onclick: function(id, pos, vote) {
				if ( vote.length === 0 )
					obj.save(id, pos);

				for(var i in vote) {
					if (id === vote[i].comment_id) 

						pos === vote[i].position ? 
							obj.delete(vote[i].id) :
							obj.update(vote[i].id, pos);
					else
						obj.save(id, pos);
				}
			},
			buttonClass: function(id, pos, votes){
				for( var i in votes ) {
					if ( id === votes[i].comment_id )
						if ( votes[i].position === 1 && pos == 1) 
							return 'md-primary';
						else if ( votes[i].position === -1 && pos == -1) 
							return 'md-accent';
				}
			},
			iconClass: function(id, pos, votes) {
				if (votes.length === 0)
					return pos == 1 ? 'thumb-up-outline' : 'thumb-down-outline';

				for( var i in votes ) {
					if ( id === votes[i].comment_id )
						if ( pos == 1) 
							return votes[i].position === 1 ? 'thumb-up' : 'thumb-up-outline';
						else if ( pos == -1) 
							return votes[i].position === -1 ?'thumb-down' : 'thumb-down-outline';
				}
			}
		};

		return obj;

	}




})();
(function() {

	'use strict';

	DepartmentSidebarController.$inject = ["$rootScope", "department"];
	angular
		.module('iserveu')
		.controller('DepartmentSidebarController', DepartmentSidebarController);

	/** @ngInject */
	function DepartmentSidebarController($rootScope, department) {

		var vm = this;

		vm.departments = [];

		$rootScope.$on('departmentSidebarRefresh', function(event, data){
			getDepartments();
		});

		function getDepartments (){
            department.getDepartments().then(function(result){
              	  vm.departments = result;
            });
        } 
	
        getDepartments();

	}



}());
(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('departmentSidebar', departmentSidebar);

	/** @ngInject */
  function departmentSidebar() {

    return {

      templateUrl: 'app/components/department/department-sidebar/department-sidebar.tpl.html'
      
    }

  }
  
})();
(function() {

	'use strict';

	editHome.$inject = ["$state", "settings", "ToastMessage", "dropHandler"];
	angular
		.module('iserveu')
		.directive('editHome', editHome);

	/** @ngInject */
	function editHome($state, settings, ToastMessage, dropHandler) {

		function editHomeController() {

			var vm = this;

			vm.settings = settings.getData();
			vm.dropHandler = dropHandler;

			vm.save = function() {
				settings.saveArray('home', vm.settings.home);
			}

			vm.cancel = function() {
	            ToastMessage.cancelChanges(function(){
	            	$state.go('home');
	            });
			};

			vm.setLogo = function(json) {
				vm.settings.home.introduction.icon = "/uploads/"+JSON.parse(json).filename;
			}

		}


		function editHomeLink(scope, el, attrs) {

			scope.$watch(
				'edit.settings.saving',
				function redirect(newValue, oldValue) {
					console.log(newValue);
					if(newValue == false && oldValue == true)
						$state.go('home');
				}
			);

		}



		return {
			controller: editHomeController,
			controllerAs: 'edit',
			link: editHomeLink,
			templateUrl: 'app/components/home/edit-home/edit-home.tpl.html'
		}

	}



})();
(function() {

	'use strict';

	homeFab.$inject = ["fabLink"];
	angular
		.module('iserveu')
		.directive('homeFab', homeFab);

	/** @ngInject */
	function homeFab(fabLink) {

		function homeFabController() {
			this.isOpen = false;
		}

		function homeFabLink(scope, el, attrs) {
			fabLink(el);
		}

		return {
			controller: homeFabController,
			controllerAs: 'fab',
			link: homeFabLink,
			templateUrl: 'app/components/home/home-fab/home-fab.tpl.html'
		}

	}


})();
(function() {
	
	angular
		.module('iserveu')
		.service('isMotionOpen', isMotionOpen);

	function isMotionOpen() {
		
		var val = '';

		this.get = function() {
			return val;
		}

		this.set = function(value) {
			val = value;
		}

	}


})();


(function() {

	'use strict';

	motion.$inject = ["$resource", "$q", "$http", "ToastMessage"];
	angular
		.module('iserveu')
		.factory('motion', motion);

	 /** @ngInject */
	function motion($resource, $q, $http, ToastMessage) {

		var Motion = $resource('api/motion/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    var GetTopMotion = $resource('api/motion/', {
               rank_greater_than:0, take:1
		}, {});

	    var MotionRestore = $resource('api/motion/:id/restore');

		function getMotions(data) {
			return Motion.get(data).$promise.then(function(results) {
				console.log(results);
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotion(id) {
			return Motion.get({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateMotion(data) {
			return Motion.update({id:data.id}, data).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function createMotion(data) {
			return Motion.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				ToastMessage.report_error(error);
			});
		}

		function deleteMotion(id) {
			return Motion.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});

		}

		function restoreMotion(id) {
			return MotionRestore.get({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getTopMotion() {
			return GetTopMotion.get().$promise.then(function(results) {
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}



		return {
			getMotions: getMotions,
			getMotion: getMotion,
			createMotion: createMotion,
			updateMotion: updateMotion,
			deleteMotion: deleteMotion,
			restoreMotion: restoreMotion,
			getTopMotion: getTopMotion
		}
	}
})();

(function() {

	'use strict';

	motionObj.$inject = ["$http", "motion", "isMotionOpen", "voteObj"];
	angular
		.module('iserveu')
		.factory('motionObj', motionObj);

	 /** @ngInject */
	function motionObj($http, motion, isMotionOpen, voteObj) {

		var motionObj = {
			data: [],
			next_page: 1,
			motionsAreEmpty: false,
			getMotions: function() {
				return $http({
                    method: "GET",
                    url: "/api/motion",
                    params: {
                         page : motionObj.next_page
                    }
              	}).then(function successCallback(r){

					motionObj.data = motionObj.data.length > 0 ? motionObj.data.concat(r.data.data) : r.data.data;
					motionObj.next_page = r.data.next_page_url ? r.data.next_page_url.slice(-1) : null;

					return motionObj.data;

				}, function errorCallback(e){
					console.log('cannot get motions');
					motionObj.motionsAreEmpty = true;
					return e;
				});
			},
			getMotionObj: function(id) {
				for(var i in this.data) {
					if( id == this.data[i].id )
						return this.data[i];
				}
			},
			reloadMotionObj: function(id) {
				motion.getMotion(id).then(function(r){
					for(var i in motionObj.data) {
						if( id == motionObj.data[i].id )
							motionObj.data[i] = r;
					}
				})
			}
		};

		return motionObj;

	}


})();
(function() {

	'use strict';

	pagesFab.$inject = ["$stateParams", "pageObj", "fabLink", "ToastMessage"];
	angular
		.module('iserveu')
		.directive('pagesFab', pagesFab);

  	 /** @ngInject */
	function pagesFab($stateParams, pageObj, fabLink, ToastMessage) {

		function pagesFabController() {

			this.pageObj = pageObj;

			this.isOpen = false;

			this.destroy = function () {
				ToastMessage.destroyThis("page", function() {
					pageObj.delete($stateParams.id);
				});
			};
		};

		function pagesFabLink(scope, el, attrs) {
			fabLink(el);
		};

		return {
			controller: pagesFabController,
			controllerAs: 'fab',
			link: pagesFabLink,
			templateUrl: 'app/components/pages/pages-fab/pages-fab.tpl.html'
		}

	}


})();
(function() {

	'use strict';

	community.$inject = ["$http"];
	angular
		.module('iserveu')
		.factory('community', community);

	/** @ngInject */
	function community($http) {

		var factory = {
			getIndex: function () {
				$http.get('/api/community').success(function(r){
					factory.index = r;
				}).error(function(e){ console.log(e); });
			},
			index: {}
		}

		factory.getIndex();

		return factory;


	}

})();
(function() {

	'use strict';

	ethnicOriginService.$inject = ["$http"];
	angular
		.module('iserveu')
		.factory('ethnicOriginService', ethnicOriginService);

  	 /** @ngInject */
	function ethnicOriginService($http) {

		function getEthnicOrigins(){
	        return $http.get('api/ethnic_origin/').then(function(r){
	            return r.data;
	        });
		}

		function getEthnicOrigin(id){
	    	return $http.get('api/ethnic_origin/'+id).then(function(r){
	            return r.data;
	        });
		}

		return {
			getEthnicOrigins: getEthnicOrigins,
			getEthnicOrigin: getEthnicOrigin
		};

	}

}());
(function() {

	'use strict';

	user.$inject = ["$resource", "$q", "$rootScope"];
	angular
		.module('iserveu')
		.factory('user', user);

  	 /** @ngInject */
	function user($resource, $q, $rootScope) {

		var User = $resource('api/user/:id', {}, {
	        'update': { method:'PUT' }
	    });
		var UserEdit = $resource('api/user/:id/edit');
		var YourUser = $resource('api/settings');

		function getIndex() {
			return User.get().$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getUserInfo(data){
			return User.get(data).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getUser(id){
			return User.get({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function storeUser(info){
			return User.save(info).$promise.then(function(result){
				return result;
			}, function(error) {
				return $q.reject(error);
			})
		}

		//change name to get fields
		function editUser(id){
			return UserEdit.query({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);				
			});
		}

		function updateUser(data){
			return User.update({id:data.id}, data).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteUser(id){
			return User.delete({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function getSelf() {
			if ( $rootScope.authenticatedUser ) return $rootScope.authenticatedUser;
			else if ( localStorage.getItem('user') ) return JSON.parse(localStorage.getItem('user'));
			else if( $rootScope.userIsLoggedIn ) getSelf();
		}

		return {
			getIndex: getIndex,
			getUserInfo: getUserInfo,
			getUser: getUser,
			editUser: editUser,
			updateUser: updateUser,
			deleteUser: deleteUser,
			storeUser: storeUser,
			self: getSelf()
		}




	}

})();
(function() {

	'use strict';

	voteOnMotion.$inject = ["$rootScope", "$stateParams", "$timeout", "vote", "voteObj", "motionObj", "SetPermissionsService", "voteButtonMessage", "isMotionOpen"];
	angular
		.module('iserveu')
		.directive('voteOnMotion', voteOnMotion);

  	 /** @ngInject */
	function voteOnMotion($rootScope, $stateParams, $timeout, vote, voteObj, motionObj, SetPermissionsService, voteButtonMessage, isMotionOpen) {


		voteController.$inject = ["$scope"];
		function voteController($scope) {
			// variables
			var vm = this;
			vm.voting = {'1': false, '0':false, '-1': false};

			// DOM accessors for controller functions
			vm.castVote			 = castVote;
			vm.isVotingEnabled   = isVotingEnabled;
			vm.voteButtonMessage = voteButtonMessage;
			vm.voteObj			 = voteObj;

			// I wonder if I can share this via the quick-vote.dir.js
			function castVote(id, pos) {

				if( isVotingEnabled() )
					return 0;

				if( voteObj.user && voteObj.user.position != pos && voteObj.user.position != null) {
					vm.voting[pos] = true;
					updateVote(pos);
				}
				else {
					vm.voting[pos] = true;

					vote.castVote({
						motion_id: id,
						position: pos
					}).then(function(r) {
						successFunc(r, pos);
					}, function(e){ errorFunc(e, pos); });
				}
			}


			function updateVote(pos) {

				var data = {
					id: voteObj.user.id,
					position: pos
				}

				vote.updateVote(data).then(function(r) {
					successFunc(r, pos);
				}, function(e){ errorFunc(e, pos); });
			}

			function successFunc(r, pos){
				vm.voting[pos] = false;
				motionObj.reloadMotionObj(r.motion_id);
				voteObj.successFunc(r, pos, false);
			}

			function errorFunc(e, pos){
				vm.voting[pos] = false;
				ToastMessage.report_error(e);
			}

			function isVotingEnabled() {
				return !isMotionOpen.get() || !SetPermissionsService.can('create-votes');
			}

			$scope.$watch('v.voteObj.user', function(newValue, oldValue) {
				if( !angular.isUndefined(newValue) )
					// some sort of digest conflict, doesn't work without the slight 
					// offset of the timeout
					if(newValue.motion_id == $stateParams.id)
		            	$timeout(function() {
		                    voteObj.user  = newValue ? newValue : {position: null} ;     
							voteObj.calculateVotes(newValue.motion_id);
		            	}, 100);
			}, true);

			$rootScope.$on('usersVoteHasChanged', function(ev, data) {
				voteObj.user = data.vote;
			});
		}

		return {
			controller: voteController,
			controllerAs: 'v',
			templateUrl: 'app/components/vote/partials/vote.tpl.html'
		}

	}


})();
(function() {

	'use strict';

	vote.$inject = ["$resource", "$q", "$http"];
	angular
		.module('iserveu')
		.factory('vote', vote);

  	 /** @ngInject */
	function vote($resource, $q, $http) {

		var Vote = $resource('api/vote/:id', {}, {
	        'update': { method:'PUT' }
	    });

		var MyVotes = $resource('api/user/:id/vote', {limit:'@limit'});

		// This function uses an $http request as opposed to resource because 
		// it doesn't expect an object or an array and uses tranformRequest
		// to create an angular identity.

	    var getMotionVotes = function(id) {
	    	return $http.get('api/motion/'+id+'/vote', {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        transformRequest: angular.identity
		    }).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
	    }


	    // set into local storage array that updates
	    function getMyVotes(id, limit) {
			return MyVotes.get({id:id}, limit).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function castVote(data) {
			return Vote.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateVote(data) {
			return Vote.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getUsersVotes() {
			return Vote.query().$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}


		return {
			getMyVotes: getMyVotes,
			getMotionVotes: getMotionVotes,
			castVote: castVote,
			updateVote: updateVote,
			getUsersVotes: getUsersVotes
		}

	}



}());
(function() {
	
	voteButtonMessage.$inject = ["$translate", "SetPermissionsService", "isMotionOpen"];
	angular
		.module('iserveu')
		.service('voteButtonMessage', voteButtonMessage);

	/** @ngInject */
	function voteButtonMessage($translate, SetPermissionsService, isMotionOpen) {

		// TODO: this as a constant watcher is slowing shit DOWN.
		// figure out a way to destroy after awhile or two-way bind
		// correct data.
		return function(votes, type){

			if ( !SetPermissionsService.can('create-votes') )

				return "You need to fill out your profile before you can vote.";

			else if ( !isMotionOpen.get() ) {

				for(var i in votes) 
					if ( votes[i] ) return type + $translate.instant('MOTION');

				return "This "+ $translate.instant('MOTION') + " is closed.";
			
			}

			else return type + $translate.instant('MOTION');
		}


	}


})();


(function() {

	'use strict';

	voteObj.$inject = ["$rootScope", "commentObj", "$stateParams", "vote", "ToastMessage"];
	angular
		.module('iserveu')
		.factory('voteObj', voteObj);

  	 /** @ngInject */
	function voteObj($rootScope, commentObj, $stateParams, vote, ToastMessage) {

		var factory = {
			user: { position: null },
			motionVotes: {
		            disagree:{percent:0,number:0},
		            agree:{percent:0,number:0},
		            abstain:{percent:0,number:0},
		            deferred_agree:{percent:0,number:0},
		            deferred_disagree:{percent:0,number:0},
		            deferred_abstain:{percent:0,number:0}
		    },
		    votes: {},
		    calculateVotes: function(id) {
		    	// TODO: figure out how to make this DOM obj not disappear each time a user votes.
		    	for(var i in this.motionVotes) {
		    		for(var j in this.motionVotes[i])
		    			this.motionVotes[i][j] = 0;
		    	}

		    	vote.getMotionVotes(id).then(function(r){

		    		var votes = factory.votes = r.data;

		            if(votes[1]){
		            	factory.motionVotes.agree = ( votes[1].active ) 
		            								? votes[1].active  
		            								: factory.motionVotes.agree; 
		                factory.motionVotes.deferred_agree = ( votes[1].passive ) 
		                									 ? votes[1].passive 
		                									 : factory.motionVotes.deferred_agree;
		            }
		            if(votes[-1]){
		                factory.motionVotes.disagree = ( votes[-1].active ) 
		                							   ? votes[-1].active 
		                							   : factory.motionVotes.disagree;
		                factory.motionVotes.deferred_disagree = ( votes[-1].passive ) 
		                										? votes[-1].passive 
		                										: factory.motionVotes.deferred_disagree;
		            }
		            if(votes[0]){
		            	factory.motionVotes.abstain =  ( votes[0].active ) 	
		            								   ? votes[0].active  
		            								   : factory.motionVotes.abstain;
		                factory.motionVotes.deferred_abstain = ( votes[0].passive ) 
		                									   ? votes[0].passive 
		                									   : factory.motionVotes.deferred_abstain;
		            }

		            return factory.motionVotes;
	            });
		    },
		    showMessage: function(pos) {
				pos = pos == 1 
					  ? 'agreed with' 
					  : ( pos == 0 ? 'abstained on' : 'disagreed with');
				
				ToastMessage.simple( 'You ' + pos + " this motion" );
		    },
		    getOverallPosition: function() {

		    	var position;

	            if(this.motionVotes.disagree.number > this.motionVotes.agree.number)
	                position = "thumb-down";
	            else if(this.motionVotes.disagree.number < this.motionVotes.agree.number)
	                position = "thumb-up";
	            else
	                position = "thumbs-up-down";

	            return position; 
		    },
		    successFunc: function(vote, id, pos, quickVote) {
		    	if(!quickVote){
					factory.user = vote;
					factory.calculateVotes(vote.motion_id);	// vm.motionVotes will be an object Factory;
		    	}

				factory.showMessage(pos);
				commentObj.getMotionComments(vote.motion_id);  // this does not seem to work with $watch in another directive. still doesn't belong here though.

				$rootScope.$broadcast('usersVoteHasChanged', {vote: vote});
		    }
		};

		factory.calculateVotes($stateParams.id);

		return factory;
		
	}

})();
(function() {

	'use strict';

    login.$inject = ["settings", "loginService", "auth", "resetPasswordService", "ToastMessage"];
	angular
		.module('iserveu')
		.controller('loginController', login);

  	 /** @ngInject */
	function login(settings, loginService, auth, resetPasswordService, ToastMessage) {	

		this.service = loginService;
		this.settings = settings.getData();

		this.extendRegisterForm = extendRegisterForm;
		this.forgotPassword = forgotPassword;
		this.sendResetPassword = sendResetPassword;
		this.confirm_email = '';

		function extendRegisterForm() {
			this.registerform = !this.registerform;
		};

		function forgotPassword() {
			this.passwordreminder = !this.passwordreminder;
		};

		function sendResetPassword(){
			auth.getResetPassword( loginService.credentials ).then(function(r) {

				ToastMessage.simple('Your email has been sent!');

			}, function(e) { console.log(e); });
		};

		resetPasswordService.check();
    }

}());
(function() {

	'use strict';

	loginService.$inject = ["$rootScope", "auth", "afterauth", "ToastMessage"];
	angular
		.module('iserveu')
		.factory('loginService', loginService);

  	 /** @ngInject */
	function loginService($rootScope, auth, afterauth, ToastMessage) {

		var loginObj = {
			creating: false,
			loggingIn: false,
			publicComputer: false,
			credentials: { email: '', password: '' },
			newUser: { first_name: '',
					   last_name: '',
				       email: '',
					   password: '' },
			errors: { emailNotValid: false,
					  invalidCredentials: false,
					  invalidEmail: false,
					  accountLocked: false },
			createUser: createUser,
			login: login,
		};

		function login(credentials) {

			loginObj.loggingIn = true;

			auth.login( credentials )
				.then(function(r) {

				loginObj.loggingIn = false;
				$rootScope.authenticatedUser = r.data.user;
				setLocalStorage( credentials );
			
			}, function(e) {

				loginObj.loggingIn = false;
				errorHandler( e.data.message );
			
			});		
		};

		function createUser() {

			loginObj.creating = true;
			
			auth.postUserCreate( loginObj.newUser )
				.then(function(r){

				loginObj.creating = false;
				
				login({email: loginObj.newUser.email, 
					   password:loginObj.newUser.password} );
			
			}, function(e) {

				loginObj.creating = false;
				errorHandler( JSON.parse(e.data.message) );

			});
		};

		function setLocalStorage(credentials) {

			auth.postAuthenticate( credentials )
				.then(function(r) {
				
				afterauth.setLoginAuthDetails(r.data.user);
				localStorage.setItem('public_computer', loginObj.publicComputer);
			
			});
		};


		function errorHandler(message) {
			for (var i in loginObj.errors) 
				loginObj.errors[i] = false;

			if( message == "Invalid credentials" )
				loginObj.errors.invalidCredentials = true;
			else if(message == "Email address not in database")
				loginObj.errors.invalidEmail = true;
			else if(angular.isString(message) && message.substr(0, 17) == 'Account is locked')
				loginObj.errors.accountLocked = true;
			else if( message.hasOwnProperty('email') )
				if( message.email[0] == "validation.unique" )
					loginObj.errors.emailNotValid = true;
			else
				ToastMessage.report_error(message);
		};

		return loginObj;
	}


})();
(function() {
	
	'use strict';

	compareTo.$inject = ["$compile"];
	angular
		.module('iserveu')
		.directive('compareTo', compareTo);

  	 /** @ngInject */
	function compareTo($compile) {

		function linkMethod(scope, element, attrs, ngModel) {

			ngModel.$validators.compareTo = function(modelValue) {
				return modelValue == scope.otherModelValue;
			};

			scope.$watch("otherModelValue", function() {
				ngModel.$validate();
			});
		}
		
		return {
			restrict: 'AE',
			require: "^ngModel",
			scope: {
				otherModelValue: "=compareTo"
			},
			replace: true,
			link: linkMethod
		}

	}

}());
(function() {
	
	'use strict';

	resetPassword.$inject = ["$compile"];
	angular
		.module('iserveu')
		.directive('resetPassword', resetPassword);

  	 /** @ngInject */
	function resetPassword($compile) {
		
		controllerMethod.$inject = ["$state", "user", "ToastMessage"];
		function controllerMethod($state, user, ToastMessage) {
	
			var vm = this;

			vm.notification = 'false';

			if($state.current.name == 'login.resetpassword'){
				vm.notification = true;
			}

			vm.savePassword = function(){
				var data = {
					id: JSON.parse(localStorage.getItem('user')).id,
					password: vm.password
				}

				user.updateUser(data).then(function(){
					vm.notification = false;
					ToastMessage.simple("Thank you for reseting your password.")
				});
			}

		}


		function linkMethod(scope, element, attrs, controller){

			attrs.$observe('hasBeen', function(value){
				if(value == 'false'){
					element.remove('resetPassword');
				}
			})

		}

	return {
		controller: controllerMethod,
		controllerAs: 'reset',
		bindToController: true,
		link: linkMethod,
		templateUrl: 'app/shared/auth/passwordreset/resetpassword.tpl.html'
	}

	}

}());
(function() {

	'use strict';

	resetPasswordService.$inject = ["$stateParams", "$state", "ToastMessage", "auth", "afterauth", "user", "$timeout", "$rootScope", "$mdDialog"];
	angular
		.module('iserveu')
		.service('resetPasswordService', resetPasswordService);

  	 /** @ngInject */
	function resetPasswordService($stateParams, $state, ToastMessage, auth, afterauth, user, $timeout, $rootScope, $mdDialog) {

		this.check = function(){
			
			if($state.current.name == 'login.resetpassword')

				if($rootScope.userIsLoggedIn === true){
					auth.logout();
					localStorage.clear();
					$rootScope.userIsLoggedIn = false;
					postToken();
				} else postToken();	
		};

		function postToken(){

			auth.getNoPassword($stateParams.resetpassword).then(function(data) {
					
					afterauth.setLoginAuthDetails(data, data.data.token);

				}, function(error) {
					if(error.status === 404){
						localStorage.clear();
						$rootScope.userIsLoggedIn = false;
						$state.go('login');
						ToastMessage.simple("Sorry! Your lost password token has expired.");
					}
					if(error.status === 403)
						console.log('no token provided');
			});

		}	

	}
}());
(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('showFooter', footer);

	function footer() {

		
		return {
			templateUrl: 'app/shared/nav/footer/footer.tpl.html'
		}

	}

}());
(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('searchFactory', searchFactory);

	function searchFactory() {

		var searchObj = {
			query: {},
			text: '',
			searchFunc: function() {
				var data = '';
				
				return data;			
			}
		}

		return searchObj;

	}


})();
(function() {

	'use strict';

	SidebarController.$inject = ["motion", "$rootScope", "$mdSidenav", "$mdMedia"];
	angular
		.module('iserveu')
		.controller('SidebarController', SidebarController);

  	 /** @ngInject */
	function SidebarController(motion, $rootScope, $mdSidenav, $mdMedia) {

		var vm = this;

		$rootScope.$mdMedia = $mdMedia;
		vm.keepOpen = false;

		vm.toggleSidenav = function(menuId){
			$mdSidenav(menuId).toggle().then(function(){
				vm.keepOpen = !$rootScope.keepOpen;
			});
		}

		vm.closeSidenav = function(menuId){
			$mdSidenav(menuId).close().then(function(){
				vm.keepOpen = false;
			});
		}

	}

})();
(function() {
	
	'use strict';

	sidebar.$inject = ["$compile"];
	angular
		.module('iserveu')
		.directive('sidebar', sidebar);

  	 /** @ngInject */
	function sidebar($compile) {

  		controllerMethod.$inject = ["motion", "$scope", "$location", "$state", "$rootScope"];
		function linkMethod(scope, element, attrs) {

			scope.$watch('currentState', function() {
				angular
					.element(document.getElementById('sidebar-inner'))
					.empty()
					.append($compile("<div class='" + attrs.sidebar + "-sidebar'" + attrs.sidebar + "-sidebar></div>")(scope));
			});

		}

		function controllerMethod(motion, $scope, $location, $state, $rootScope) {
        
  		}	
		
		return {
			restrict: 'E',
			link: linkMethod,
			controller: controllerMethod
		}

	}

}());


(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('userBar', userBar);

	function userBar(){
		
	  	 /** @ngInject */
		UserbarController.$inject = ["$translate", "$mdSidenav", "auth", "afterauth", "UserbarService", "SetPermissionsService", "pageObj"];
		function UserbarController($translate, $mdSidenav, auth, afterauth, UserbarService, SetPermissionsService, pageObj) {

			var vm = this;

			vm.userbarservice = UserbarService;
			vm.setpermissionservice = SetPermissionsService;
			vm.pageObj = pageObj;
			vm.preferredLang = "English";
			vm.languages = [{name:'English', key:'en'},
							{name:'French', key:'fr'}];

			vm.changeLanguage = function(langKey){
				$translate.use(langKey);
			}

			vm.logout = function() {
				auth.logout().then(function() {
					afterauth.clearCredentials();
				});
			}

			vm.toggleSidebar = function(id) {
				$mdSidenav(id).toggle(); 
			}
		};

		return {
			controller: UserbarController,
			controllerAs: 'user',
			templateUrl: 'app/shared/nav/userbar/userbar-production.tpl.html'
		}

	}
})();
(function() {

	'use strict';

	UserbarService.$inject = ["$rootScope"];
	angular
		.module('iserveu')
		.service('UserbarService', UserbarService);

  	 /** @ngInject */
	function UserbarService($rootScope) {
 		
 		var vm = this;

 		vm.title = "-";

 		vm.setTitle = function(value){
 			vm.title = value
 		}

	}	
})();

(function() {

	'use strict';

	RedirectController.$inject = ["UserbarService", "$timeout", "$state"];
	angular
		.module('iserveu')
		.controller('RedirectController', RedirectController);

  	 /** @ngInject */
	function RedirectController(UserbarService, $timeout, $state) {

		UserbarService.setTitle("Woops!");

		var vm = this;

		vm.seconds = 6;

		vm.timer = {
			seconds: 5000
		}

		function countHandler(){
			vm.seconds = vm.seconds - 1;
			var stopped = $timeout(function(){
				countHandler();
			}, 1000);


			if(vm.seconds === 0){$timeout.cancel( stopped );};

		}
		
		$timeout(function(){
			$state.go('home');
		}, vm.timer.seconds);

		countHandler();

	}


}());
(function() {

	'use strict';


	errorHandler.$inject = ["$state"];
	angular
		.module('iserveu')
		.service('errorHandler', errorHandler);

	/** @ngInject */
	function errorHandler($state) {






	}



})();
(function() {

	'use strict';

	globalService.$inject = ["$rootScope"];
	angular
		.module('iserveu')
		.service('globalService', globalService);

	/** @ngInject */
	function globalService($rootScope) {

		/**
		*	Initializes global variables.
		*
		*/
		this.init = function() {
			$rootScope.themename = 'default';
	        $rootScope.motionIsLoading = [];
		};

		/**
		*	Checks that the user's credentials are set up in the local storage.
		*	Assigns global variables that are checked in the view model
		*   throughout the app.
		*/
		this.checkUser = function() {
			var user = JSON.parse(localStorage.getItem('user'));
			
			if(user) {
				$rootScope.authenticatedUser = user;
				$rootScope.userIsLoggedIn = true;
			};
		};

		/**
		*	Points current state name to a rootScope variable that is 
		*	accessed throughout the app for the sidebar directive 
		*	which dynamically renders the state's sidebar.
		*/
		this.setState = function(state) {
			$rootScope.currentState = state.name;
		};


	}

})();
(function() {

	'use strict';

	redirect.$inject = ["$rootScope", "$state"];
	angular
		.module('iserveu')
		.service('redirect', redirect);

	/** @ngInject */
	function redirect($rootScope, $state) {

		/**
		*	Redirect function for when a user is forwarded to a site URL and
		*	logs in. They will be redirected the previous state they were
		*	at before being rejected by authentication.
		*/
		this.onLogin = function(state, params, prevState) {
			if(state.name !== 'login')
				if (state.name !== 'login.resetpassword') {
				$rootScope.redirectUrlName = state.name;
				$rootScope.redirectUrlID = params.id;
				$rootScope.previousUrlID = prevState.id;
			};
		};
	
		/**
		*	Redirect when a user is not authenticated via AuthController
		*	or they have somehow lost their localstorage credentials. Logs the user
		*	out and redirects them to login state.
		*/
		this.ifNotAuthenticated = function(ev, requireLogin, auth, state, prevState) {
			if(auth === false && requireLogin === true){
				ev.preventDefault();
				if(prevState !== 'login' || state !== 'login')
					$state.go('login');
			};
		}; 



	}

})();
(function(){
	'use strict';

	appearance.$inject = ["settings", "palette", "ToastMessage"];
	angular
		.module('iserveu')
		.directive('appearanceManager', appearance);

	/** @ngInject */
	function appearance(settings, palette, ToastMessage) {

		appearanceController.$inject = ["$scope"];
		function appearanceController($scope) {

			this.settings = settings.getData();
			this.service  = settings;
			this.palette  = palette;

			$scope.$watch(
				'appearance.settings.saving',
				function redirect(newValue, oldValue){
					if(newValue == false && oldValue == true)
						ToastMessage.reload(800);
			});
		}


		return {
			controller: appearanceController,
			controllerAs: 'appearance',
			templateUrl: 'app/components/admin/partials/appearance/appearance.tpl.html'
		}



	}



})();
(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('appearanceService', appearanceService);

	/** @ngInject */
	function appearanceService() {

		/**
		*	Parses the palette arrays and passes to assignHueColors.
		*/
		this.assignThemePalette = function(palette) {

			var result = {};

			palette.accent.warning = palette.accent.hue_one;

			for(var i in palette) 
				if( i !== 'blank')
				result[i] = assignHueColors( palette[i], palette.blank[i] );
				
			return result;
		};

		/**
		*	Because mdThemingProvider accepts a large palette of colors,
		*	but realistically we only want 3 colors for our palette 
		*	on the site's content and as part of the UI configuration
		* 	for the user. This function parses and pushes these values
		* 	to settings API.
		*/
		function assignHueColors(array, palette) {


			for(var i in array) {

				var val = array[i];

				switch (i) {
					case 'hue_one': 
						palette[ '50' ] = val.substr(1);
						setHue(100, 300, val.substr(1), palette);
						break;
					case 'hue_two':
						setHue(400, 600, val.substr(1), palette);
						break;
					case 'hue_three':
						setHue(700, 900, val.substr(1), palette);
						break;
					case 'warning':
						setHue(100, 700, val.substr(1), palette, true);
						break;
					default:
						palette['contrastDefaultColor'] = val;
						break;
				};
			};

			return palette;
		};

		/*	
		*	Sets the hues to fill the palette given by mdThemingProvider.
		*
		*/
		function setHue(min, max, val, palette, prefix)
		{
			for(var hue = min; hue <= max; hue = hue + 100) {
				
				if(prefix) {
					hue = hue == 200 ? 400 : ( hue == 400 ? 700 : hue);
					palette['A' + hue ] = val;
				} 
				else palette[ hue ] = val;
			}
		}

	}


})();
(function() {

	'use strict';

	paletteObj.$inject = ["settings"];
	angular
		.module('iserveu')
		.factory('palette', paletteObj);

	/** @ngInject */
	function paletteObj(settings) {

		var settings = settings.getData();

		/**
		*	UI models for theming palette.
		*/
		return {
			// 'Blank' is required for the parsing. It reuses the object array given 
			// from settings. If you were to create an object array, it creates
			// a 901 length array because of the key identifiers. 
			blank: settings.theme, 
			primary: {
				hue_one: '#'+settings.theme.primary['50'],
				hue_two: '#'+settings.theme.primary['400'],
				hue_three: '#'+settings.theme.primary['700'],
				warning: '#'+settings.theme.primary['A700'],
				contrast: settings.theme.primary['contrastDefaultColor']
			},
			accent: {
				hue_one: '#'+settings.theme.accent['50'],
				hue_two: '#'+settings.theme.accent['400'],
				hue_three: '#'+settings.theme.accent['700'],
				contrast: settings.theme.accent['contrastDefaultColor']
			}
		};	
	}


})();
(function() {

	'use strict';

	contentManager.$inject = ["$state", "pageObj", "settings", "dropHandler", "ToastMessage"];
	angular
		.module('iserveu')
		.directive('contentManager', contentManager);

	/** @ngInject */
	function contentManager($state, pageObj, settings, dropHandler, ToastMessage) {

		function contentController() {

			this.pages = pageObj;
			this.service = settings;
			this.settings = settings.getData();
			this.dropHandler = 	dropHandler;

			this.deletePage = function(slug) {
				ToastMessage.destroyThis("page", function() {
					pageObj.delete(slug);
				});
			};

		};


		return {
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin/partials/content/content-manager.tpl.html',
		}


	}


})();
(function() {
	
	departmentManager.$inject = ["departmentManagerService"];
	angular
		.module('iserveu')
		.directive('departmentManager', departmentManager);

	function departmentManager(departmentManagerService) {

		departmentManagerController.$inject = ["$scope"];
		function departmentManagerController($scope) {

			$scope.department = departmentManagerService;

		}


		return {
			controller: departmentManagerController,
			templateUrl: 'app/components/admin/partials/department/department-manager.tpl.html'
		}


	}

})();
(function() {
	
	departmentManagerService.$inject = ["$state", "$timeout", "department", "ToastMessage"];
	angular
		.module('iserveu')
		.factory('departmentManagerService', departmentManagerService);

    /** @ngInject */
	function departmentManagerService($state, $timeout, department, ToastMessage) {

		var factory = {
			list: {},
			success: {},
			disabled: {},
			edit: function(id) {
				for(var i in this.disabled)
	           		this.disabled[i] = true;
	            this.disabled[id] = !this.disabled[id];
			},
			save: function(name, id) {
				this.success[id] = true;
            	department.updateDepartment({
            		id: id,
            		name: name
            	}).then(function(r) {
            		factory.successHandler(r, id); 
            	}, function(e){
            		factory.errorHandler(e);
            	});
			},
			destroy: function(name, id) {
				ToastMessage.destroyThis(name, 
					function(){
						department.deleteDepartment(id);
				});
			},
			create: function(name) {
				department.addDepartment({
					name: name,
					active: 1
				}).then(function(r) {
					successHandler(r);
				}, function(e) {
					errorHandler(e);
				})
			},
			pressEnter: function(ev, name, id) {
				if(ev.keyCode === 13)
					this.save(name, id);
			},
			hasMany: function(id) {
				// TODO: php scope to see how many motions these departments are attached to
				// it and return it in a toast message <3
			},
			successHandler: function(r, id) {
				this.edit('promise');
				this.success[id] = false;
			},
			errorHandler: function(r, id) {
				this.edit('promise');
			}
		}

        department.get().then(function(r){
            factory.list = r.data;

            for(var i in r.data){
            	factory.success[ r.data[i].id ]  = false;
            	factory.disabled[ r.data[i].id ] = true;
            }
        });

		return factory;
	}


})();


(function () {

	'use strict';

	angular
		.module('iserveu')
		.directive('imageManager', imageManager);

	function imageManager() {


		function imageController() {

		}


		return {
			controller: imageController,
			controllerAs: 'image',
			templateUrl: 'app/components/admin/partials/images/images.tpl.html'
		}


	}



})();
(function() {

	'use strict';

	userManager.$inject = ["user"];
	angular
		.module('iserveu')
		.directive('userManager', userManager);
		// TODO: refactor the CSS of the template.
	/** @ngInject */
	function userManager(user) {


		function userController() {

			var vm = this;

			vm.users = {};

			user.getIndex().then(function(r) {
				vm.users = r.data;
			}, function(e) { console.log(e); });

		};


		return {
			controller: userController,
			controllerAs: 'user',
			templateUrl: 'app/components/admin/partials/user/user-manager.tpl.html',
		}


	}


})();
(function() {

	'use strict';

	backgroundImageManager.$inject = ["settings", "fileService", "ToastMessage"];
	angular
		.module('iserveu')
		.directive('backgroundImageManager', backgroundImageManager);

	/** @ngInject */
	function backgroundImageManager(settings, fileService, ToastMessage) {

		function backgroundimageController() {

			this.settings = settings.getData();

			this.today = this.settings.theme.background_image 
						? this.settings.theme.background_image :
						'/themes/default/photos/background.png';

			this.save = function(file) {
				settings.saveArray('background_image', '/uploads/'+JSON.parse(this.uploaded).filename);
			}
		}



		return {
			controller: backgroundimageController,
			controllerAs: 'bkg',
			templateUrl: 'app/components/backgroundimage/components/backgroundimage-manager/backgroundimage-manager.tpl.html'
		}
	}


})();
(function() {

	'use strict';

		backgroundSidebar.$inject = ["$rootScope", "$scope", "$filter", "backgroundimage", "ToastMessage"];
	angular
		.module('iserveu')
		.controller('BackgroundImageSidebarController', backgroundSidebar);

		/** @ngInject */
		function backgroundSidebar($rootScope, $scope, $filter, backgroundimage, ToastMessage){

			var vm = this;

			vm.activateMotion = activateMotion;

			function backgroundImages(){
				backgroundimage.getBackgroundImages().then(function(result) {
					vm.backgroundimages = result.data;
					checkIfDisplayed(vm.backgroundimages);
				}, function(error) {
					console.log(error);
				});
			}

			function activateMotion(id, active){
				var data = {
					id: id,
					active: active
				}

				backgroundimage.updateBackgroundImage(data).then(function(result){
				}, function(error){
					ToastMessage.report_error();
				})
			}

			function checkIfDisplayed(){

				var todayDate = new Date();
				todayDate = $filter('date')(todayDate, "yyyy-MM-dd HH:mm:ss");

				angular.forEach(vm.backgroundimages, function(value, key){
					var counter = 0;
					angular.forEach(value, function(type, key){
						if(key == 'active' && type == 1){
							counter++;
						}
						if(key == 'display_date' && type <= todayDate){
							counter++;
						}
					})
					if(counter == 2){
						value['hasBeenDisplayed'] = true;
						vm.hasBeenDisplayed = true;
					}
				})
			}

			$rootScope.$on('backgroundImageUpdated', function(event, data) {
				backgroundImages();
			});

			backgroundImages();

		}

	}
)();
(function() {

  'use strict';

  backgroundimageSidebar.$inject = ["SetPermissionsService"];
  backgroundimagePreviewSidebar.$inject = ["SetPermissionsService"];
  angular
    .module('iserveu')
    .directive('backgroundimageSidebar', backgroundimageSidebar)
    .directive('backgroundimage.previewSidebar', backgroundimagePreviewSidebar);


      /** @ngInject */
  function backgroundimageSidebar(SetPermissionsService) {

    return {

      templateUrl: SetPermissionsService.can('administrate-background_images') ? 'app/components/backgroundimage/components/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html' :'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
      
    }
  }
  /** @ngInject */
  function backgroundimagePreviewSidebar(SetPermissionsService) {

    return {

      templateUrl: SetPermissionsService.can('administrate-background_images') ? 'app/components/backgroundimage/components/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html' :'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
      
    }
  }

  
})();
(function() {

	'use strict';

	PreviewImageController.$inject = ["$rootScope", "$stateParams", "$state", "ToastMessage", "backgroundimage", "UserbarService"];
	angular
		.module('iserveu')
		.controller('PreviewImageController', PreviewImageController);

	/** @ngInject */
	function PreviewImageController($rootScope, $stateParams, $state, ToastMessage, backgroundimage, UserbarService) {

		var vm = this;

		vm.filename = "uploads/background_images/";
		vm.updateBackgroundImage = updateBackgroundImage;
		vm.deleteBackgroundImage = deleteBackgroundImage;
		vm.updating = false;

		backgroundimage.getBackgroundImage($stateParams.id).then(function(result){
			vm.fileinfo = result.data;
			vm.filename = vm.filename + result.data.filename;
			UserbarService.title = result.data.filename;
		}, function(error){
			console.log(error);
		})

		function updateBackgroundImage(){
			var data = {
				id: vm.fileinfo.id,
				credited: vm.fileinfo.credited,
				active: vm.fileinfo.active,
				url: vm.fileinfo.url,
				display_date: vm.fileinfo.display_date
			}

			backgroundimage.updateBackgroundImage(data).then(function(result){
				ToastMessage.simple("Successfully updated.");
				$rootScope.$emit('backgroundImageUpdated');
				vm.updating = false;
			}, function(error){
				vm.updating = false;
				ToastMessage.report_error(error);
			})
		}

		function deleteBackgroundImage(){
			backgroundimage.deleteBackgroundImage(vm.fileinfo.id).then(function(result){
				ToastMessage.simple("Image deleted.");
				$state.go('backgroundimage');
				$rootScope.$emit('backgroundImageUpdated');
			}, function(error) {
				ToastMessage.report_error(error);
			})
		}


	}


}());	

(function() {

	'use strict';

	commentvote.$inject = ["$resource", "$q"];
	angular
		.module('iserveu')
		.factory('commentvote', commentvote);

	/** @ngInject */
	function commentvote($resource, $q) {

		var CommentVote = $resource('api/comment_vote/:id', {}, {
	        'update': { method:'PUT' }
	    });

		function saveCommentVotes(data) {
			return CommentVote.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateCommentVotes(data) {
			return CommentVote.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteCommentVote(id) {
			return CommentVote.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}


		return {
			saveCommentVotes: saveCommentVotes,
			updateCommentVotes: updateCommentVotes,
			deleteCommentVote: deleteCommentVote
		}

	}


}());
(function(){

	'use strict';

	commentOnMotion.$inject = ["$stateParams", "commentObj", "voteObj", "motionObj"];
	angular
		.module('iserveu')
		.directive('commentOnMotion', commentOnMotion);

	/** @ngInject */
	function commentOnMotion($stateParams, commentObj, voteObj, motionObj) {

		commentController.$inject = ["$scope"];
		function commentController($scope) {

			$scope.obj = commentObj;
			$scope.vote = voteObj;

			$scope.$watch(voteObj.user, function(vote) {
				$scope.vote.user = vote;
			});
		}

		return {
			controller: commentController,
			templateUrl: 'app/components/comment/partials/comment.tpl.html'
		}
	}

})();
(function() {

	'use strict';

	comment.$inject = ["$resource", "$q"];
	angular
		.module('iserveu')
		.factory('comment', comment);

	/** @ngInject */
	function comment($resource, $q) {

		var Comment = $resource('api/comment/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    var Restore = $resource('api/comment/:id/restore');

	    var MotionComment = $resource('api/motion/:id/comment');

	    var MyComments =  $resource('api/user/:id/comment', {}, {
	      query: {
	        method: 'GET',
	        params: {},
	        isArray: true,
	        transformResponse: function(data, header){
	          //Getting string data in response
	          var jsonData = JSON.parse(data); //or angular.fromJson(data)
			  var comments = [];

	          angular.forEach(jsonData, function(comment){
	            comments.push(comment);
	          });

	          return comments;
	        }
	      }
	    });

	    var motion_comments = [];

		function getComment() {
			return Comment.query().$promise.then(function(results) {
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}

		function saveComment(data) {
			return Comment.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateComment(data) {
			return Comment.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteComment(id) {
			return Comment.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function restoreComment(id){
			return Restore.get({id:id}).$promise.then(function(success) {
				return success;
			}, function(error){
				return $q.reject(error);
			})
		}

		function getMyComments(id) {
			return MyComments.query({id:id}).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionComments(id) {
			return MotionComment.get({id:id}).$promise.then(function(result) {
				motion_comments = result;
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function saveLocalMotionComments(data) {
			motion_comments = data;
		}


		return {
			saveComment: saveComment,
			updateComment: updateComment,
			deleteComment: deleteComment,
			restoreComment: restoreComment,
			getComment: getComment,
			getMyComments: getMyComments,
			getMotionComments: getMotionComments,
			saveLocalMotionComments: saveLocalMotionComments,
		}
	}
})();
(function() {

	'use strict';

	displayComments.$inject = ["commentObj", "commentVoteObj", "voteObj"];
	angular
		.module('iserveu')
		.directive('displayComments', displayComments);

	/** @ngInject */
	function displayComments(commentObj, commentVoteObj, voteObj){

		function displayCommentsController() {

			this.obj = commentObj;
			this.vote = voteObj;
			this.commentVote = commentVoteObj;

			this.formatDate = formatDate;
			function formatDate(d){
				if(d.created_at.diff !== d.updated_at.diff)
					return "edited " + d.updated_at.diff;
				// else if (d.created_at.date > 3 days )
				// return d.created_at.alpha;
				else
					return d.created_at.diff;
			}


		}

		return {
			controller: displayCommentsController,
			controllerAs: 'dc',
			templateUrl: 'app/components/comment/components/display-comments/display-comments.tpl.html'
		}

	
	}

})();
(function() {

	'use strict';


	createMotion.$inject = ["$state", "motion", "UserbarService", "department", "dateService"];
	angular
		.module('iserveu')
		.directive('createMotion', createMotion);

	/** @ngInject */
	function createMotion($state, motion, UserbarService, department, dateService) {

		function createMotionController() {

	    	var vm = this;

	        vm.motion = { closing: new Date() };
	        vm.creating     = false;
	        vm.departments 	= department.self.getData();


	    	vm.newMotion = function(){
	            
	            vm.creating = true;
	            vm.motion.closing = dateService.stringify(vm.motion.closing);

	            motion.createMotion( vm.motion ).then(function(r) {
	            	// TODO: something like this;
	                // $rootScope.$emit('refreshMotionSidebar');  
	                vm.creating = false;
	                $state.go( 'motion', ( {id:r.id} ) );
	            });
			};
		}


		return {
			controller: createMotionController,
			controllerAs: 'create',
			templateUrl: 'app/components/motion/components/create-motion/create-motion.tpl.html'
		}


	}


})();
(function(){

	'use strict';


	editMotion.$inject = ["$rootScope", "$stateParams", "$state", "$mdToast", "motionObj", "motion", "ToastMessage", "department", "dateService"];
	angular
		.module('iserveu')
		.directive('editMotion', editMotion);


	// This is a todo

	 /** @ngInject */
	function editMotion($rootScope, $stateParams, $state, $mdToast, motionObj, motion, ToastMessage, department, dateService){

		function editMotionController() {

			var vm = this;

			vm.departments = department;

	        vm.editMotionMode = false;
	        vm.editingMotion = false;

	        vm.minDate = new Date();

	        vm.updated_motion = [{
	            title: null,
	        }];

	        vm.updateMotion = updateMotion;
	        vm.cancelEditMotion = cancelEditMotion;


	        function initMotion(id) {

        		vm.motion = motionObj.getMotionObj(id);

	        	if ( !vm.motion )
	        		motion.getMotion(id).then(function(r){
	        			vm.motion = r;
	        		});
	        }

	        function cancelEditMotion() {
	            ToastMessage.cancelChanges(function(){
	            	 $state.go('motion', {id: vm.motion.id});
	            });
	        }


	        function updateMotion() {
	            vm.editingMotion = true;
	           	dateService.updateForPost( vm.motion.closing );
	            updateMotionFunction();
	        }

	        function updateMotionFunction(){
	            motion.updateMotion(vm.motion).then(function(r) {
	            	reloadMotionObj(r.id);
	                vm.editingMotion = false;
	                ToastMessage.simple("You've successfully updated this motion!", 800);
	                $state.go( 'motion', ( {id:r.id} ) );

	            }, function(error) {
	                ToastMessage.simple(error.data.message);
	                vm.editingMotion = false;
	            });
	        }

	        initMotion($stateParams.id);
		}

		return {
			controller: editMotionController,
			controllerAs: 'edit',
			templateUrl: 'app/components/motion/components/edit-motion/edit-motion.tpl.html'
		}
		
	}


})();
(function(){

	'use strict';


	motionDrafts.$inject = ["motionObj", "UserbarService"];
	angular
		.module('iserveu')
		.directive('motionDrafts', motionDrafts);

	 /** @ngInject */
	function motionDrafts(motionObj, UserbarService){


		console.log('motiondrafts');


		function motionDraftController() {

			this.motionObj = motionObj;

		};

		return {

			controller: motionDraftController,
			controllerAs: 'draft',
			templateUrl: 'app/components/motion/components/motion-drafts/motion-drafts.tpl.html'

		}

	}

})();
(function() {

	'use strict';

	motionFabToolbar.$inject = ["$state", "$stateParams", "motion", "motionObj", "fabLink", "ToastMessage"];
	angular
		.module('iserveu')
		.directive('motionFabToolbar', motionFabToolbar);

	 /** @ngInject */
	function motionFabToolbar($state, $stateParams, motion, motionObj, fabLink, ToastMessage){

		function motionFabToolbarController() {

			this.deleteMotion = deleteMotion;
			this.isOpen = false;

	        function deleteMotion() {
	        	ToastMessage.destroyThis("motion", function(){
                    motion.deleteMotion($stateParams.id).then(function(r) {
                        $state.go('home');
                        motionObj.getMotions();
                    }, function(e) { ToastMessage.report_error(e); });
	        	});
	        };
		}

		function motionFabLink(scope,el,attrs) {
			fabLink(el);
		}


		return {
			controller: motionFabToolbarController,
			controllerAs: 'fab',
			link: motionFabLink,
			templateUrl: 'app/components/motion/components/motion-fab/motion-fab-toolbar.tpl.html'
		}

	}

})();


(function(){

	'use strict';


	angular
		.module('iserveu')
		.directive('motionFiles', motionFiles);

	function motionFiles(){
	
		function motionFileController() {

			var vm = this;
	        
	        vm.theseFiles        = {};
	        vm.uploadMotionFile  = uploadMotionFile;
	        vm.changeTitleName   = changeTitleName;
	        vm.removeFile        = removeFile;
	        vm.viewFiles         = [];
	        vm.errorFiles        = [];
	        vm.uploadError       = false;
	        vm.upload            = upload;
	        var index            = 0;

	        function upload(file){
	            vm.theseFiles[index] = new FormData();
	            vm.theseFiles[index].append("file", file.file);
	            vm.theseFiles[index].append("file_category_name", "motionfiles");
	            vm.theseFiles[index].append("title", file.name);
	            index++;
	        };

	        function uploadMotionFile(id) {
	            angular.forEach(vm.theseFiles, function(value, key) {
	                motionfile.uploadMotionFile(id, value);
	            });
	        };

	        function changeTitleName(index, name){
	            vm.theseFiles[index].append("title", name);
	        };

	        function removeFile(index){
	            delete vm.theseFiles[index];
	        };

	        vm.validate = function(file){
	            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]){
	                vm.viewFiles.push(file);
	                upload(file);
	            }
	            else {
	                vm.uploadError = true;
	                vm.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
	            }
	        };

		};












	}


})();
(function() {

	'use strict';

	motionfile.$inject = ["$resource", "$q", "$http"];
	angular
		.module('iserveu')
		.factory('motionfile', motionfile);

	 /** @ngInject */
	function motionfile($resource, $q, $http) {

		function uploadMotionFile(motion_id, fd){
			return $http.post('api/motion/'+motion_id+'/motionfile/', fd, {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        transformRequest: angular.identity
		    }).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		// set up resources from ng-resource
		var MotionFile = $resource('api/motion/:motion_id/motionfile/:file_id', {motion_id:'@motion_id', file_id:'@file_id'}, {
	        'update': { method:'PUT' }
	    });

		function getMotionFiles(motion_id){
			return MotionFile.query({motion_id:motion_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}


		function getMotionFile(motion_id, file_id){
			return MotionFile.query({motion_id:motion_id, file_id: file_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		// might function wonky, if that happens add a method for PUT
		function updateMotionFile(data, motion_id, file_id){
			return MotionFile.update(data, {motion_id:motion_id, file_id:file_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteMotionFile(motion_id, file_id){
			return MotionFile.delete({motion_id:motion_id, file_id:file_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			getMotionFiles: getMotionFiles,
			uploadMotionFile: uploadMotionFile,
			getMotionFile: getMotionFile,
			updateMotionFile: updateMotionFile,
			deleteMotionFile: deleteMotionFile
		}


	}

})();
(function() {

	'use strict';

	CreateUserController.$inject = ["$rootScope", "$scope", "$state", "user", "ToastMessage"];
	angular
		.module('iserveu')
		.controller('CreateUserController', CreateUserController);

  	 /** @ngInject */
	function CreateUserController($rootScope, $scope, $state, user, ToastMessage){
		var vm = this;

		vm.first_name;
		vm.middle_name;
		vm.last_name;
		vm.email;

		vm.creating = false;

		vm.createNewUser = function(){

			vm.creating = true;

			var data = {
				first_name: vm.first_name,
				middle_name: vm.middle_name,
				last_name: vm.last_name,
				email: vm.email,
				password: 'password'
			}

			user.storeUser(data).then(function(results) {
				vm.creating = false;
				vm.first_name = '';
				vm.middle_name = '';
				vm.last_name = '';
				vm.email = '';
				$scope.newUserAdminForm.$setPristine();
				ToastMessage.simple("User created successfully!");
			})
		}

	}

})();
(function() {

	'use strict';

	editUser.$inject = ["editUserFactory", "userToolbarService", "roleFactory"];
	angular
		.module('iserveu')
		.directive('editUser', editUser);

	/** @ngInject */
	function editUser(editUserFactory, userToolbarService, roleFactory) {

		editUserController.$inject = ["$scope"];
		function editUserController($scope) {

			userToolbarService.state = '';
			$scope.edit = editUserFactory;
			$scope.roles = roleFactory;
		}

		return {
			controller: editUserController,
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();
(function() {


	'use strict';

	editUserFactory.$inject = ["$stateParams", "$http", "user", "REST", "refreshLocalStorage"];
	angular
		.module('iserveu')
		.factory('editUserFactory', editUserFactory);

	/** @ngInject */
	function editUserFactory($stateParams, $http, user, REST, refreshLocalStorage){

		var factory = {
			/* Function to map form input variables to the variable. */
			map: function(bool){
				return {
					first_name: bool,
					middle_name: bool,
					last_name: bool,
					email: bool,
					date_of_birth: bool,
					address: bool,
					password: bool
				}
			},
			/** Front end conditionals. */
			success: {},
			disabled: {},
			/**
			*  Switch to open and close control form inputs.
			*  UI acts similar to an Accordian. When one
			*  input opens, the rest close.
			*/
			switch: function(type){
				for( var i in this.disabled )
					this.disabled[i] = i == type ? !this.disabled[i] : true;
			},
			/** Function to post to API. */
			save: function(type, data){
				var fd = REST.post.makeData(type, data);
				this.success[type] = true;

				user.updateUser(fd).then(function(r){
					factory.successHandler(r, type);
				}, function(e) { factory.errorHandler(e); });
			},
			/** Function to emulate user press down enter to save. */
			pressEnter: function(ev, type, data){
		    	if( ev.keyCode === 13 )
		    		this.save(type, data);
			},
			successHandler: function(r, type){
				this.success[type] = false;
				this.switch('promise');
				refreshLocalStorage.setItem('user', r);
			},
			errorHandler: function(e, type){
				this.successHandler(type);
				ToastMessage.report_error(e);
			}
		};

		/** Initializes UI variables to control form inputs */
		factory.success  = factory.map(false);
		factory.disabled = factory.map(true);



		return factory;
	}

})();
(function() {

	'use strict';

	displayProfile.$inject = ["$stateParams", "userToolbarService", "user", "vote"];
	angular
		.module('iserveu')
		.directive('displayProfile', displayProfile);

	function displayProfile($stateParams, userToolbarService, user, vote) {

		function displayProfileController() {

			userToolbarService.showInputField = false;
			userToolbarService.state = "{'cursor':'default'}";

			var vm = this;

			vm.retrieving = true;
			vm.votes = null;
			vm.administrator = isAdmin();

	        function isAdmin() {
	        	for( var i in user.self.user_role )
	        		if( user.self.user_role[i] == "Full Administrator")
	        			return true;
	        	return false;
	        }

            vote.getMyVotes($stateParams.id, {limit:5})
            	.then(function(r){
					vm.retrieving = false;
	                if( r.total !== 0 ) 
	                	vm.votes = r.data;
            }, function(e) { vm.retrieving = false; });

		}


		return {
			controller: displayProfileController,
			controllerAs: 'display',
			templateUrl: 'app/components/user/components/profile/display.tpl.html'
		}



	}


})();
(function() {

	'use strict';

	profileToolbar.$inject = ["userToolbarService"];
	angular
		.module('iserveu')
		.directive('profileToolbar', profileToolbar);

	/** @ngInject */
	function profileToolbar(userToolbarService) {


		profileToolbarController.$inject = ["$scope"];
		function profileToolbarController($scope) {

			$scope.toolbar = userToolbarService;

			$scope.$watch("toolbar.edit.success['last_name']",
				function(newValue, oldValue){
					if(newValue === false && oldValue === true)
						userToolbarService.showInputField = false;
			}, true);	
		
		}

		return {
			controller: profileToolbarController,
			templateUrl: 'app/components/user/components/profile/toolbar.tpl.html'
		}

	}

})();
(function() {

	'use strict';

	userToolbarService.$inject = ["$state", "$timeout", "editUserFactory"];
	angular
		.module('iserveu')
		.service('userToolbarService', userToolbarService);

	function userToolbarService($state, $timeout, editUserFactory) {

		this.state = '';
		this.edit = editUserFactory;
		this.save = save;
		this.editField = editField;
		this.pressEnter = pressEnter;

		function save(data) {

			var user = editUserFactory.map(''), j, i;

			for ( i in data )
			for ( j in user )
				if( i === j && isOfTypeName(i) ) 
				user[j] = data[i];

			editUserFactory.save('last_name', user);
		};

		function isOfTypeName(_str) {
			var l = _str.length;
			return _str.substr( l - 4, l ) === 'name';
		};

		function editField() {
			if($state.current.name === 'edit-user')
				this.showInputField = true;
		};

		function pressEnter(ev, data) {
			if( ev.keyCode === 13 )
				save(data);
		};

	}


})();
(function() {

	'use strict';

	userFab.$inject = ["$stateParams", "user", "ToastMessage", "fabLink"];
	angular
		.module('iserveu')
		.directive('userFab', userFab);

	/** @ngInject */
	function userFab($stateParams, user, ToastMessage, fabLink) {

		function userFabController() {
			
			this.isOpen = false;

			this.user = $stateParams;

			this.destroy = function() {

				ToastMessage.destroyThis("user", function(){
					user.deleteUser($stateParams.id);
				});
			
			};


		}

		function userFabLink(scope, el, attrs) {
			fabLink(el);
		}

		return {
			controller: userFabController,
			controllerAs: 'fab',
			link: userFabLink,
			templateUrl: 'app/components/user/components/user-fab/user-fab.tpl.html'
		}

	}


})();
(function() {

	'use strict';

	role.$inject = ["$resource", "$q"];
	angular
		.module('iserveu')
		.factory('role', role);

  	 /** @ngInject */
	function role($resource, $q) {

		var Role = $resource('api/role');

		var UserRole = $resource('api/user/:id/role/:role_id', {id:'@id', role_id:'@role_id'}, {
	        'update': { method:'PUT' }
	    });

		function getRoles(){
			return Role.query().$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function grantRole(data){
			return UserRole.save({id:data.id}, data).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function getUserRole(id){
			return UserRole.query(id).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function deleteUserRole(data){
			return UserRole.delete(data).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		return {
			getRoles: getRoles,
			grantRole: grantRole,
			getUserRole: getUserRole,
			deleteUserRole: deleteUserRole
		}

	}


}());
(function() {


	'use strict';

	roleFactory.$inject = ["$stateParams", "role", "user"];
	angular
		.module('iserveu')
		.factory('roleFactory', roleFactory);

  	 /** @ngInject */
	function roleFactory($stateParams, role, user){

		var factory = {
			list: {},
			editRole: false,
			user: [],
			showRoles: function() {
				this.showEdit = !this.showEdit;
			},
			check: function(d) {
				for(var i in d) 
				for(var j in this.user)
					if(d[i].display_name == this.user[j])
						this.list[i]['hasRole'] = true;
			},
			set: function(role) {
				// if($stateParams.id !== user.self.id)
				this.setRole(role,$stateParams.id);
			},
			setRole: function(role, id) {
				if(!role.hasRole)
					this.grant(role, id);
				else if(role.hasRole)
					this.remove(role, id);
			},
			grant: function (r, id){
				role.grantRole({
					id: id,
					role_name: r.name}).then(function(){
						// refreshLocalStorage.init();
				});
			},
			remove: function (r, id){
				role.deleteUserRole({
					id: id,
					role_id: r.id}).then(function(){
						// refreshLocalStorage.init();
				});
			},
			getAllRoles: function() {
				role.getRoles().then(function(r){
					factory.list = r;
					factory.check(r);
				});
			}
		}

		factory.getAllRoles();

		return factory;

	}

})();
(function() {

  'use strict';

  	userSidebar.$inject = ["$rootScope", "SetPermissionsService"];
  angular
    .module('iserveu')
    .directive('userSidebar', userSidebar);

   /** @ngInject */
  function userSidebar($rootScope, SetPermissionsService) {

  	return {

      templateUrl: SetPermissionsService.can('administrate-users') ? 

      	'app/components/user/components/user-sidebar/user-sidebar.tpl.html' :
      
      	'app/components/motion/components/motion-sidebar/motion-sidebar.tpl.html'
      
      }

  	}

})();
(function() {


	'use strict';

    motionSidebarSearch.$inject = ["$timeout", "department", "motionObj", "motion", "searchFactory"];
    angular
      .module('iserveu')
      .directive('motionSearchbar', motionSidebarSearch);


    // TODO: start refactoring and cleaning up the code. Simplifying.
     /** @ngInject */
    function motionSidebarSearch($timeout, department, motionObj, motion, searchFactory) {

    	function controllerMethod() {
    		
        	var vm = this;

    		vm.departmentObj = department.self;

    		vm.orderByFilters = [
			   // {name: "Popularity" 		,query: "search_query_popularity"}, 
			   {name: "Newest"     		,query: {oldest: true}},
			   {name: "Oldest"	   		,query: {newest: true}},
			   // {name: "Open for Voting" ,query: {is_active:true, is_current:true}},   // CHECK PHP, not working.
			   {name: "Closed"			,query: {is_expired:true}}
			];


			vm.motion_filters = {
				take: 100,
				limit: 100,
				next_page: 1,
				oldest: true,
				is_active: true,
				is_current: true
			}

			vm.departmentFilter = {id: ''};
			vm.orderByFilter;
			vm.newFilter = [];

			vm.showSearch = false;

			vm.searchText = '';
			vm.searching  = false;

			vm.searchInput = function() {
				searchFactory.text = vm.searchText;
			}

			vm.querySearch = function(filter){

				vm.searching = true;

				var temp_arr = Object.getOwnPropertyNames(filter);
				temp_arr.pop();			//removes $mdSelect event thats bundled with var filter
				emptyMotionFilters();

				angular.forEach(temp_arr, function(fil, key){
					vm.motion_filters[fil] = true;
				})

				if(angular.isNumber(vm.departmentFilter)){
					filter['department_id'] = vm.departmentFilter;
					vm.motion_filters.push(vm.departmentFilter);
				}
				return motion.getMotions(filter).then(function(result){
					vm.newFilter = filter;
					vm.searching = false;
					return motionObj.data = result.data;
				})
			}

			vm.querySearchDepartment = function(filter) {

				vm.newFilter['department_id'] = filter.department_id;
				emptyMotionFilters();
				vm.motion_filters['department_id'] = filter.department_id;

				vm.searching = true;

				return motion.getMotions(vm.newFilter).then(function(result){
					vm.newFilter = vm.newFilter;
					vm.searching = false;
					return motionObj.data = result.data;
				})
			}

			function emptyMotionFilters() {
				var temp_filters = vm.motion_filters;

				vm.motion_filters		   = [];
				vm.motion_filters['take']  = temp_filters.take;
				vm.motion_filters['limit'] = temp_filters.limit;
				vm.motion_filters['next_page']  = temp_filters.next_page;
			}

			vm.showSearchFunc = function(){
				if(vm.showSearch)
					vm.searchText = searchFactory.text = '';

				vm.showSearch = !vm.showSearch;
			}

			function getMotions(filter){			
				
				vm.searching = true;
				motion.getMotions(filter).then(function(results) {
					motionObj.data = results.data;
					motionObj.next_page = null;
					vm.searching = false;
				});
			};

			vm.showAll = function() {
				vm.departmentFilter = '';
				vm.orderByFilter 	= '';
				emptyMotionFilters();
				getMotions(vm.motion_filters);
			}

      }

      return {
	      	controller: controllerMethod,
	        controllerAs: 'search',
	        templateUrl: 'app/components/motion/components/motion-sidebar/partials/motion-sidebar-search.tpl.html'
      }
      
    }
  
})();

(function(){

	'use strict';

	angular
		.module('iserveu')
		.directive('trendingIcons', trendingIcons);

	function trendingIcons() {



		return {
			templateUrl: 'app/components/motion/components/motion-sidebar/partials/motion-sidebar-trending.tpl.html'
		}

	}


})();
(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('motionSidebar', motionSidebar);
   
  function motionSidebar() {

  	 /** @ngInject */
	MotionSidebarController.$inject = ["$mdSidenav", "motionObj", "searchFactory", "department"];
	function MotionSidebarController($mdSidenav, motionObj, searchFactory, department) {

		var vm = this;

		/* Variables */
		vm.motionObj		 = motionObj;
		vm.motionListLoading = motionObj.data.length > 0 ? false : true;
		vm.search		 	 = searchFactory; 

		
		/* HTML access to functions */
		vm.loadMoreMotions   = loadMoreMotions;		
		vm.closeSidenav      = function(id){ $mdSidenav(id).close(); }


		/* Pagination function. Runs off of ngInfiniteScroll library. When
		*  the sidebar reaches the bottom, this function is triggered. 
		*  @motionListLoading: boolean for DOM spinner when loading.
		*/
		function loadMoreMotions() {

			vm.motionListLoading = vm.paginating = true;

			motionObj.getMotions().then(function(r){
				vm.motionListLoading = vm.paginating = false;
			});
		};
	
		loadMoreMotions();


	};

    return {
    	controller: MotionSidebarController,
    	controllerAs: 'sidebar',
      	templateUrl: 'app/components/motion/components/motion-sidebar/partials/motion-sidebar.tpl.html'
    }
    
  }
  
})();
(function() {

  'use strict';

  motionSidebarQuickVote.$inject = ["vote", "voteObj", "ToastMessage", "SetPermissionsService"];
  angular
    .module('iserveu')
    .directive('quickVote', motionSidebarQuickVote);

 /** @ngInject */
  function motionSidebarQuickVote(vote, voteObj, ToastMessage, SetPermissionsService) {

  	function controllerMethod() {

  		var vm = this;

        vm.canCreateVote = SetPermissionsService.can('create-votes');
        vm.cycleVote = cycleVote;
        vm.voteObj = voteObj;

		function cycleVote (motion){

			if(!motion.MotionOpenForVoting)
				ToastMessage.simple("This motion is not open for voting.", 1000);
			else{ 
				if(!motion.user_vote)
					castVote(motion.id);
				else{

					var data = {
		                id: motion.user_vote.id,
		                position: null
		            }
					
					if(motion.user_vote.position != 1)
						data.position = motion.user_vote.position + 1; 
					else
						data.position = -1;

					updateVote(data);
				};
			};
		}

		function castVote(id){
			vote.castVote({
				motion_id:id, 
				position:0}).then(function(r){
				successFunc(r, 0, true);
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(r) {
				successFunc(r, 0, data.position);
			});
		}

		function successFun(vote, pos) {
			motionObj.reloadMotionObj(vote.motion_id);
			voteObj.successFunc(vote, pos, true);
		}

  	}


    return {
    	controller: controllerMethod,
    	controllerAs: 'c',
    	templateUrl: 'app/components/motion/components/motion-sidebar/partials/quick-vote.tpl.html'
    }
    
  }
  
})();
//# sourceMappingURL=app.js.map