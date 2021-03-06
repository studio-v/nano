<?php

define('DS',          DIRECTORY_SEPARATOR);
define('PS',          PATH_SEPARATOR);
define('LIB',         __DIR__);
define('ROOT',        dirName(LIB));
define('PACK',        ROOT . DS . 'packages');
define('APP',         ROOT . DS . 'application');
define('SETTINGS',    APP . DS . 'settings');
define('CONTROLLERS', APP . DS . 'controllers');
define('MODELS',      APP . DS . 'models');
define('LAYOUTS',     APP . DS . 'layouts');
define('VIEWS',       APP . DS . 'views');
define('HELPERS',     APP . DS . 'helpers');
define('PLUGINS',     APP . DS . 'plugins');
define('APP_LIB',     APP . DS . 'library');
define('MESSAGES',    APP . DS . 'messages');
define('PUBLIC',      ROOT . DS . 'public');
define('TESTS',       ROOT . DS . 'tests');
define('ENV',         Nano::config('env'));
define('WEB_ROOT',    Nano::config('web')->root);
define('WEB_URL',     Nano::config('web')->url);

require LIB . DS . 'Nano' . DS . 'Loader.php';

final class Nano {

	/**
	 * @var Nano
	 */
	private static $instance = null;

	/**
	 * @var Nano_Dispatcher
	 */
	private $dispatcher;

	/**
	 * @var Nano_Routes
	 */
	private $routes;

	/**
	 * @var array
	 */
	private static $configs = array();

	/**
	 * @return Nano
	 */
	public static function instance() {
		if (null == self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @return void
	 * @param string $url
	 */
	public static function run($url = null) {
		self::instance();
		include SETTINGS . DS . 'routes.php';
		if (null === $url) {
			$url = $_SERVER['REQUEST_URI'];
			if (false !== strPos($url, '?')) {
				$url = subStr($url, 0, strPos($url, '?'));
			}
			if (self::config('web')->url && 0 === strPos($url, self::config('web')->url)) {
				$url = subStr($url, strLen(self::config('web')->url));
			}
			if (self::config('web')->index) {
				$url = preg_replace('/' . preg_quote(self::config('web')->index) . '$/', '', $url);
			}
		}
		echo self::instance()->dispatcher->dispatch(self::instance()->routes, $url);
	}

	/**
	 * @return string
	 * @param Nano_Route $route
	 */
	public static function runRoute(Nano_Route $route) {
		return self::instance()->dispatcher->run($route);
	}

	/**
	 * @return Nano_Routes
	 */
	public static function routes() {
		return self::instance()->routes;
	}

	/**
	 * @return Nano_Dispatcher
	 */
	public static function dispatcher() {
		return self::instance()->dispatcher;
	}

	/**
	 * @return Nano_HelperBroker
	 */
	public static function helper() {
		return Nano_HelperBroker::instance();
	}

	/**
	 * @return mixed
	 * @param string $name
	 */
	public static function config($name) {
		if (!isset(self::$configs[$name])) {
			$config = null;
			if (false === @include(SETTINGS . DS . $name . '.php')) {
				return false;
			}
			self::$configs[$name] = $config;
		}
		return self::$configs[$name];
	}

	/**
	 * @return void
	 */
	public static function reloadConfig() {
		self::$configs[] = array();
	}

	/**
	 * @return boolean
	 */
	public static function isTesting() {
		return isset($_COOKIE['PHPUNIT_SELENIUM_TEST_ID']) || defined('TESTING');
	}

	/**
	 * @return Nano_Db
	 * @param string $name
	 */
	public static function db($name = null) {
		return Nano_Db::instance($name);
	}

	/**
	 * @return Nano_Message
	 */
	public static function message() {
		return Nano_Message::instance();
	}

	/**
	 * @return boolean
	 * @param unknown_type $className
	 */
	public static function autoload($className) {
		try {
			if (false === include(Nano_Loader::classToPath($className))) {
				return false;
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	private function __construct() {
		spl_autoload_register('nano_autoload');
		$this->dispatcher = new Nano_Dispatcher();
		$this->routes     = new Nano_Routes();
		$this->initLibrary();
		$this->setupErrorReporting();
	}

	private function initLibrary() {
		set_include_path(
			LIB
			. PS . APP_LIB
			. PS . MODELS
			. PS . CONTROLLERS
			. PS . PLUGINS
			. PS . HELPERS
			. PS . get_include_path()
		);
	}

	private function setupErrorReporting() {
		if (self::config('web')->errorReporting) {
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', true);
		} else {
			error_reporting(0);
			ini_set('display_errors', false);
		}
	}

	private function __clone() { throw new RuntimeException(); }
	private function __sleep() { throw new RuntimeException(); }
	private function __wakeUp() { throw new RuntimeException(); }

}

function nano_autoload($className) {
	return Nano::autoload($className);
}