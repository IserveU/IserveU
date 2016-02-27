(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('userToolbarService', userToolbarService);

	function userToolbarService($state, $timeout, editUserFactory) {

		this.state = '';
		this.edit = editUserFactory;
		this.save = save;
		this.editField = editField;
		this.pressEnter = pressEnter;

		function save(data) {

			var user = editUserFactory.map(''), j, i;

			for ( i in data )
			for ( j in user )
				if( i === j && isOfTypeName(i) ) 
				user[j] = data[i];

			editUserFactory.save('last_name', user);
		};

		function isOfTypeName(_str) {
			var l = _str.length;
			return _str.substr( l - 4, l ) === 'name';
		};

		function editField() {
			if($state.current.name === 'edit-user')
				this.showInputField = true;
		};

		function pressEnter(ev, data) {
			if( ev.keyCode === 13 )
				save(data);
		};

	}


})();