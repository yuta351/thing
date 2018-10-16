<?php
	
	namespace Framework\Routing;

	class RouteException extends \Exception {}

	class Route {
		
		private $methods;
		private $uri;
		private $attributes;
		private $regex;
		private $controller;

		public function __construct(array $requestMethods, string $uri, array $attributes) {
			
			$this->methods = $requestMethods;
			$this->uri = $uri;
			$this->attributes = $attributes;
			$this->attributes['params'] = array();

			if(!isset($attributes['controller'])) {
				throw new RouteException("This route does not have any controller asociated with it.");
			}

			$this->compileRegex();

		}

		private function compileRegex() {
			
			$this->regex = str_replace("/", "\\/", preg_replace("/{(\w+)}/", "(\w+)", $this->uri));

		}

		public function getRegex() {
			return "/" . $this->regex . "/";
		}

		public function getAttributes() {
			return $this->attributes;
		}

		public function getUri() {
			return $this->uri;
		}

		public function getMethods() {
			return $this->methods;
		}



	}