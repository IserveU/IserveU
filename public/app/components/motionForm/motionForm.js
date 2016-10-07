(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('motionForm', [
			'$rootScope',
			'$state',
			'$stateParams',
			'$timeout',
			'$translate',
			'isuSectionProvider',
			'Motion',
			'motionResource',
			'motionFileResource',
			'fileResource',
			'ToastMessage',
			'motionDepartments',
			'motionFilesFactory',
			'Authorizer',
		motionForm]);

	function motionForm($rootScope, $state, $stateParams, $timeout, $translate, isuSectionProvider, Motion, motionResource, motionFileResource, fileResource,
		ToastMessage, motionDepartments, motionFilesFactory, Authorizer) {

		function motionFormController($scope) {
			var self = this;

			self.createMotion = $state.current.name == 'create-motion' ? true : false;
			self.departments = motionDepartments;
			self.existingMotionFiles = [];
	        self.motion = new Motion({ closing_at: new Date(+new Date + 12096e5), status: 'draft' });
			self.motionFile  = motionFilesFactory;
			self.motionFiles = [];
			self.processing = false;
			self.cancel = cancel;
			self.triggerSpinner = triggerSpinner;
			self.successHandler = successHandler;

	        function cancel() {
	        	ToastMessage.cancelChanges(function(){
	        		self.createMotion ? navigateAway() : $state.go('motion', {id: self.motion.id});
        		});

        		function navigateAway(){
                    motionResource.deleteMotion($stateParams.id);
        			$state.go('dashboard');
        		}
	        };

	        function successHandler(r) {
	        	/** deprecrated */
	            // motionFilesFactory.attach(r.id, self.motionFiles);

	            $rootScope.preventStateChange = true;
	            self.motion.setData(r).refreshExtensions();

	            if(self.motion.id) {
	            	ToastMessage.simple("You successfully updated this " + $translate.instant('MOTION'));
	            } else if(Authorizer.canAccess('edit-motion')){
	            	ToastMessage.simple("Your submission has been sent in for review!");
	            }

	            $timeout(function() {
		           	$state.go( 'motion', ( {id: r.id} ), {reload: true} );
	            }, 600);
	        };

			function triggerSpinner(val) {
				self.processing = val || !self.processing;
			}

			/** Initializing function to get motion data. */
			(function init() {

		        motionDepartments.loadAll();

				// if edit-motion
				if( self.createMotion ) return false;

				self.motion = Motion.get($stateParams.id);

				fileResource.getFiles($stateParams.id).then(function(r){
					console.log(r);
					self.existingMotionFiles = r;
				});

				// TODO: motionFileResource no longer exists
	   //   		motionFileResource.getMotionFiles($stateParams.id).then(function(r){
				// 	self.existingMotionFiles = r;
				// });

			})();
		}

		function motionFormLink(scope, el, attrs, ctrl) {

			var autopost = !!angular.element(el).attr('autopost');

			if(autopost) {
				angular.extend(isuSectionProvider.defaults, {target: '/api/motion', method: 'POST'});
				var motion = ctrl.motion._sanitize();
				isuSectionProvider.callMethodToApi(motion).then(function(success){
					console.log(success);
					$stateParams.id = success.id;
					self.motion = new Motion(success);
					console.log($stateParams);
				}, function(error){
				});
			}

			el.bind('submit', function(ev){
				ev.preventDefault();
				saveMotion();
			});

			function saveMotion() {
				ctrl.triggerSpinner(true);

				angular.extend(isuSectionProvider.defaults,
					(!!!$stateParams.id) ?
					{target: '/api/motion', method: 'POST'} :
					{target: '/api/motion/'+$stateParams.id, method: 'PATCH'});

				var motion = ctrl.motion._sanitize();

				isuSectionProvider.callMethodToApi(motion).then(function(success){
					ctrl.triggerSpinner(false);
					ctrl.successHandler(success);
				}, function(error){
					ctrl.triggerSpinner(false);
				});
			}
		}

		return {
			link: motionFormLink,
			controller: ['$scope', motionFormController],
			controllerAs: 'form',
			templateUrl: 'app/components/motionForm/motionForm.tpl.html'
		}


	}


})();