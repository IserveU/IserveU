var path = require('path');

var Utils = function () {

    this.commonElements = {
        logoutButton: function () { return element(by.partialButtonText('Logout')); },
        loginButton: function () { return element(by.partialButtonText('Login')); },
    }

    this.doLogin = function (email, password) {

    }

    this.doLogout = function () {

    }



}

module.exports = Utils;
