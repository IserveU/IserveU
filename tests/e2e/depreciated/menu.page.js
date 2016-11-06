var Menu = function() {

	//TODO
	// - page navigation.css with repeater
	// - toggle sidebars

	this.dropdown = element(by.css('div.md-toolbar-item > md-menu > button[ng-click="$mdOpenMenu()"]'));
	this.dashboardButton =  element(by.className('menu__button-dashboard'));
	this.createMotionButton = element(by.className('menu__button-create-motion'));
	this.myMotionsButton = element(by.className('menu__button-my-motions'));
	this.profileButton = element(by.className('menu__button-profile'));
	this.logoutButton = element(by.className('menu__button-logout'));
	this.loginButton = element(by.className('menu__button-login'));

	this.pageList = element.all(by.repeater('page in user.pageObj.index'));
	this.page = element(by.binding('page.title')); // does this work

};

module.exports = Menu;