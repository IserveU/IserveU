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

			_orderBy: {

				filters: [
				   {name: "Newest", query: {'oldest': true}},
				   {name: "Oldest", query: {'newest': true}},
				   {name: "Closed", query: {'orderBy.closing_at':'desc'}}
				],

				filter: ''
			},

			_newFilter: [],

			_filputeredBy: '',

			show: function() {
				if(this.isOpen)
					this.text = '';
				this.isOpen = !this.isOpen;
			},
			//normal casual text search 
			searchAll: function() {
				var data = {
					'allTextFields': this.text // TODO alter function to take paramaters from searchbar input
				};
				motion.getMotions(data)
					.then(result => {
					motionIndex._index = result.data;
					motionIndex._next_page = null;
					factory.searching = false;
					})
			},
			// search according to filter choice
			searchSpecific: function() {

			},.
			all: function() {
				this._department.filter = '';
				this._orderBy.filter 	= '';
				this.clearFilters();
				this.getResults(this._filters);
			},
			query: function(filter) {

				var temp = Object.getOwnPropertyNames(filter);
				console.log(temp);
				temp.pop();			//removes $mdSelect event thats bundled with var filter

				this.clearFilters();
				this.setFilterBy(temp[0]);

				angular.forEach(temp, function(f, key){
					factory._filters[f] = true;
				});

				if(angular.isNumber(this._department.filter)){
					filter['department_id'] = this._department.filter;
					this._filters.push(this._department.filter);
				}

				return motion.getMotions(filter).then(function(r){
					factory.newFilter = filter;
					factory.searching = false;
					motionIndex.data = r.data;
					return r.data;
				});
			},

			queryDepartment: function(filter) {

				this.setFilterBy(filter.name);

				this._newFilter['departmentId'] = filter.department_id;
				this.clearFilters();
				this._filters['departmentId'] = this._newFilter['departmentId'];

				return motion.getMotions(this._newFilter).then(function(r){
					factory._newFilter = factory._newFilter;
					factory.searching = false;
					motionIndex._index = r.data
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

				motion.getMotions(filter).then(result => {
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

