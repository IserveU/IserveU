const { mix } = require('laravel-mix');


// 'node_modules/alloyeditor/dist/alloy-editor/alloy-editor-all.js',
mix.copy('node_modules/alloyeditor/dist/alloy-editor', 'public/alloyeditor', false); //The default icon set


mix.combine([
    'node_modules/angular/angular.js',
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/angular-animate/angular-animate.min.js',
    'node_modules/angular-material/angular-material.min.js',
    'node_modules/angular-messages/angular-messages.min.js',
    'node_modules/angular-resource/angular-resource.min.js',
    'node_modules/angular-sanitize/angular-sanitize.min.js',
    'node_modules/alloyeditor/dist/alloy-editor/alloy-editor-all.js',
    'node_modules/angular-alloyeditor/src/angular-alloyeditor.js',
    'node_modules/angular-aria/angular-aria.min.js',
    'node_modules/angular-cookies/angular-cookies.min.js',
    'node_modules/angular-loading-bar/build/loading-bar.min.js',
    'node_modules/marked/marked.min.js',

    'node_modules/angular-translate/dist/angular-translate.min.js',
    'node_modules/angular-translate/dist/angular-translate-storage-local/angular-translate-storage-local.min.js',
    'node_modules/angular-translate/dist/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
    'node_modules/angular-ui-router/release/angular-ui-router.min.js',
    'node_modules/ng-infinite-scroll/build/ng-infinite-scroll.min.js',
    'node_modules/tinycolor2/dist/tinycolor-min.js',
    'node_modules/md-color-picker/dist/mdColorPicker.min.js',
    'node_modules/ng-flow/dist/ng-flow-standalone.min.js',
     'node_modules/satellizer/dist/satellizer.min.js', //depecrated, maybe
     'node_modules/angular-marked/dist/angular-marked.min.js'

  ],'public/js/dependencies.js'); // added resources as third argument to point directly to the resources directory and not have /js in there

