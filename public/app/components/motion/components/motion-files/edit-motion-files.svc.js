(function() {
	
	angular
		.module('iserveu')
		.factory('editMotionFilesFactory', editMotionFilesFactory);

	function editMotionFilesFactory($stateParams, motionfile, ToastMessage) {

		var factory = {
			show: {},
			saving: {},
			success: {},
			edit: function(id) {
				for (var i in this.show)
					this.show[i][id] = false;
				this.show[id] = !this.show[id];
			},
			update: function(title, id) {
				this.edit(id);
				motionfile.updateMotionFile(
					{title:title}, $stateParams.id, id
				);
			},
			pressEnter: function(ev, title, id) {
				if (ev.keyCode === 13)
					this.update(title, id);
			},
			destroy: function() {
				ToastMessage.destroyThis("file", function(){
					motionfile.deleteMotionFile($stateParams.id, id);
				});
			}
		}

		return factory;
	}


})();

