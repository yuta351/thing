<?php

	namespace Framework\Http;

	class RequestException extends \Exception {}

	class Request {
		
		private $requestMethod;
		private $requestUri;
		private $queryString;
		private $protocolVersion;
		private $remoteAddress;
		private $requestHeaders;

		private $get;
		private $post;

		private $input;

		private $session;
		private $cookies;

		public function __construct(string $requestMethod, 
									string $uri,
									array $get,
									array $post,
									array $server,
									array $cookies = array(),
									array $session = array(),
									string $input = null) {
			
			if(!$this->validRequestMethod($requestMethod)) {
				throw new RequestException("Invalid request method.");	
			}

			$this->requestMethod = $requestMethod;
			$this->requestUri = parse_url($uri, PHP_URL_PATH);

			$this->queryString = $_SERVER['QUERY_STRING'];

			$this->requestHeaders = $this->getHeaders($server);

			$this->remoteAddress = $server['REMOTE_ADDR'];
			$this->protocolVersion = $server['SERVER_PROTOCOL'];

			$this->get = $get;
			$this->post = $post;

			$this->cookies = $cookies;
			$this->session = $session;

			if($input) {
				$this->input = $input;
			}

		}

		public static function fromGlobals() {
			
			return new Request(
					$_SERVER["REQUEST_METHOD"],
					$_SERVER["REQUEST_URI"],
					$_GET,
					$_POST,
					$_SERVER,
					isset($_COOKIES) ? $_COOKIES : array(),
					isset($_SESSION) ? $_SESSION : array(),
					(isset($_SERVER['HTTP_CONTENT_LENGTH']) && $_SERVER['HTTP_CONTENT_LENGTH'] > 0) ? file_get_contents('php://input') : ""
				);

		}

		private function validRequestMethod(string $requestMethod) {
			$validRequestMethods = array(
					"GET",
					"POST",
					"HEAD",
					"PUT",
					"PATCH",
					"DELETE",
					"OPTIONS"
				);

			return in_array($requestMethod, $validRequestMethods);
		}

		private function getHeaders(array $server) {
			
			$output = array();

			foreach($server as $serverVar => $serverVal) {
				
				if(substr($serverVar, 0, 5) == 'HTTP_') {

					// Convert server keys to header names
					// Example: HTTP_CONTENT_LENGTH -> Content-length
					$output[
						ucwords(
							strtolower(
								str_replace("_", "-",
									str_replace("HTTP_", "", $serverVar)
									)
								)
							)
					] = $serverVal;
				}

			}

			return $output;
		}

		public function toString() {
			$req = sprintf("%s %s?%s %s\r\n", 
					$this->requestMethod,
					$this->requestUri,
					$this->queryString,
					$this->protocolVersion
				);
			
			foreach($this->requestHeaders as $header => $value) {
				$req .= sprintf("%s: %s\r\n", $header, $value);
			}

			$req .= "\r\n\r\n";

			if(strlen($this->input) > 0) {
				$req .= $this->input;
			}

			return $req;
		}

		 public function getRemoteAddress() {
		 	return $this->remoteAddress;
		 }

		 public function getProtocolVersion() {
		 	return $this->protocolVersion;
		 }

		 public function getRequestUri() {
		 	return $this->requestUri;
		 }

		 public function getQueryString() {
		 	return $this->queryString;
		 }

		 public function getRequestMethod() {
		 	return $this->requestMethod;
		 }

		 public function get(string $keyName) {
		 	return (isset($this->get[$keyName])) ? $this->get[$keyName] : "";
		 }
		  
		 public function post(string $keyName) {
		 	return (isset($this->post[$keyName])) ? $this->post[$keyName] : "";
		 }

		 public function getHeader(string $headerName) {
		 	
		 	foreach($this->requestHeaders as $header => $value) {
		 		if(strtolower($header) == strtolower($headerName)) {
		 			return $this->requestHeaders[$header];
		 		}
		 	}

		 	throw new RequestException("Header is not set.");

		 }

	}
