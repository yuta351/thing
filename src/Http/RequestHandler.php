<?php

	namespace Framework\Http;

	use Framework\Http\Request;
	use Framework\Routing\RouteCollection;
	use Framework\Routing\Router;
	use Framework\Routing\Route;
	use Framework\Routing\RouteNotFoundException;

	class RequestHandler {
		
		protected $routes;

		public function __construct() {
			
			$this->routes = new RouteCollection();
		} 

		//
		// handle() : Transforms a Request into a Response.
		//

		public function handle(Request $request) {
			
			// route request to controller
			// call controller wiht methods

			$router = new Router($this->routes);

			try {

				$attributes = $router->match($request);

				return call_user_func_array($attributes['controller'], $attributes['params']);

			}
			catch(RouteNotFoundException $e) {
				return new Response("The page you are looking for was not found on this server.", 404);
			}

		}

		//
		// mapRoute() : maps a route to a Controller
		//

		public function mapRoute(array $methods, string $uri, array $attributes) {
			
			$this->routes->add(
				new Route($methods, $uri, $attributes)
				);

		}

	}