mix.js([
  'public/app/app.js',
  'public/app/api/authResource.svc.js',
  'public/app/api/commentResource.svc.js',
  'public/app/api/commentVoteResource.svc.js',
  'public/app/api/communityResource.svc.js',
  'public/app/api/ethnicOriginResource.svc.js',
  'public/app/api/fileResource.svc.js',
  'public/app/api/homeResource.svc.js',
  'public/app/api/motionDepartmentResource.svc.js',
  'public/app/api/motionFileResource.svc.js',
  'public/app/api/motionResource.svc.js',
  'public/app/api/settingResource.svc.js',
  'public/app/api/userPreferenceResource.svc.js',
  'public/app/api/userResource.svc.js',
  'public/app/api/userRoleResource.svc.js',
  'public/app/api/voteResource.svc.js',
  'public/app/components/admin.dash/appearance/appearance.dir.js',
  'public/app/components/admin.dash/content/content-manager.dir.js',
  'public/app/components/admin.dash/content/content-manger.svc.js',
  'public/app/components/admin.dash/content/themePicker/Palette.js',
  'public/app/components/admin.dash/content/themePicker/themePicker.dir.js',
  'public/app/components/admin.dash/department/department-manager.dir.js',
  'public/app/components/admin.dash/department/department-manager.svc.js',
  'public/app/components/admin.dash/drafts/motion-drafts.dir.js',
  'public/app/components/admin.dash/email/email.dir.js',
  'public/app/components/admin.dash/pages/page-manager.dir.js',
  'public/app/components/admin.dash/system/system-manager.dir.js',
  'public/app/components/commentList/commentList.js',
  'public/app/components/commentList_abstain/commentList_abstain.js',
  'public/app/components/commentList_agree/commentList_agree.js',
  'public/app/components/commentList_disagree/commentList_disagree.js',
  'public/app/components/commentVote/commentVote.js',
  'public/app/components/crowdVerification/crowdVerification.js',
  'public/app/components/emailVote/emailVote.js',
  'public/app/components/fileUploader/fileUploader.dir.js',
  'public/app/components/footer/footer.dir.js',
  'public/app/components/home/homePage.js',
  'public/app/components/home/widgets/homeIntroduction.js',
  'public/app/components/home/widgets/myComments.js',
  'public/app/components/home/widgets/myVotes.js',
  'public/app/components/home/widgets/topComments.js',
  'public/app/components/home/widgets/topMotions.js',
  'public/app/components/login/login.dir.js',
  'public/app/components/login/login.svc.js',
  'public/app/components/motion/motion.js',
  'public/app/components/motion/motionDepartments.svc.js',
  'public/app/components/motion/motionIndex.svc.js',
  'public/app/components/motionFiles/motion-files.svc.js',
  'public/app/components/motionFiles/motionFiles.js',
  'public/app/components/motionForm/motionForm.js',
  'public/app/components/motionSearchbar/motionSearchbar.js',
  'public/app/components/motionSearchbar/motionSearchbar.svc.js',
  'public/app/components/motionSections/motionSections.js',
  'public/app/components/motionSidebar/motionSidebar.js',
  'public/app/components/motionSidebar/quickVote.js',
  'public/app/components/motionTiles/motionTiles.js',
  'public/app/components/motionVoteButtons/motionVoteButtons.js',
  'public/app/components/motionVoteButtons/motionVoteButtons.svc.js',
  'public/app/components/motionVotes/motionVotes.js',
  'public/app/components/motionVoteStatusbar/motionVoteStatusbar.js',
  'public/app/components/motionVoteStatusbar/motionVoteStatusbar.svc.js',
  'public/app/components/motionVoteUrl/emailVote.js',
  'public/app/components/navigation/betaMessage.dir.js',
  'public/app/components/navigation/sidebar.dir.js',
  'public/app/components/navigation/userbar.dir.js',
  'public/app/components/navigation/userbar.svc.js',
  'public/app/components/notification/incompleteProfile.svc.js',
  'public/app/components/notification/notification.dir.js',
  'public/app/components/notification/notification.svc.js',
  'public/app/components/pages/create-page.dir.js',
  'public/app/components/pages/edit-page.dir.js',
  'public/app/components/pages/pages.dir.js',
  'public/app/components/pages/pages.svc.js',
  'public/app/components/password/resetpassword.dir.js',
  'public/app/components/password/resetpassword.svc.js',
  'public/app/components/register/register.dir.js',
  'public/app/components/sidebar/sidebar.js',
  'public/app/components/sidebar/sidebarSearch.js',
  'public/app/components/termsAndConditions/termsAndConditions.dir.js',
  'public/app/components/user/displayUser/displayUser.dir.js',
  'public/app/components/user/displayUser/toolbar.dir.js',
  'public/app/components/user/displayUser/toolbar.svc.js',
  'public/app/components/user/editUser/editUser.dir.js',
  'public/app/components/user/editUser/editUserForm.svc.js',
  'public/app/components/user/editUser/fieldTemplates/editUserFieldTemplates.dir.js',
  'public/app/components/user/userIndex.svc.js',
  'public/app/components/user/userPreferenceFactory.svc.js',
  'public/app/components/user/userRoleFactory.svc.js',
  'public/app/components/userComment/userComment.js',
  'public/app/components/userCommentCreate/userCommentCreate.js',
  'public/app/components/userCommentEdit/userCommentEdit.js',
  'public/app/components/users.dash/user-manager.dir.js',
  'public/app/components/userSidebar/search/userSearchbar.js',
  'public/app/components/userSidebar/search/userSearchbar.svc.js',
  'public/app/components/userSidebar/userSidebar.js',
  'public/app/components/usersMotions/usersMotions.dir.js',
  'public/app/deprecated/isu-form-sections.js',
  'public/app/models/commentModel.svc.js',
  'public/app/models/commentVoteModel.svc.js',
  'public/app/models/motionCommentsModel.svc.js',
  'public/app/models/motionFilesModel.js',
  'public/app/models/motionsModel.svc.js',
  'public/app/models/motionVotesModel.svc.js',
  'public/app/models/voteModel.svc.js',
  'public/app/routes.js',
  'public/app/shared/authorizer/authorizer.svc.js',
  'public/app/shared/authorizer/hasPermission.dir.js',
  'public/app/shared/config/httpProvider.conf.js',
  'public/app/shared/config/loadingBarProvider.conf.js',
  'public/app/shared/config/marked.conf.js',
  'public/app/shared/config/mdIconProvider.conf.js',
  'public/app/shared/config/mdThemingProvider.conf.js',
  'public/app/shared/config/ngInfiniteScroll.conf.js',
  'public/app/shared/config/ngTranslate.conf.js',
  'public/app/shared/config/urlProvider.conf.js',
  'public/app/shared/directives/accordian/accordian.dir.js',
  'public/app/shared/directives/alloy/alloy.js',
  'public/app/shared/directives/alloy/alloy.svc.js',
  'public/app/shared/directives/alloy/regex.svc.js',
  'public/app/shared/directives/floatingButton/floatingButton.dir.js',
  'public/app/shared/directives/floatingButton/floatingButton.svc.js',
  'public/app/shared/directives/logoProvider/logoProvider.dir.js',
  'public/app/shared/directives/spinner.dir.js',
  'public/app/shared/filters.js',
  'public/app/shared/providers/globalProvider.js',
  'public/app/shared/services/debounce.svc.js',
  'public/app/shared/services/formFormatters/fileId.dir.js',
  'public/app/shared/services/formFormatters/fileService.svc.js',
  'public/app/shared/services/formFormatters/focusInput.dir.js',
  'public/app/shared/services/formFormatters/formataddress.dir.js',
  'public/app/shared/services/formFormatters/formatBirthday.dir.js',
  'public/app/shared/services/formFormatters/formatClosing.dir.js',
  'public/app/shared/services/formFormatters/formatCommunity.dir.js',
  'public/app/shared/services/formFormatters/formatDate.dir.js',
  'public/app/shared/services/formFormatters/formatHttp.dir.js',
  'public/app/shared/services/formFormatters/formatNumber.dir.js',
  'public/app/shared/services/formFormatters/formatPublic.dir.js',
  'public/app/shared/services/formFormatters/inputmatch.dir.js',
  'public/app/shared/services/formFormatters/pressEnter.dir.js',
  'public/app/shared/services/mdtoastmessage.svc.js',
  'public/app/shared/services/onStateChange/redirect.svc.js',
  'public/app/shared/services/localstoragemanager.svc.js',
  'public/app/shared/services/settings.svc.js',
  'public/app/shared/services/utils.svc.js',
  'public/app/shared/settings/settingsUtils.svc.js'
],'public/js/app.js');

mix.styles([
    'node_modules/angular-material/angular-material.min.css',
    'node_modules/mdi/css/materialdesignicons.min.css',
    'node_modules/md-color-picker/dist/mdColorPicker.min.css',
    'node_modules/angular-loading-bar/build/loading-bar.css'
],'public/css/dependencies.css');

mix.sass('resources/assets/sass/style.scss','public/css/app.css');

mix.copy('node_modules/mdi/fonts', 'public/fonts'); //The default icon set
mix.copy('node_modules/alloyeditor/dist/alloy-editor/assets/fonts', 'public/build/css/fonts');

//Copy the standard icons (set in the department table)
mix.copy('resources/assets/icons', 'public/icons');

//Copy the glyph fonts and symbols for the UI
mix.copy('resources/assets/symbols', 'public/symbols');

mix.copy('resources/assets/maintenance.jpg', 'public');

mix.version();


var LiveReloadPlugin = require('webpack-livereload-plugin');

mix.webpackConfig({
    plugins: [
        new LiveReloadPlugin()
    ]
});

