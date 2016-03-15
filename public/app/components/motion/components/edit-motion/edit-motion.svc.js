(function() {
	
	angular
		.module('iserveu')
		.factory('editMotionFactory', editMotionFactory);

	 /** @ngInject */
	function editMotionFactory($stateParams, $state, $timeout, motion, motionObj, ToastMessage, REST, motionFilesFactory, utils) {

		var factory = {
			/** Variables */
			motion: {},
			minDate: new Date(),
			editing: false,
			/** Initializing function to get motion data. */
			init: function(id) {

				id = id ? id : $stateParams.id;

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
	                $timeout(function() {
		                $state.go( 'motion', ( {id:r.id} ), {reload: true} );	
	                }, 1000);

	            }, function(e) {
	                ToastMessage.report_error(e.data.message);
	                factory.editing = false;
	            });
			},
			/** Filters out data that needs to be reformatted for posting. */
			updateGuard: function() {

                motionFilesFactory.attach(this.motion.id);

	            this.editing = true;
	           	
	            /** Taken out for localized economies TODO: switch this out for settings.json*/
	           	// this.motion.closing = REST.date.stringify( 
	           	// 					  this.motion.closing.carbon.date );

	           	this.motion.closing = new Date(NaN);

	           	if(!utils.nullOrUndefined(this.motion.section)) {
		           	var section = this.motion.section;
		           	delete this.motion.section;
			        this.motion.section = { content:  section };
			        if(section.id)
			        	this.motion.section.id = section.id;
		    	}

	            this.update();
			},
			pushAvatarArray: function(message) {
				if( !this.motion.section )
					this.motion.section = { bio: {avatar_id: JSON.parse(message).id}};
				else 
					this.motion.section.bio.avatar_id = JSON.parse(message).id;
			}
		}

		factory.init($stateParams.id);

		return factory;
	}


})();

