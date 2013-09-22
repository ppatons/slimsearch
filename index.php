<?php

require 'vendor/autoload.php';

// load required files RedBean
require_once('vendor/RedBean/rb.php');

// register Slim auto-loader
\Slim\Slim::registerAutoloader();

// set up database connection
R::setup('mysql:host=localhost;dbname=slimsearch','root','');
// R::setup('mysql:host=localhost; dbname=mydatabase','user','password');
R::freeze(true);

// initialize app
$app = new \Slim\Slim();

// handle GET requests for /people
$app->get('/people', function () use ($app) {
	// query database for all people
	$people = R::find('people');

	// send response header for JSON content type
	$app->response()->header('Content-Type', 'application/json');

	// return JSON-encoded response body with query results
	echo json_encode(R::exportAll($people));
});

// run
$app->run();