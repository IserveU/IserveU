(function () {
  'use strict'

angular
	.module('app.motions')
	.factory('MotionDepartments',
  ['$http',
		 'motionDepartmentResource',

    function ($http, motionDepartmentResource) {
  var motionDepartment = {
  index: [],
  filter: '',
  loadAll: function () {
  var self = this

			if (self.index.length > 0) {
  return false
			}

			// TODO: make a get sanitizer that strips promise and such with method below
  motionDepartmentResource.getDepartments().then(function (success) {
  self.index = success.data
			})
		}
}

  return motionDepartment

}])
})()
