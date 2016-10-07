(function() {

    'use strict';

    angular
        .module('iserveu')
        .factory('fileResource', ['$resource', '$q', fileResource]);

     /** @ngInject */
    function fileResource($resource, $q) {

        // TODO: allow other parent files
        var File = $resource('api/motion/:id/file/:file_slug', {}, {
            'update': {
                method:'PUT',
                ignoreLoadingBar: true
            }
        });

        function getFiles(id){
            return File.query({id: id}).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        function getFile(data){
            return File.get({id: data.id, file_slug: data.file_slug}).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        function saveFile(id){
            return File.save({id: data.id}, data).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }


        function updateFile(data) {
            return File.update({id: data.id, file_slug: data.file_slug}, data).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        function deleteFile(data) {
            return File.delete({id: data.id, file_slug: data.file_slug}).$promise.then(function(success){
                return success;
            }, function(error){
                return $q.reject(error);
            });
        }

        return {
            getFiles: getFiles,
            getFile: getFile,
            saveFile: saveFile,
            updateFile: updateFile,
            deleteFile: deleteFile
        };

    }

}());