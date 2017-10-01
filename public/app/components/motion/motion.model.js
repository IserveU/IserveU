(function (window, angular, undefined) {
 
  'use strict';
  
  angular
    .module('app.motions')
    .factory('Motion', MotionFactory);

  MotionFactory.$inject = [
    '$state',
    'MotionIndex', 
    'MotionResource', 
    'MotionComments', 
    'MotionVotes',
    'MotionFile', 
    'FileResource',
    'ToastMessage',
    'Settings',
    'Utils'
  ];

  function MotionFactory ($state, MotionIndex, MotionResource, MotionComments, MotionVotes, MotionFile, FileResource, ToastMessage,
    Settings, Utils) {

    function Motion(motionData) {
      if (motionData) {
        this.setData(motionData)
      }
    }

    Motion.prototype = {

      _sanitize: function () {
        return angular.extend({}, {
          title: this.title || 'New Draft',
          summary: this.summary,
          text: this.text,
          status: this.status,
          department_id: this.department ? this.department.id : 1,
          closing_at: this.getClosing(),
          user_id: this.user_id,
          id: this.id
        })
      },

      refreshExtensions: function () {
        this.getMotionFiles()
        this.getMotionComments()
        this.getMotionVotes()
        this.reloadMotionIndex()
      },

      setData: function (motionData) {
        angular.extend(this, motionData)
        return this
      },

      load: function (id) {
        var self = this
        MotionResource.getMotion(id).then(function (result) {
          self.setData(result)
          // .refreshExtensions()
        })
      },

      delete: function (id) {
        var self = this
        MotionResource.deleteMotion(id).then(function (result) {
          self.setData(result).refreshExtensions()
          // redirect and toast
        })
      },

      update: function (data) {
        var self = this
        MotionResource.updateMotion(data).then(function (result) {
          self.setData(result)
          // redirect and toast
        })
      },

      getClosing: function () {

        if (this.status !== 'published' || new Date(this.closing_at).valueOf() <= 1) {
          return undefined
        } else if (Settings.get('motion.allow_closing')) {

          if (this.closing_at.carbon) {
            return new Date(this.closing_at.carbon.date)
          }

          return Utils.date.stringify(this.closing_at)
        } else {
          return new Date(NaN)
        }
      },

      /**
      * Get the comments associated with this Motion.
      */
      getMotionComments: function (id) {
        var self = this
        id = id || self.slug
        MotionResource.getMotionComments(id).then(function (result) {
          var motionComments = new MotionComments(result)
          self.setData({motionComments: motionComments})
        }, function (error) {
          // temporary fix for php error
          self.setData({motionComments: null })
        })
      },

      /**
      * Get the rank of this motion.
      * < 0 = agree
      * > 0 = Disagree
      * 0   = tie
      */
      getRank: function (id) {

      },

      // /**
      // * Get all departments.
      // */
      // getDepartments: function() {
      //   return motionDepartmentResource.getDepartments()
      //     .then(function(success) {
      //       return success;
      //     });
      // },

      /**
      * Get the motion files associated with this Motion.
      */
      getMotionFiles: function (id) {
        var self = this
        id = id || self.slug
        FileResource.getFiles(id).then(function (result) {
          var motionFiles = []
          for (var i in result) {
            // $$promise being created in the array
            // anyway to strip this out from the return?
            // ninstead of filter? also probably enhance this contract ..
            if (result[i].id) {
              var motionFile = new MotionFile(result[i])
              motionFiles.push(motionFile)
            }
          }

          if (motionFiles.length > 0) {
            self.setData({motionFiles: motionFiles})
          }
        })

        // MotionResource.getMotionFiles(id).then(function(result){
        //  var motionFiles = [];

        //  for(var i in result) {
        //    // $$promise being created in the array
        //    // anyway to strip this out from the return?
        //    // ninstead of filter? also probably enhance this contract ..
        //    if(result[i].id) {
        //      var motionFile = new MotionFile(result[i]);
        //      motionFiles.push(motionFile);
        //    }
        //  }

        //  if(motionFiles.length > 0){
        //    self.setData({motionFiles: motionFiles});
        //  }

        // });
      },

      /**
      * Get the votes associated with this Motion.
      */
      getMotionVotes: function (id) {
        var self = this
        id = id || self.slug

        MotionResource.getMotionVotes(id).then(function (result) {
          var data = result.data || data

          if (!('motionVotes' in self)) {
            self.setData({motionVotes: new MotionVotes(data)})
            self.motionVotes.getOverallPosition()
          } else {
            self.motionVotes.reload(data).getOverallPosition()
          }
        })
      },

      reloadMotionIndex: function () {
        return MotionIndex.reloadOne(this)
      },

      /**
      * Update the user's votes attached to this Motion.
      */
      reloadUserVote: function (vote) {
        this._userVote = {}
        this._userVote = {
          motion_id: vote.motion_id,
          id: vote.id,
          position: +vote.position
        }
      },

      reloadOnVoteSuccess: function (vote) {
        this.getMotionComments()
        this.getMotionVotes()
        this.reloadUserVote(vote)
        this.reloadMotionIndex()
      }
    }

    Motion.build = function (motionData) {
      var motion = new Motion(motionData)

      motion.getMotionComments()
      motion.getMotionFiles()
      motion.getMotionVotes()
      motion.reloadMotionIndex()

      return motion
    }

    Motion.get = function (id) {
      var motion = MotionIndex.retrieveById(id), newMotion

      if (!motion) {
        newMotion = new Motion()
        newMotion.load(id)
        return newMotion
      } else {
        if (motion instanceof Motion) {
          return motion
        }
        newMotion = Motion.build(motion)
        return newMotion
      }
    }

    return Motion
  }
})(window, window.angular)
