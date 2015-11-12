# IserveU
eDemocracy Tool

Run: "bower install" to get dependencies
Run: "gulp watch" while working to continue compiling assets http://laravel.com/docs/5.0/elixir

If you wish to add new dependencies, add them to both bower.json and gulpfile.js

Remember to run "bower install" fairly regularly, especially if things are rendering strangely. Many of these libraries are being updated all the time and we're adding in new things.

New files inside resources/css and public/app are automatically compiled

##Error Handling

Laravel can return error codes with specific error messages when a request to the API is deemed unsuccessful. We will generally want either 401 (not authenticated), 403 (access denied), or 500 (something went wrong).

In the Laravel controllers, if an error should be thrown, use Laravel's `abort` method. Example:

<pre><code>
if(Auth::check()) {
	return $data;
}
else {
	abort(401, 'You are not logged in!');
}
</code></pre>

More [here](http://laravel.com/docs/5.0/errors#http-exceptions) and [here](http://laravel-recipes.com/recipes/202/throwing-httpexceptions)

##Beginner's Guide to Installing/Setting up Laravel on Homestead on a Mac

Install Laravel [Homestead](http://laravel.com/docs/5.0/homestead) or if you have a linux machine go through the [install requirements](http://laravel.com/docs/5.1) for laravel.

Follow doc instructions and once you have your VM setup ssh into your machine (vagrant ssh), get into your root directory and run:

<pre><code>
$ composer install
$ npm install --global gulp
$ npm install
$ bower install
$ npm install node-sass
$ npm install gulp-sass
</code></pre>

Now run <pre><code>$ gulp</code></pre> to make sure you've install everything correctly. It should run pretty well. If you get an error that asks you to "Try reinstalling `node-sass`"
go into your command line and enter in:

<pre><code>rm -r node_modules/laravel-elixir/node_modules/gulp-sass</code></pre>

Now make sure you've added the IserveU database into your homestead.yaml, you can make sure that it's there by typing this into your command line (while ssh'd onto vagrant):

<pre><code>
	$ mysql -uhomestead -p
	$ secret		// homestead's db password
</code></pre>

You'll be entered in the mysql command line you can enter in "show databases;" to see that you're there.

<pre><code>
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| homestead          |
| iserveu            |
| mysql              |
| performance_schema |
+--------------------+
5 rows in set (0.01 sec)
</code></pre>

You can connect to the db through any interface, I've used [MySQLWorkbench](https://www.mysql.com/products/workbench/) and the config for that is as follows: 

SSH Hostname: 127.0.0.1:2222  
SSH Username: vagrant  
SSH Password: vagrant  
SSH Key File: /Users/yourname/.vagrant.d/insecure_private_key  
MySQL Hostname: 127.0.0.1  
MySQL Server Port: 3306  
Username: homestead  
Password: secret

After you've connected, go into Users and Privileges (under Server drop-down menu) and create a user that you've declared in your Homestead.yaml. In my case I used iserveu_user, and also used a select password for that (these are the same as in your .env of the project folder). Give this user all privileges. Then just seed the database.

<pre><code>
$ php artisan migrate
$ php artisan db:seed
</code></pre>

You should be able to connect to your localhost and access IserveU. 

##The API

/api/resource/ 			INDEX 	(get)
Will return an index of the resource, if you are logged in it will return an indext that applies to you (your comments, your votes, motions with your votes)

/api/resource/create	CREATE 	(get)
Will check you can create a resource and returns the fields that you can submit in a store method, pointing out the value types expected and if they are required

/api/resource/store		STORE 	(post)
Does the initial store of a resource, returns the resource on success or will have an error if validation or permission failed

/api/resource/X			SHOW 	(get)
Will fetch this resource, if you are logged in it will get all the data you have permission for on this resouce (to populate the edit form/review)

/api/resource/X/edit 	EDIT 	(get)
Will check you can edit this particular resource and returns the fields that you can submit in the update method, pointing out the value types expected, if they are required and if they are locked (locked fields can't be changed once being set)

/api/resource/X/	 	UPDATE 	(update)
Does the store of the resource, takes updates with even just one field each time.

/api/resource/X			DESTROY	(delete)
Deletes the record, sometimes soft deletes but if you run it twice it will delete it as far as it is able to


#License

GNU General Public License: http://www.gnu.org/licenses/gpl.html
