<?php

	namespace Framework\Routing;

	use Framework\Http\Request;
	use Framework\Routing\RouteCollection;

	class RouteNotFoundException extends \Exception {}

	class Router {
		
		private $routes;

		public function __construct(RouteCollection $routes) {
				
			$this->routes = $routes;

		}

		public function match(Request $request) {
			
			foreach($this->routes->getRoutes() as $route) {
				
				if(in_array($request->getRequestMethod(), $route->getMethods())) {
					
					if(strpos($route->getUri(), "{") !== false) {

							if(preg_match($route->getRegex(), $request->getRequestUri(), $matches)) {
								
								$attributes = $route->getAttributes();

								unset($matches[0]);

								if(count($matches) > 0) {
									$attributes['params'] = $matches;
								} else {
									$attributes['params'] = array();
								}

								return $attributes;

							}
							else continue;

					}
					else {
						if(!strcmp($route->getUri(), $request->getRequestUri())) {
							return $route->getAttributes();
						}
					}

				}

			}

			throw new RouteNotFoundException("Route not found.");

		}

	}