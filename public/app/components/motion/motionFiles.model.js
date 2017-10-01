'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.motions')
    .factory('MotionFile', [
      '$http',
      MotionFileFactory]);

  function MotionFileFactory($http) {

    function MotionFile(motionFileData) {
      if (motionFileData) {
        this.setData(motionFileData);
        // this.setIsImage(motionFileData.filename);
      }
    }

    MotionFile.prototype = {

      setData: function(motionFileData) {
        angular.extend(this, motionFileData);
      },

      setIsImage: function(filename) {
        // this.setData({isImage:
        //  ( !!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}
        //  [filename.substr(filename.length - 3)]
        //    ||  filename.substr(filename.length - 4) === 'jpeg' )
        //  });
      },

      load: function(id) {

      },

      update: function(data) {

      },

      delete: function(id) {

      },

      attachToMotion: function(motionId, fileId) {

      }

    };

    return MotionFile;
  }
})(window, window.angular);
