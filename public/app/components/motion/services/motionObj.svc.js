
(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionObj', [
			'$http', '$timeout', 'motion', 'isMotionOpen', 'voteObj', 'commentObj', 'motionFilesFactory', 'utils',
			motionObj]);

	 /** @ngInject */
	function motionObj($http, $timeout, motion, isMotionOpen, voteObj, commentObj, motionFilesFactory, utils) {

		var factory = {
	  		/* Variables */
			data: [],
			next_page: 1,
			motionsAreEmpty: false,
			details: {},
			isLoading: true,
			getMotions: function() {
				return $http({
                    method: "GET",
                    url: "/api/motion",
                    params: {
                         page : factory.next_page
                    }
              	}).then(function successCallback(r){

					factory.data = factory.data.length > 0 ? factory.data.concat(r.data.data) : r.data.data;
					factory.next_page = r.data.next_page_url ? r.data.next_page_url.slice(-1) : null;

					return factory.data;

				}, function errorCallback(e){
					console.log('cannot get motions');
					factory.motionsAreEmpty = true;
					return e;
				});
			},
			getMotionObj: function(id) {
				for(var i in this.data) {
					if( id == this.data[i].id )
						return this.data[i];
				}
				return motion.getMotion(id).then(function(r){ return r; });
			},
			reloadMotionObj: function(id) {
				motion.getMotion(id).then(function(r){
					for(var i in factory.data) {
						if( id == factory.data[i].id )
							factory.data[i] = r;
					}
				});
			},
	        /**
	        *	Sets the motion dependencies that
	        *	is shared across the app modules.
	        */
			setMotionDependencies: function(motion) {
				this.details = motion;

	            commentObj.getMotionComments(motion.id);  
	            voteObj.calculateVotes(motion.id); 
	            voteObj.user = motion.user_vote ? motion.user_vote : {position: null};
	            isMotionOpen.set(motion.MotionOpenForVoting);
	            isMotionOpen.setStatus(motion.status);
	            motionFilesFactory.get(motion.id);
				
				$timeout(function() {
					factory.isLoading = false;
				}, 2500);
			},
			clearMotionDependencies: function() {
	            commentObj.comment  = null;
	            this.details   = null;
	            this.isLoading = true;
	            voteObj.voteLoading = true;
			},
			clear: function() {
				utils.clearArray(this.data);
				this.next_page = 1;
			}
		};

		return factory;

	}


})();