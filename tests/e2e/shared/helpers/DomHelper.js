var DomHelper = function() {

	this.hasClass = function hasClass(element, cls) {
	    return element.getAttribute('class').then(function (classes) {
	        return classes.split(' ').indexOf(cls) !== -1;
	    });
	};


}


module.exports = DomHelper;