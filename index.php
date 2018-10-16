<?php

	include "../framework/vendor/autoload.php";

	use Framework\Http\Request;
	use Framework\Http\Response;
	use Framework\Http\JsonResponse;
	use Framework\Http\RequestHandler;

	$request = Request::fromGlobals();

	/*switch($request->getRequestUri()) {
		
		case '/':
			$response = new Response("Home");
			break;
		case '/api':
			$response = new JsonResponse(['error' => 'true', 'details' => 'test error'], '500');
			break;
		default:
			$response = new Response("Not found", 404);
			break;

	}*/

	$handler = new RequestHandler();

	$handler->mapRoute(['GET'], '/home', ['controller' => function(){
		return new Response("Hey! This is the home page");
	}]);

	$handler->mapRoute(['GET'], '/user/{id}', ['controller' => function($id) {
		return new Response("Your user id is $id");
	}]);

	$response = $handler->handle($request);

	$response->send();