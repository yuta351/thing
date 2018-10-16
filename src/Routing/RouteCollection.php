<?php

	namespace Framework\Routing;

	use Framework\Routing\Route;

	class RouteCollectionException extends \Exception {} 		

	class RouteCollection {

		private $routes = array();
		
		public function __construct() {

		}

		public function add(Route $route) {
			$this->routes[] = $route;
		}

		public function getRoutes() {
			return $this->routes;
		}

	}