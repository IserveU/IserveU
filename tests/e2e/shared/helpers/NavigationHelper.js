var NavigationHelper = function() {

	/**
	 * @name waitForUrlToChangeTo
	 * @description Wait until the URL changes to match a provided regex
	 * @param {RegExp} urlRegex wait until the URL changes to match this regex
	 * @returns {!webdriver.promise.Promise} Promise
	 */
	this.waitForUrlToChangeTo = function waitForUrlToChangeTo(url) {
	    //
	    // EC being the url. But on timeout it should error out saying that the url was not there
	}

}


module.exports = NavigationHelper;