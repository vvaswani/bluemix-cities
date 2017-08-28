<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// autoload files
require '../vendor/autoload.php';

// if BlueMix VCAP_SERVICES environment available
// overwrite local credentials with BlueMix credentials
if ($services = getenv("VCAP_SERVICES")) {
  $config = [];
  $services_json = json_decode($services, true);
  $config['settings']['db']['hostname'] = $services_json['cleardb'][0]['credentials']['hostname'];
  $config['settings']['db']['username'] = $services_json['cleardb'][0]['credentials']['username'];
  $config['settings']['db']['password'] = $services_json['cleardb'][0]['credentials']['password'];
  $config['settings']['db']['name'] = $services_json['cleardb'][0]['credentials']['name'];
} else {
  require '../config.php';
}

// configure Slim application instance
// initialize application
$app = new \Slim\App($config);

// initialize dependency injection container
$container = $app->getContainer();

// configure view renderer in DI container
$container['view'] = function ($container) {
  return new \Slim\Views\PhpRenderer("../views/");
};

// configure MySQL client in DI container
$container['db'] = function ($container) {
  $config = $container->get('settings');
  return new mysqli(
    $config['db']['hostname'], 
    $config['db']['username'], 
    $config['db']['password'], 
    $config['db']['name']
  );
};

// index page controller
$app->get('/', function (Request $request, Response $response) {
  $result = $this->db->query("SELECT * FROM cities");
  $data = $result->fetch_all(MYSQLI_ASSOC);
  return $this->view->render($response, 'index.phtml', [
    'router' => $this->router, 
    'cities' => $data
  ]);
})->setName('index');

// record creation controller
$app->post('/save', function (Request $request, Response $response) {
  $params = $request->getParams();
  $city = filter_var($params['city'], FILTER_SANITIZE_STRING);
  if (!$this->db->query("INSERT INTO cities (name) VALUES ('$city')")) {
    throw new Exception('Failed to save record: ' . $this->db->error);
  }
  return $response->withHeader('Location', $this->router->pathFor('index'));
})->setName('save');

// record deletion controller
$app->get('/delete/{id}', function (Request $request, Response $response, $args) {
  $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
  if (!$this->db->query("DELETE FROM cities WHERE id = '$id'")) {
    throw new Exception('Failed to delete record.');
  }
  return $response->withHeader('Location', $this->router->pathFor('index'));
})->setName('delete');

// database reset
// only for development, remove from production code
$app->get('/reset-db', function (Request $request, Response $response) {
  if (!$this->db->query("DROP TABLE IF EXISTS cities")) {
    throw new Exception('Failed to drop table: ' . $this->db->error);
  } 
  if (!$this->db->query("CREATE TABLE cities ( id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL )")) {
    throw new Exception('Failed to create table: ' . $this->db->error);  
  }
  return $response->write('Database successfully reset!');
});

// PHP runtime info
// only for debugging, remove from production code
$app->get('/info', function (Request $request, Response $response) {
  phpinfo();
});

$app->run();
