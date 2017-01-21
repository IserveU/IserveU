(function() {

	angular
		.module('iserveu')
		.factory('motionSearchFactory', ['motionResource', 'motionIndex', 'motionDepartments', motionSearchFactory]);
     // TODO: needs documentation
	function motionSearchFactory(motion, motionIndex, motionDepartments) {

		motionDepartments.loadAll();
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

			_department: motionDepartments,
			_status: {
				filters: [
				   {name: "Published", query: {'status': ["published",""]}},
				   {name: "Closed", query: {'status': ["","closed"]}},

				],

				filter: ''

			},
			_orderBy: {

				filters: [
				   {name: "Newest", query: {'published': 'desc'}},
				   {name: "Oldest", query: {'published': 'asc'}},
				   {name: "Closing Soon", query: {'descClosing':'desc'}}
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
					'allTextFields': this.text // TODO alter function to take paramaters from searchbar input
				};
				motion.getMotions(data).then(function (result) {
		          motionIndex._index = result.data;
		          motionIndex._next_page = null;
		          factory.searching = false;
		        });
			},
			all: function() {
				this._department.filter = '';
				this._orderBy.filter 	= '';
				this._status.filter = '';
				this.clearFilters();
				this.getResults(this._filters);
			},
			searchSpecific: function() {
				
				this._newFilter['status[]'] = this._status.filter.status;
 				this._newFilter['department_id'] = this._department.filter;
 				this._newFilter['orderBy[closing_at]'] = this._orderBy.filter.descClosing;
 				this._newFilter['orderBy[published_at]'] = this._orderBy.filter.published;
 				//sanitize the data in case the value is empty/ user has not chosen the filter.

 				var sanitized = {}; 
  				for (var key in this._newFilter) {
   					if (!!this._newFilter[key])
     				sanitized[key] = this._newFilter[key];
  				}
				return motion.getMotions(sanitized).then(function(r){
					factory._newFilter = factory._newFilter;
					factory.searching = false;
					motionIndex._index = r.data;
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

				motion.getMotions(data).then(function (result) {
					motionIndex._index = result.data;
					motionIndex._next_page = null;
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

