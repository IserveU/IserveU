(function() {
	
	angular
		.module('iserveu')
		.directive('editMotionFiles', editMotionFiles);

	function editMotionFiles($stateParams, ToastMessage, motionfile) {

		function editMotionFilesController() {

			var vm =this;
			vm.show = {};

			vm.update = update;
			vm.edit = edit;
			vm.pressEnter = pressEnter;

			function edit (id) {
				for (var i in vm.show)
					vm.show[i][id] = false;
				vm.show[id] = !vm.show[id];
			};

			function update (title, id) {
				edit(id);
				console.log(title);

				motionfile.updateMotionFile({title:title}, $stateParams.id, id);
			};

			function pressEnter (ev, title, id) {
				if (ev.keyCode === 13)
					update(title, id);
			};

			function destroy (id) {
				ToastMessage.destroyThis("file", function(){
					motionfile.deleteMotionFile($stateParams.id, id);
				});
			};
		}


		return {
			controller: editMotionFilesController,
			controllerAs: 'editFile',
			templateUrl: 'app/components/motion/components/motion-files/edit-motion-files.tpl.html'
		}


	}

})();