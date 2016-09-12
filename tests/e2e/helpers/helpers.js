var Helper = function() {

	this.hasClass = function hasClass(element, cls) {
	    return element.getAttribute('class').then(function (classes) {
	        return classes.split(' ').indexOf(cls) !== -1;
	    });
	};

	/**
	 * @name waitForUrlToChangeTo
	 * @description Wait until the URL changes to match a provided regex
	 * @param {RegExp} urlRegex wait until the URL changes to match this regex
	 * @returns {!webdriver.promise.Promise} Promise
	 */
	this.waitForUrlToChangeTo = function waitForUrlToChangeTo(urlRegex) {
	    var currentUrl;

	    return browser.getCurrentUrl().then(function storeCurrentUrl(url) {
	            currentUrl = url;
	        }
	    ).then(function waitForUrlToChangeTo() {
	            return browser.wait(function waitForUrlToChangeTo() {
	                return browser.getCurrentUrl().then(function compareCurrentUrl(url) {
	                    return urlRegex.test(url);
	                });
	            });
	        }
	    );
	}

}


module.exports = Helper;