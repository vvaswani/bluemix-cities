This example application is built with PHP, Slim and Bootstrap. It requires a local or hosted MySQL service. 

To deploy this application to your local development host (for example, `localhost`):

 * Create an empty database in your local MySQL instance.
 * Clone the repository to the Web server document root on your local development host.
 * Run `composer update` to install all dependencies.
 * Create `config.php` with credentials for the local MySQL service. Use `config.php.sample` as an example.
 * Browse to `http://localhost/public/index.php/reset-db` to initialize the database tables.
 * Browse to `http://localhost/public/index.php` to access the application.
 
To deploy this application to your Bluemix space:

 * Instantiate a ClearDB MySQL service using the Bluemix console. 
 * Create an empty database in your ClearDB service.
 * Clone the repository to your local system.
 * Create `manifest.yml` and update it to use a custom hostname. Use `manifest.yml.sample` as an example.
 * Push the application to Bluemix with `cf push`.
 * Bind the ClearDB service to your application with `cf bind-service`.
 * Restage the application with `cf restage`.
 * Browse to `http://[hostname].mybluemix.net/reset-db` to initialize the database tables.
 * Browse to `http://[hostname].mybluemix.net` to access the application.
