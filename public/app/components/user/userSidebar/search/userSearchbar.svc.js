(function() {

	angular
		.module('app.user')
		.factory('userSearchFactory', ['UserResource','userIndex', userSearchFactory]);

     // TODO: needs documentation
	function userSearchFactory(UserResource, userIndex) {

		var factory = {
			text: '',
			searching: false,
			isOpen: false,

			_filters: {
				take: 100,
				limit: 100,
				next_page: 1,
				oldest: true,
				is_active: true,
				is_current: true
			},
			_role: {
				filters: [
				   {name: "Citizen", query: {'roles': ["citizen",""]}},
				   {name: "Participant", query: {'roles': ["participant",""]}},
				   {name: "Representative", query: {'roles': ["representative",""]}},
				   {name: "Administrator", query: {'roles': ["administrator",""]}}

				],

				filter: ''

			},
			_identity: {
				filters: [
				   {name: "Unverified", query: {'id': 0}},
				   {name: "Verified", query: {'id': 1}}

				],

				filter: ''

			},
			_addressVerified: {
				filters: [
				   {name: "Unverified", query: {'id': 0}},
				   {name: "Verified", query: {'id': 1}}

				],

				filter: ''

			},
			_newFilter: [],

			_filteredBy: '',

			show: function() {
				if(this.isOpen)
					this.text = '';
				this.isOpen = !this.isOpen;
			},
			//normal casual text search 
			searchAll: function(event) {
				if(event.keyCode === 8 && !this.text)
				{
					this.text = this.text.substring(0,this.text.length-1);
				}
				var data = {
					'allNames': this.text // TODO alter function to take paramaters from searchbar input
				};
				UserResource.getUsers(data).then(function (result) {
		          userIndex._index = result.data;
		          userIndex._next_page = null;
		          factory.searching = false;
		        });
			},
			all: function() {
				this._role.filter = '';
				this._identity.filter = '';
				this._addressVerified.filter = '';
				this.clearFilters();
				this.getResults(this._filters);
			},
			searchSpecific: function() {
				
				this._newFilter['roles[]'] = this._role.filter.roles;
				this._newFilter['identityVerified'] = this._identity.filter.id;
				this._newFilter['addressVerified'] = this._addressVerified.filter.id;

 				//sanitize the data in case user has not chosen the filter.
 				var sanitized = {}; 
  				for (var key in this._newFilter) {
   					if (this._newFilter[key] !== undefined){
     					
     					sanitized[key] = this._newFilter[key];
     				}
  				}
				return UserResource.getUsers(sanitized).then(function(r){
					factory._newFilter = factory._newFilter;
					factory.searching = false;
					userIndex._index = r.data;
					return r.data;
				});				
			},
			clearFilters: function() {
				var temp = this._filters;

				this._filters		   = [];
				this._filters['limit'] = temp.limit;
			},

			clearText: function() {
				this.text = '';
			},

			getResults: function(filter) {

				this.setFilterBy(null);

				UserResource.getUsers(filter).then(function(r) {
					userIndex._index = r.data;
					userIndex._next_page = null;
					factory.searching = false;
				});
			},

			setFilterBy: function(text) {
				this.searching = true;
				this._filteredBy = text;
			}

		}

		return factory;

	}


})();
