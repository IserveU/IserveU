(function() {
	
	angular
		.module('iserveu')
		.factory('motionSearchFactory', ['motion', 'motionObj', 'department', motionSearchFactory]);

     /** @ngInject */
     // TODO: needs documentation
	function motionSearchFactory(motion, motionObj, department) {

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

			_department: {
				index: department.self,
				filter: ''
			},

			_orderBy: {

				filters: [
				   {name: "Newest", query: {oldest: true}},
				   {name: "Oldest", query: {newest: true}},
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
				this._department.filter = '';
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

				if(angular.isNumber(this._department.filter)){
					filter['department_id'] = this._department.filter;
					this._filters.push(this._department.filter);
				}

				return motion.getMotions(filter).then(function(r){
					factory.newFilter = filter;
					factory.searching = false;
					return motionObj.data = r.data;
				});
			},

			queryDepartment: function(filter) {

				this.setFilterBy(filter.name);

				this._newFilter['department_id'] = filter.department_id;
				this.clearFilters();
				this._filters['department_id'] = this._newFilter['department_id'];

				return motion.getMotions(this._newFilter).then(function(r){
					factory._newFilter = factory._newFilter;
					factory.searching = false;
					return motionObj.data = r.data;
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

				motion.getMotions(filter).then(function(r) {
					motionObj.data = r.data;
					motionObj.next_page = null;
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

