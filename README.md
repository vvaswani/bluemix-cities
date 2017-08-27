This example application is built with PHP, Slim and Bootstrap. It requires a local or hosted MySQL service. 

To deploy this application to your local development host (for example, `localhost`):

 * Create an empty database in your local MySQL database.
 * Clone the repository to the Web server document root on your local development host.
 * Run `composer update` to install all dependencies.
 * Create `config.php` with credentials for the local MySQL service. Use `config.php.sample` as an example.
 * Browse to `http://localhost/[path]/public/index.php`.
 
To deploy this application to your Bluemix space:

 * Clone the repository to your local system.
 * Run `composer update` to install all dependencies.
 * Create `config.php` with credentials for the various services. Use `config.php.sample` as an example.
 * Update `manifest.yml` with your custom hostname.
 * Push the application to Bluemix with `cf push`.
 * Instantiate a ClearDB MySQL service using the Bluemix console. 
 * Create an empty database in your ClearDB service.
 * Bind the ClearDB service to your application with `cf bind-service`.
 * Restage the application with `cf restage`.
 * Browse to `http://[hostname].mybluemix.net/index.php`.
 
