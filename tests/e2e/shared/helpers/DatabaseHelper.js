function ConnectDatabase (){
	let mysql = require('../../../node_modules/mysql');

	this.connection = mysql.createConnection({
		host : process.env.DB_HOST,
		user : process.env.DB_USERNAME,
		password : process.env.DB_PASSWORD,
		database : process.env.DB_DATABASE
	});
};

module.exports = ConnectDatabase;