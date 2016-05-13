(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('motionForm', [
			'$state', 
			'$stateParams', 
			'$timeout', 
			'isuSectionProvider', 
			'Motion',
			'motionIndex', 
			'motionfile', 
			'ToastMessage', 
			'motionDepartments', 
			'motionFilesFactory',
			'errorHandler', 
			'Authorizer', 
			'utils', 
			'SETTINGS_JSON',
			motionForm]);

	function motionForm($state, $stateParams, $timeout, isuSectionProvider, Motion, motionIndex, motionfile, 
		ToastMessage, motionDepartments, motionFilesFactory, errorHandler, Authorizer, utils, SETTINGS_JSON) {

		function motionFormController($scope) {
			var self = this;

			self.createMotion = $state.current.name == 'create-motion' ? true : false;
			self.departments = motionDepartments;
			self.existingMotionFiles = [];
	        self.motion = { closing: new Date(), status: 0 };
			self.motionFile  = motionFilesFactory;
			self.motionFiles = [];
			self.processing = false;

	        self.cancel = function() {
	        	ToastMessage.cancelChanges(function(){
	        		self.createMotion ? $state.go('dashboard') : $state.go('motion', {id: self.motion.id});
        		});
	        };

	        self.successHandler = function(r) {

	            motionFilesFactory.attach(r.id, self.motionFiles);
	            motionIndex.reloadOne( Motion.build(r) );

	            if(Authorizer.canAccess('edit-motion'))
	            	ToastMessage.simple("Your submission has been sent in for review!");
		            
	            $timeout(function() {
		           	$state.go( 'motion', ( {id: r.id} ), {reload: true} );
	            }, 600);
	        };

			self.triggerSpinner = function(val) {
				self.processing = val || !self.processing;
			};

			/** Initializing function to get motion data. */
			function init(id) {
				if(self.createMotion)
					return 0;

				self.motion = Motion.get(id);

	     		motionfile.getMotionFiles(id).then(function(r){
					self.existingMotionFiles = r;
				});
			}

	        init($stateParams.id);
	        motionDepartments.loadAll();
		}


		function motionFormLink(scope, el, attrs, ctrl) {
			
			el.bind('submit', function(ev){
				ev.preventDefault();

				ctrl.triggerSpinner(true);

				angular.extend(isuSectionProvider.defaults, 
					ctrl.createMotion ?
					{target: '/api/motion', method: 'POST'} :
					{target: '/api/motion/'+$stateParams.id, method: 'PATCH'});		

				ctrl.motion.closing = SETTINGS_JSON.allow_closing 
					? utils.date.stringify(ctrl.motion.closing) : new Date(NaN);

				isuSectionProvider.callMethodToApi(ctrl.motion)
					.then(function(success){
						ctrl.triggerSpinner(false);
						ctrl.successHandler(success);
					}, function(error){
						ctrl.triggerSpinner(false);
						errorHandler(error);
					});
			});
		}


		return {
			link: motionFormLink,
			controller: ['$scope', motionFormController],
			controllerAs: 'form',
			templateUrl: 'app/components/motionForm/motionForm.tpl.html'
		}


	}


})();