var MotionFormPage = function() {

	this.randomMotionTitle = Math.random().toString(36);
	this.randomMotionDetails = Math.random().toString(36);

	this.titleInput = element(by.model('form.motion.title'));
	this.detailInput = element(by.model('form.motion.text'));

	this.departmentSelectButton = element.all(by.css('md-select[name="department"]'));
	this.departmentList = element.all(by.repeater('d in form.departments.index'));
	this.closingDate = element.all(by.model('form.motion.closing'));

	this.statusSelect = element.all(by.css('md-select[name="status"]'));

	this.statusList = {
		draft: element(by.className('select-option__draft')),
		review: element(by.className('select-option__review')),
		publish: element(by.className('select-option__publish'))
	};

	this.saveButton = element(by.css('spinner.create-motion__button > button[type="submit"]'));


	this.fillBasic = function(status) {
	  	this.titleInput.sendKeys(this.randomMotionTitle);
  	// details.sendKeys(randomMotionDetails); not working
	  	this.departmentSelectButton.click();
	    this.departmentList.get(2).click(); // TODO choose a random department
	};

	// TODO
	this.fillMotionFiles = function() {

	}

	// TODO
	this.fillSections = function() {

	}

	this.setStatus = function(status) {
		this.statusSelect.click();
		this.statusList[status].click();
	}

};

module.exports = MotionFormPage;