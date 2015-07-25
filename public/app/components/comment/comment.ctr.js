    (function() {

    angular
        .module('iserveu')
        .controller('CommentController', CommentController);

    function CommentController($rootScope, $scope, $stateParams, motion, comment, vote, MotionService, $mdToast, $state, UserbarService) {

        var vm = this;
        vm.commenttext;
        vm.editComment = false;
        vm.thisUsersComment = MotionService.thisUsersComment;

        vm.editCommentFunction = function(){
            vm.editComment = !vm.editComment;
        }

        vm.submitComment = function(text) {

            var data = {
                vote_id: MotionService.userVoteId,
                text: text
            }

            comment.saveComment(data).then(function(result) {
                vm.commenttext = '';
                $rootScope.$emit('getMotionComments');
                 $mdToast.show(
                  $mdToast.simple()
                    .content("You've made a comment!")
                    .position('bottom right')
                    .hideDelay(3000)
                );
              
                
            }, function(error) {
                console.log(error);
            });            
        }

        vm.updateComment = function(text) {
            
            var data = {
                id: vm.thisUsersComment.id,
                text: text
            }
            comment.updateComment(data).then(function(result) {
                $rootScope.$emit('getMotionComments');
                $mdToast.show(
                  $mdToast.simple()
                    .content("Commented updated")
                    .position('bottom right')
                    .hideDelay(3000)
                );
            }, function(error) {

            });
        }

        vm.deleteComment = function() {   
           var data = {
                id: MotionService.thisUsersComment.id,
                text: MotionService.thisUsersComment.text
            }
            comment.deleteComment(data.id).then(function(result) {
                $rootScope.$emit('getMotionComments');

                $mdToast.show(
                  toast = $mdToast.simple()
                    .content("Commented deleted")
                    .action('Undo?')
                    .highlightAction(false)
                    .position('bottom right')
                    .hideDelay(8000)
                );

                $mdToast.show(toast).then(function() {
                    comment.updateComment(data).then(function(result) {
                    $rootScope.$emit('getMotionComments');
                     $mdToast.show(
                     toast = $mdToast.simple()
                        .content("Your comment is back!")
                        .position('bottom right')
                        .hideDelay(3000)
                    );
                    }); 
                    
                });
            }, function(error) {
                
            });           
        }

    }

}());