(function() {
	
	angular
		.module('iserveu')
		.directive('motionForm', [
			'$state', '$stateParams', '$timeout', '$isuApiProvider', 'motionObj', 
			'motionfile', 'ToastMessage', 'department', 'motionFilesFactory', 'errorHandler', 
			'utils', 'SETTINGS_JSON',
			motionForm]);

	function motionForm($state, $stateParams, $timeout, $isuApiProvider, motionObj, motionfile, ToastMessage, department, motionFilesFactory, errorHandler, utils, SETTINGS_JSON) {

		function motionFormController($scope) {
			var self = this;

			self.createMotion = $state.current.name == 'create-motion' ? true : false;
	        self.motion = { closing: new Date() };
			self.departments = department.self;

			self.motionFile  = motionFilesFactory;
			self.motionFiles = [];
			self.existingMotionFiles = [];

	        self.successHandler = function(r) {
	            motionFilesFactory.attach(r.id, self.motionFiles);
	            $timeout(function() {
		           	$state.go( 'motion', ( {id: r.id} ) );
	            }, 600);
	        };

			self.triggerSpinner = function(val) {
				self.processing = val || !self.processing;
			};

	        self.cancel = function() {
	        	ToastMessage.cancelChanges(function(){
	        		self.createMotion ? $state.go('dashboard') : $state.go('motion', {id: self.motion.id});
        		});
	        };

			/** Initializing function to get motion data. */
			function init(id, mData) {
				if(self.createMotion)
					return 0;

	     		self.motion = mData || motionObj.getMotionObj(id);
	     		
	     		console.log(mData);
	     		if(self.motion.hasOwnProperty('$$state')) // If is a promise, then call self to resolve.
	     			self.motion.then(function(mData){ return init(id, mData);});
	     		
	     		motionfile.getMotionFiles(id).then(function(r){
					self.existingMotionFiles = r;
				});
			};

	        init($stateParams.id);
		}


		function motionFormLink(scope, el, attrs, ctrl) {
			
			el.bind('submit', function(ev){

				ev.preventDefault();

				ctrl.triggerSpinner(true);

				angular.extend($isuApiProvider.defaults, 
					ctrl.createMotion ?
					{target: '/api/motion', method: 'POST'} :
					{target: '/api/motion/'+$stateParams.id, method: 'PATCH'});		

				ctrl.motion.closing = SETTINGS_JSON.allow_closing 
					? utils.date.stringify(ctrl.motion.closing) : new Date(NaN);

				$isuApiProvider.callMethodToApi(ctrl.motion)
					.then(function(s){
						ctrl.triggerSpinner(false);
						ctrl.successHandler(s);
					}, function(e){
						console.log('foo');
						ctrl.triggerSpinner(false);
						errorHandler(e);
					});
			});

		}


		return {
			link: motionFormLink,
			controller: ['$scope', motionFormController],
			controllerAs: 'form',
			templateUrl: 'app/components/motion/partials/form.tpl.html'
		}


	}

})();