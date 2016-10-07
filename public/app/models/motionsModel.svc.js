(function() {

'use strict';

angular
.module('iserveu')
.factory('Motion', [
	'motionIndex',
	'motionResource',
	'fileResource',
	'MotionComments',
	'MotionFile',
	'MotionVotes',
	'$state',
	'ToastMessage',
	'SETTINGS_JSON',
	'utils',
function(motionIndex, motionResource, fileResource, MotionComments, MotionFile, MotionVotes, $state, ToastMessage, SETTINGS_JSON, utils){

	function Motion(motionData) {
		if(motionData) {
			this.setData(motionData);
		}
	}

	Motion.prototype = {

		_sanitize: function() {
			return angular.extend({}, {
				title: this.title || "New Draft",
				summary: this.summary,
				text: this.text,
				status: this.status,
				department_id: this.department_id,
				closing_at: this.getClosing(),
				user_id: this.user_id,
				id: this.id
			})
		},

		refreshExtensions: function() {
			this.getMotionFiles();
			this.getMotionComments();
			this.getMotionVotes();
			this.reloadMotionIndex();
		},

		setData: function(motionData) {
			angular.extend(this, motionData);
			return this;
		},

		load: function(id) {
			var self = this;
			motionResource.getMotion(id).then(function(result) {
				self.setData(result).refreshExtensions();
			});
		},

		delete: function() {
			var self = this;
			motionResource.deleteMotion(id).then(function(result){
				self.setData(result).refreshExtensions();
				// redirect and toast
			});
		},

		update: function(data) {
			var self = this;
			motionResource.updateMotion(data).then(function(result){
				self.setData(result);
				// redirect and toast
			});
		},

		getClosing: function() {
			if(this.status === 'published') {
				return undefined;
			} else {
				return SETTINGS_JSON.allow_closing ? utils.date.stringify(this.closing_at) : new Date(NaN);
			}
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
			});
		},

		/**
		*	Get the motion files associated with this Motion.
		*/
		getMotionFiles: function(id) {
			console.log('getMotionFiles');
			var self = this;
			id = id || self.id;

			fileResource.getFiles(id).then(function(result){
				console.log(result);
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
			})

			// motionResource.getMotionFiles(id).then(function(result){
			// 	var motionFiles = [];

			// 	for(var i in result) {
			// 		// $$promise being created in the array
			// 		// anyway to strip this out from the return?
			// 		// ninstead of filter? also probably enhance this contract ..
			// 		if(result[i].id) {
			// 			var motionFile = new MotionFile(result[i]);
			// 			motionFiles.push(motionFile);
			// 		}
			// 	}

			// 	if(motionFiles.length > 0){
			// 		self.setData({motionFiles: motionFiles});
			// 	}

			// });
		},

		/**
		*	Get the votes associated with this Motion.
		*/
		getMotionVotes: function(id) {
			var self = this;
			id = id || self.id;

			motionResource.getMotionVotes(id).then(function(result){

				var data = result.data || data;

				if(!('motionVotes' in self)) {
					self.setData({motionVotes: new MotionVotes(data)});
					self.motionVotes.getOverallPosition();
				} else {
					self.motionVotes.reload(data).getOverallPosition();
				}
			});
		},

		reloadMotionIndex: function() {
			return motionIndex.reloadOne(this);
		},

		/**
		*	Update the user's votes attached to this Motion.
		*/
		reloadUserVote: function(vote) {
			this.userVote = {};
			this.userVote = {motion_id: vote.motion_id, id: vote.id, position: +vote.position};
		},

		reloadOnVoteSuccess: function(vote) {
			this.getMotionComments();
			this.getMotionVotes();
			this.reloadUserVote(vote);
			this.reloadMotionIndex();
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

	function errorHandler(error) {
		if(error.status === 404) {
			ToastMessage.simple('The requested page does not exist.')
			$state.go('home');
		}
	}


	return Motion;
}])

})();

