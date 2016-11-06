var MotionPage = function() {

	this.motionTitle = element.all(by.css('header > h1[class="md-display-2 motion__title"]'));
	this.detail = element(by.model('motion.text'));

	// TODO 
	// - motiontiles
	// - motionfiles
	// - sections

	this.motionFiles =  element.all(by.repeater('file in motion.motionFiles'));

}


module.exports = MotionPage;
