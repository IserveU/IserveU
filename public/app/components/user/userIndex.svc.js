(function() {

'use strict';

angular
	.module('iserveu')
	.factory('userIndex',
		['$http',
     'utils',
		 'userResource',

	function($http, utils, userResource) {

	var userIndex = {

		_index: [],

		_current_page: 1,

		_last_page: null,

		_next_page: 2,

		_paginating: false,

		_stopPaginating: false,

		_load: function() {
			var self = this;

			self._paginating = true;

			userResource.getUsersIndex(this._current_page).then(function(results) {

				self._next_page = self.nextPage(results.next_page_url);
				self._index = results.data;
				self._last_page = results.last_page;
				self._paginating = false;
				self._stopPaginating = results.next_page_url ? false : true;

			}, function(error) {
				throw new Error('Unable to retrieve initial index of users.');
			});
		},

		clear: function() {
			var originalLength = this._index.length;
			for (var i = originalLength; i > 0; i--) {
			     this._index.pop();
			}

			this._current_page = 1;
			this._last_page = null;
			this._next_page = 2;
			this._paginating = false;
			this._stopPaginating = false;

		},

		loadMoreUsers: function() {
			var self = this;

			if(!self._next_page || self._current_page === self._last_page || self._stopPaginating)
				return false;

			self._paginating = true;

			userResource.getUsersIndex(self._next_page).then(function(results) {
      
          
				self._next_page = self.nextPage(results.next_page_url);
				self._index = angular.isArray(self._index) ? self._index.concat(results.data) : results.data;
				self._last_page = results.last_page;
				self._paginating = false;
				self._stopPaginating = results.next_page_url ? false : true;

			}, function(error) {
				throw new Error('Unable to retrieve next page of user index.');
			});
		},

		nextPage: function(url) {
			return url ? utils.getUrlParameter('page',url) : null;
		},

		retrieveById: function(id) {
			for(var i in this._index) {
				if( id === this._index[i].id )
					return this._index[i];
			}

			return false;
		},

		reloadOne: function(user) {
			var i = 0;

			for(i in this._index) {
				if( user.id === this._index[i].id ) {
					this._index[i] = user;
					return true;
				}
			}

			this._index[++i] = user;

			return true;
		}
  
	}

	return userIndex;

}])


})();
