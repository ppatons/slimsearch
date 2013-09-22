<?php

require 'vendor/autoload.php';

// load required files RedBean
require_once('vendor/RedBean/rb.php');

// set up database connection
R::setup('mysql:host=localhost;dbname=slimsearch','root','');
// R::setup('mysql:host=localhost; dbname=mydatabase','user','password');
R::freeze(true);

// initialize app
$app = new \Slim\Slim(array(
		'cookies.secret_key' => 'secret_key',
));

// set default conditions for route parameters
\Slim\Route::setDefaultConditions(array(
  'id' => '[0-9]{1,}',
));

# read and/or write JSON from/to file
// jSON URL which should be requested
// $json_url = 'http://localhost/slimsearch/vendor/json/people.json';

$file = "vendor/json/people.json";
$json = json_decode(file_get_contents($file), true);

file_put_contents($file, json_encode($json));

//@TODO - add class ResourceNotFoundException

// @TODO -  route for simple API authentication

// handle GET requests for /people
$app->get('/people', 'authenticate', function () use ($app) {    
  try {
    $people = R::find('people'); 
    $mediaType = $app->request()->getMediaType();
    if ($mediaType == 'application/xml') {
      $app->response()->header('Content-Type', 'application/xml');
      $xml = new SimpleXMLElement('<root/>');
      $result = R::exportAll($people);
      foreach ($result as $r) {
        $item = $xml->addChild('item');
        		$item->addChild('guid', $r['guid']);
				$item->addChild('picture', $r['picture']);
				$item->addChild('age', $r['age']);
				$item->addChild('name', $r['name']);
				$item->addChild('gender', $r['gender']);
				$item->addChild('company', $r['company']);
				$item->addChild('phone', $r['phone']);
				$item->addChild('email', $r['email']);
				$item->addChild('address', $r['adress']);
				$item->addChild('about', $r['about']);
				$item->addChild('registered', $r['registered']);
				$item->addChild('tags', $r['tags']);
				$item->addChild('friends', $r['friends']);
      }
      echo $xml->asXml();
    } else if (($mediaType == 'application/json')) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($people));
    }
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// handle GET requests for /people/:id
$app->get('/people/:id', 'authenticate', function ($id) use ($app) {    
  try {
    $person = R::findOne('people', 'id=?', array($id));
    if ($person) {
      $mediaType = $app->request()->getMediaType();
      if ($mediaType == 'application/xml') {
        $app->response()->header('Content-Type', 'application/xml');
        $xml = new SimpleXMLElement('<root/>');
        $result = R::exportAll($person);
        foreach ($result as $r) {
          $item = $xml->addChild('item');
          $item->addChild('guid', $r['guid']);
				$item->addChild('picture', $r['picture']);
				$item->addChild('age', $r['age']);
				$item->addChild('name', $r['name']);
				$item->addChild('gender', $r['gender']);
				$item->addChild('company', $r['company']);
				$item->addChild('phone', $r['phone']);
				$item->addChild('email', $r['email']);
				$item->addChild('address', $r['adress']);
				$item->addChild('about', $r['about']);
				$item->addChild('registered', $r['registered']);
				$item->addChild('tags', $r['tags']);
				$item->addChild('friends', $r['friends']); 
        }
        echo $xml->asXml();      
      } else if (($mediaType == 'application/json')) {
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(R::exportAll($person));
      }
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// handle POST requests for /people
$app->post('/people', 'authenticate', function () use ($app) {    
  try {
    $request = $app->request();
    $mediaType = $request->getMediaType();
    $body = $request->getBody();
    if ($mediaType == 'application/xml') {
      $input = simplexml_load_string($body);
    } elseif ($mediaType == 'application/json') {    
      $input = json_decode($body); 
    } 
    $person = R::dispense('people');
    $person->name = (int)$input->name;
    $person->gender = (string)$input->denger;
    $person->company = (string)$input->company;
    // @TODO add all fields
    $id = R::store($person);
    
    if ($mediaType == 'application/xml') {
      $app->response()->header('Content-Type', 'application/xml');
      $xml = new SimpleXMLElement('<root/>');
      $result = R::exportAll($person);
      foreach ($result as $r) {
        $item = $xml->addChild('item');
        $item->addChild('id', $r['id']);
        $item->addChild('guid', $r['guid']);
        $item->addChild('picture', $r['picture']); 
        $item->addChild('age', $r['age']); 
        // @TODO add all fields
      }
      echo $xml->asXml();          
    } elseif ($mediaType == 'application/json') {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($person));
    } 
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// handle PUT requests for /people
$app->put('/people/:id', 'authenticate', function ($id) use ($app) {    
  try {
    $request = $app->request();
    $mediaType = $request->getMediaType();
    $body = $request->getBody();
    if ($mediaType == 'application/xml') {
      $input = simplexml_load_string($body);
    } elseif ($mediaType == 'application/json') {    
      $input = json_decode($body); 
    } 
    $person = R::findOne('people', 'id=?', array($id));  
    if ($person) {      
      $person->name = (string)$input->name;
      $person->gender = (string)$input->gender;
      $person->company = (string)$input->company;
      // @TODO - add all fields
      R::store($person);    
      if ($mediaType == 'application/xml') {
        $app->response()->header('Content-Type', 'application/xml');
        $xml = new SimpleXMLElement('<root/>');
        $result = R::exportAll($person);
        foreach ($result as $r) {
          $item = $xml->addChild('item');
          $item->addChild('id', $r['id']);
          $item->addChild('title', $r['title']);
          $item->addChild('url', $r['url']); 
          $item->addChild('date', $r['date']); 
        }
        echo $xml->asXml();          
      } elseif ($mediaType == 'application/json') {
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(R::exportAll($person));
      } 
    } else {
      throw new ResourceNotFoundException();    
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// handle DELETE requests for /people
$app->delete('/people/:id', 'authenticate', function ($id) use ($app) {    
  try {
    $request = $app->request();
    $person = R::findOne('people', 'id=?', array($id));  
    if ($person) {
      R::trash($person);
      $app->response()->status(204);
    } else {
      throw new ResourceNotFoundException();
    }
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

// generates a temporary API key using cookies
// call this first to gain access to API methods
$app->get('/demo', function () use ($app) {    
  try {
    $app->setEncryptedCookie('uid', 'demo', '5 minutes');
    $app->setEncryptedCookie('key', 'demo', '5 minutes');
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});


// run
$app->run();