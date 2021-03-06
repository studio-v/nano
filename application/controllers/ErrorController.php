<?php

class ErrorController extends Nano_C {

	public $layout = 'empty';

	public function e404Action() {
		$this->markRendered();
		header('Content-Type: text/plain; charset=UTF-8', true);
		echo '404 Page not found' . PHP_EOL . PHP_EOL . $this->error;
	}

	public function e500Action() {
		$this->markRendered();
		header('Content-Type: text/plain; charset=UTF-8', true);
		echo '500 Internal error' . PHP_EOL . PHP_EOL . $this->error;
	}

}