<?php

	namespace Framework\Http;

	class Response {
		
		protected $content;
		protected $headers;
		protected $responseCode;

		public function __construct(string $content, int $responseCode = 200, array $headers = array()) {
			

			$this->responseCode = $responseCode;
			$this->content = $content;
			$this->headers = $headers;
		}

		public function send() {
			
			http_response_code($this->responseCode);

			foreach($this->headers as $header) {
				header($header);
			}

			echo $this->content;

		}


	}