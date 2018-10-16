<?php

	namespace Framework\Http;

	class JsonResponse extends Response {
		

		public function __construct(array $content, int $responseCode = 200, array $headers = array()) {
			
			$headers[] = "Content-type: application/json";

			parent::__construct(json_encode($content), $responseCode = $responseCode, $headers);
		}

	}