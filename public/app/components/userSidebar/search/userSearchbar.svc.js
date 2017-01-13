(function() {

	angular
		.module('iserveu')
		.factory('userSearchFactory', ['userIndex', userSearchFactory]);

     // TODO: needs documentation
	function userSearchFactory(user, userIndex) {

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


			_orderBy: {

				filters: [
				   {name: "Newest", query: {oldest: true}},
				   {name: "Oldest", query: {newest: true}}
				   // {name: "Closed", query: {is_expired:true}}
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

			all: function() {
				this._orderBy.filter 	= '';
				this.clearFilters();
				this.getResults(this._filters);
			},

			query: function(filter) {

				var temp = Object.getOwnPropertyNames(filter);
				temp.pop();			//removes $mdSelect event thats bundled with var filter

				this.clearFilters();
				this.setFilterBy(temp[0]);

				angular.forEach(temp, function(f, key){
					factory._filters[f] = true;
				});

				return user.getUsers(filter).then(function(r){
					factory.newFilter = filter;
					factory.searching = false;
					userIndex.data = r.data;
					return r.data;
				});
			},

			clearFilters: function() {
				var temp = this._filters;

				this._filters		   = [];
				this._filters['take']  = temp.take;
				this._filters['limit'] = temp.limit;
				this._filters['next_page']  = temp.next_page;
			},

			clearText: function() {
				this.text = '';
			},

			getResults: function(filter) {

				this.setFilterBy(null);

				user.getUsers(filter).then(function(r) {
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
