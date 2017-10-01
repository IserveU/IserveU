(function() {

  'use strict';

  angular
    .module('app.motions')
    .factory('MotionIndex', MotionIndexFactory);

  MotionIndexFactory.$inject = ['$http', 'Utils', 'MotionResource'];

  function MotionIndexFactory($http, Utils, MotionResource) {

    var MotionIndex = {

      _index: {},

      _current_page: 0,

      _last_page: null,

      _next_page: 1,

      _paginating: false,

      _stopPaginating: false,

      _load: function() {
        var self = this;

        self._paginating = true;

        MotionResource.getMotionsIndex(this._current_page).then(function(results) {

          self._next_page = self.nextPage(results.next_page_url);
          self._index = results.data;
          self._last_page = results.last_page;
          self._paginating = false;
          self._stopPaginating = results.next_page_url ? false : true;

        }, function(error) {
          throw new Error('Unable to retrieve initial index of motions.');
        });
      },

      clear: function() {
        var originalLength = MotionIndex._index.length;
        for (var i = originalLength; i > 0; i--) {
             MotionIndex._index.pop();
        }

        MotionIndex._current_page = 1;
        MotionIndex._last_page = null;
        MotionIndex._next_page = 2;
        MotionIndex._paginating = false;
        MotionIndex._stopPaginating = false;

        return MotionIndex;
      },

      loadMoreMotions: function() {
        var self = this;

        if(!self._next_page || self._current_page === self._last_page || self._stopPaginating)
          return false;

        self._paginating = true;

        MotionResource.getMotionsIndex(self._next_page).then(function(results) {

          self._next_page = self.nextPage(results.next_page_url);
          self._index = angular.isArray(self._index) ? self._index.concat(results.data) : results.data;
          self._last_page = results.last_page;
          self._paginating = false;
          self._stopPaginating = results.next_page_url ? false : true;

        }, function(error) {
          throw new Error('Unable to retrieve next page of motion index.');
        });
      },

      nextPage: function(url) {
        return url ? Utils.getUrlParameter('page',url) : null;
      },

      retrieveById: function(id) {
        for(var i in this._index) {
          if( id === this._index[i].id )
            return this._index[i];
        }

        return false;
      },

      reloadOne: function(motion) {
        var i = 0;

        for(i in this._index) {
          if( motion.id === this._index[i].id ) {
            this._index[i] = motion;
            return true;
          }
        }

        this._index[++i] = motion;

        return true;
      }
    }

    return MotionIndex;

  }


})();
