(function() {
	
	angular
		.module('iserveu')
		.factory('editMotionFactory', editMotionFactory);

	 /** @ngInject */
	function editMotionFactory($stateParams, $state, motion, motionObj, ToastMessage, dateService, motionFilesFactory) {

		var factory = {
			/** Variables */
			motion: {},
			minDate: new Date(),
			editing: false,
			/** Initializing function to get motion data. */
			init: function(id) {
	     		this.motion = motionObj.getMotionObj(id);

	        	if ( !this.motion )
	        		motion.getMotion(id).then(function(r){
	        			motionFilesFactory.get(r.id);
	        			factory.motion = r;
	        		});
			},
			/** Cancel editing and then redirect. */
			cancel: function() {
	            ToastMessage.cancelChanges(function(){
	            	 $state.go('motion', {id: factory.motion.id});
	            });
			},
			/** Method to update moiton. */
			update: function() {
	            motion.updateMotion(this.motion).then(function(r) {
	                
	                factory.editing = false;
	            	motionObj.reloadMotionObj(r.id);
	                ToastMessage.simple(
	                	"You've successfully updated this motion!", 800);
	                $state.go( 'motion', ( {id:r.id} ) );

	            }, function(e) {
	                ToastMessage.report_error(e.data.message);
	                factory.editing = false;
	            });
			},
			/** Filters out data that needs to be reformatted for posting. */
			updateGuard: function() {
	            this.editing = true;
	           	this.motion.closing = dateService.stringify( 
	           						  this.motion.closing.carbon.date );
	            this.update();
                if (motionFilesFactory.files){
                	console.log('files');
	                motionFilesFactory.attach(this.motion.id);
                }

			}
		}

		factory.init($stateParams.id);

		return factory;
	}


})();

