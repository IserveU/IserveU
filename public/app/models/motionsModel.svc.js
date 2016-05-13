(function() {

'use strict';

angular
.module('iserveu')
.factory('Motion', [
	'motionIndex', 
	'motionResource',
	'MotionComments',
	'MotionFile',
	'MotionVotes', 
	'errorHandler', 
function(motionIndex, motionResource, MotionComments, MotionFile, MotionVotes, errorHandler){


	function Motion(motionData) {
		if(motionData) {
			this.setData(motionData);
		} 
	}

	Motion.prototype = {

		setData: function(motionData) {
			angular.extend(this, motionData);
		},

		load: function(id) {
			var self = this;
			motionResource.getMotion(id).then(function(result) {
				self.setData(result);
				self.getMotionComments(id);
				self.getMotionFiles(id);
				self.getMotionVotes(id);
				self.reloadMotionIndex();
			}, function(error) {
				errorHandler(error);
			});
		},

		delete: function() {
			var self = this;
			motionResource.deleteMotion(id).then(function(result){
				self.setData(result);	
				// redirect and toast
			}, function(error){
				errorHandler(error);
			});
		},

		update: function(data) {
			var self = this;
			motionResource.updateMotion(data).then(function(result){
				self.setData(result);	
				// redirect and toast
			}, function(error){
				errorHandler(error)
			});
		},

		/**
		*	Get the comments associated with this Motion.
		*/
		getMotionComments: function(id) {
			var self = this;
			id = id || self.id;
			motionResource.getMotionComments(id).then(function(result){
				var motionComments = new MotionComments(result);
				self.setData({motionComments: motionComments});	
			
			}, function(error){
				// temporary fix for php error
				self.setData({motionComments: null });	

				// errorHandler(error);
			});	
		},

		/**
		*	Get the motion files associated with this Motion.
		*/
		getMotionFiles: function(id) {
			var self = this;
			id = id || self.id;
			motionResource.getMotionFiles(id).then(function(result){
				var motionFiles = [];

				for(var i in result) {
					// $$promise being created in the array
					// anyway to strip this out from the return?
					// ninstead of filter? also probably enhance this contract ..
					if(result[i].id) {
						var motionFile = new MotionFile(result[i]);
						motionFiles.push(motionFile);
					}
				}

				if(motionFiles.length > 0){
					self.setData({motionFiles: motionFiles});
				}

			}, function(error){
				errorHandler(error);
			});
		},

		/**
		*	Get the votes associated with this Motion.
		*/
		getMotionVotes: function(id) {
			var self = this;
			id = id || self.id;
			motionResource.getMotionVotes(id).then(function(result){
				
				var motionVotes = new MotionVotes(result.data);
				self.setData({motionVotes: motionVotes});
				motionVotes.getOverallPosition();

			}, function(error){
				errorHandler(error);
			});
		},

		/**
		*	Update the user's votes attached to this Motion.
		*/
		reloadUserVote: function(vote) {
			return angular.extend(this.user_vote, {}, {position: vote.position});
		},

		reloadMotionIndex: function() {
			return motionIndex.reloadOne(this);
		}

	}

	Motion.build = function(motionData) {

		var motion = new Motion(motionData);

		motion.getMotionComments();
		motion.getMotionFiles();
		motion.getMotionVotes();
		motion.reloadMotionIndex();

		return motion;
	}

	Motion.get = function(id) {
		var motion = motionIndex.retrieveById( id );

		if(!motion) {
			var newMotion = new Motion();
			newMotion.load(id);
			return newMotion;
		}
		else {
			if(motion instanceof Motion) {
				return motion;
			}
			var newMotion = Motion.build(motion);
			return newMotion;
		}
	}


	return Motion;
}])

})();

