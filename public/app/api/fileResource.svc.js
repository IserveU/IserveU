(function() {

    'use strict';

    angular
        .module('iserveu')
        .factory('fileResource', ['$resource', '$q', fileResource]);

     /** @ngInject */
    function fileResource($resource, $q) {

        // TODO: allow other parent files
        var File = $resource('api/motion/{motion_id}/file/{file_id}', {}, {
            'update': {
                method:'PUT',
                ignoreLoadingBar: true
            }
        });

        function getFile(data){
            return File.get({motion_id: data.motion_id, file_id: data.file_id}).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        function saveFile(id){
            return File.save({motion_id: data.motion_id}, data).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }


        function updateFile(data) {
            return File.update({motion_id: data.motion_id, file_id: data.file_id}, data).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        function deleteFile(data) {
            return File.delete({motion_id: data.motion_id, file_id: data.file_id}).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        return {
            getFile: getFile,
            saveFile: saveFile,
            updateFile: updateFile,
            deleteFile: deleteFile
        };

    }

}());